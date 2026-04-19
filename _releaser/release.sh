#!/usr/bin/env bash
set -euo pipefail # Fail fast on errors, unset vars, and pipeline failures.

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "${SCRIPT_DIR}/_utils.sh"

REPO_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"
cd "${REPO_ROOT}"

WP_LINE="${WP_LINE:-}"          # 6.8
VERSION_FILE="${REPO_ROOT}/VERSION"
RELEASE_TAG="$(build_release_tag "${WP_LINE}" "${VERSION_FILE}")" || exit 1
WP_LINE_BRANCH="wp-${WP_LINE}"
WORKTREE_DIR_REL="worktrees/${WP_LINE_BRANCH}"
WORKTREE_DIR="$(realpath -m "${WORKTREE_DIR_REL}")"

cecho cyan "[INFO] RELEASE_TAG: ${RELEASE_TAG}"

### CHECKS

# Tag exists
if git rev-parse --verify --quiet "refs/tags/${RELEASE_TAG}" >/dev/null; then
	cecho red "[STOP] Tag ${RELEASE_TAG} already exists" >&2
	exit 1
fi

# No branch
if ! git rev-parse --verify --quiet "refs/heads/${WP_LINE_BRANCH}" >/dev/null; then
	cecho red "[STOP] Branch ${WP_LINE_BRANCH} not found" >&2
	exit 1
fi

# Uncommitted changes
if [[ -n "$(git status --porcelain --untracked-files=all)" ]]; then
	cecho red "[STOP] Commit changes before starting the flow." >&2
	exit 1
fi

### MAIN FLOW

cecho cyan "[STEP] Switch WP to ${WP_LINE}.*"
run_php "composer require --dev wordpress/wordpress:${WP_LINE}.*  --no-interaction --no-update"
run_php "composer update wordpress/wordpress  --no-interaction --with-dependencies"

cecho cyan "[STEP] Run parser"
run_php "php _parser/run.php"

cecho cyan "[STEP] Run tests"
run_php "composer run phpunit -- --colors=always"

cecho cyan "[STEP] Create/Reuse WORKTREE ${WORKTREE_DIR_REL}"
git worktree prune --expire now >/dev/null 2>&1
if git worktree list --porcelain | grep -Fqx "worktree ${WORKTREE_DIR}"; then
	worktree_branch="$(git -C "${WORKTREE_DIR}" rev-parse --abbrev-ref HEAD)"
	if [[ "${worktree_branch}" != "${WP_LINE_BRANCH}" ]]; then
		cecho red "[STOP] Existing worktree ${WORKTREE_DIR} is on branch ${worktree_branch}, expected ${WP_LINE_BRANCH}" >&2
		exit 1
	fi
else
	git worktree add "${WORKTREE_DIR}" "${WP_LINE_BRANCH}" >/dev/null
fi

cecho cyan "[STEP] Copy to WORKTREE ${WORKTREE_DIR_REL}"
rm -rf "${WORKTREE_DIR}/wp-runtime"
cp -a zero.php wp-runtime "${WORKTREE_DIR}/"

cecho cyan "[STEP] Reset all changes in current branch"
git reset --hard HEAD
run_php "composer update wordpress/wordpress"

cecho cyan "[STEP] Commit to WORKTREE ${WORKTREE_DIR_REL} and add TAG ${RELEASE_TAG}"
git -C "${WORKTREE_DIR}" add zero.php wp-runtime

if git -C "${WORKTREE_DIR}" diff --cached --quiet; then
	cecho yellow "Nothing to commit on ${WP_LINE_BRANCH}."
else
	git -C "${WORKTREE_DIR}" commit -m "Release ${RELEASE_TAG}"
	git -C "${WORKTREE_DIR}" tag "${RELEASE_TAG}"
	git -C "${WORKTREE_DIR}" push --atomic origin "${WP_LINE_BRANCH}" "refs/tags/${RELEASE_TAG}"
	cecho green "Pushed with tag: ${RELEASE_TAG}"
fi
