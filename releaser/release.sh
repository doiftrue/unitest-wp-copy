#!/usr/bin/env bash
set -euo pipefail # Fail fast on errors, unset vars, and pipeline failures.

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "${SCRIPT_DIR}/_utils.sh"

REPO_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"
cd "${REPO_ROOT}"

NOT_PUSH="${NOT_PUSH:-}"
WP_LINE="${WP_LINE:-}"  # 6.8

# Files to copy into the release worktree (alongside wp-runtime/).
RELEASE_FILES=(
	zero.php
	README.md
	SYMBOLS-INFO.md
)
VERSION_FILE="${REPO_ROOT}/VERSION"
RELEASE_TAG="$(build_release_tag "${WP_LINE}" "${VERSION_FILE}")" || exit 1
WP_LINE_BRANCH="wp-${WP_LINE}"
WORKTREE_DIR_REL="worktrees/${WP_LINE_BRANCH}"
WORKTREE_DIR="$(realpath -m "${WORKTREE_DIR_REL}")"

echo_cyan "[INFO] RELEASE_TAG: ${RELEASE_TAG}"

### CHECKS

# Tag exists
if git rev-parse --verify --quiet "refs/tags/${RELEASE_TAG}" >/dev/null; then
	echo_red "[STOP] Tag ${RELEASE_TAG} already exists" >&2
	exit 1
fi

# No branch
if ! git rev-parse --verify --quiet "refs/heads/${WP_LINE_BRANCH}" >/dev/null; then
	echo_red "[STOP] Branch ${WP_LINE_BRANCH} not found" >&2
	exit 1
fi

# Uncommitted changes
if [[ -n "$(git status --porcelain --untracked-files=all)" ]]; then
	echo_red "[STOP] Commit changes before starting the flow." >&2
	exit 1
fi

### MAIN FLOW

echo_cyan "➜ ➜ Switch WP to ${WP_LINE}.*"
run_php "composer require --dev wordpress/wordpress:${WP_LINE}.*  --no-interaction --no-update"
run_php "composer update wordpress/wordpress  --no-interaction --with-dependencies"

echo_cyan "➜ ➜ Run parser"
run_php "php parser/run.php"

echo_cyan "➜ ➜ Run tests"
run_php "composer run phpunit -- --colors=always"

echo_cyan "➜ ➜ Create/Reuse WORKTREE ${WORKTREE_DIR_REL}"
git worktree prune --expire now >/dev/null 2>&1
if git worktree list --porcelain | grep -Fqx "worktree ${WORKTREE_DIR}"; then
	worktree_branch="$(git -C "${WORKTREE_DIR}" rev-parse --abbrev-ref HEAD)"
	if [[ "${worktree_branch}" != "${WP_LINE_BRANCH}" ]]; then
		echo_red "[STOP] Existing worktree ${WORKTREE_DIR} is on branch ${worktree_branch}, expected ${WP_LINE_BRANCH}" >&2
		exit 1
	fi
else
	git worktree add "${WORKTREE_DIR}" "${WP_LINE_BRANCH}" >/dev/null
fi

# copy
echo_cyan "➜ ➜ Copy to WORKTREE ${WORKTREE_DIR_REL}"
cp -a "${RELEASE_FILES[@]}" "${WORKTREE_DIR}/"
printf '%s\n' "${RELEASE_TAG}" > "${WORKTREE_DIR}/VERSION"
rsync -a --delete --delete-excluded \
	--include="/wp-line-extra/${WP_LINE}/***" \
	--exclude="/wp-line-extra/*" \
	"wp-runtime/" "${WORKTREE_DIR}/wp-runtime/"

echo_cyan "➜ ➜ Reset all changes in current branch"
git reset --hard HEAD
run_php "composer install" # NOTE: to not change lock file

if [[ -n "${NOT_PUSH}" ]]; then
	echo_yellow "➜ ➜ Commit/tag/push skipped."
	exit 0
fi

# commit & push
echo_cyan "➜ ➜ Commit to WORKTREE ${WORKTREE_DIR_REL} and add TAG ${RELEASE_TAG}"
git -C "${WORKTREE_DIR}" add -A

if git -C "${WORKTREE_DIR}" diff --cached --quiet; then
	echo_yellow "Nothing to commit on ${WP_LINE_BRANCH}."
else
	git -C "${WORKTREE_DIR}" commit -m "Release ${RELEASE_TAG}"
	git -C "${WORKTREE_DIR}" tag "${RELEASE_TAG}"
	git -C "${WORKTREE_DIR}" push --atomic origin "${WP_LINE_BRANCH}" "refs/tags/${RELEASE_TAG}"
	echo_green "➜ ➜ ➜ ➜ Pushed with tag: ${RELEASE_TAG}"
fi
