#!/usr/bin/env bash
set -euo pipefail # Fail fast on errors, unset vars, and pipeline failures.

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "${SCRIPT_DIR}/_utils.sh"

REPO_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"
cd "${REPO_ROOT}"

WP_LINE="${WP_LINE:-}"          # 6.8
RELEASE_TAG="${RELEASE_TAG:-}"  # 6.8.x

if [[ -z "${WP_LINE}" ]]; then
	cecho red "[STOP] Set required env var: WP_LINE (example: 6.8)" >&2
	exit 1
fi

if [[ -z "${RELEASE_TAG}" ]]; then
	cecho red "[STOP] Set required env var: RELEASE_TAG (example: 6.8.5.10)" >&2
	exit 1
fi

if [[ -n "$(git status --porcelain --untracked-files=all)" ]]; then
	cecho red "[STOP] Commit changes before starting the flow." >&2
	exit 1
fi

WP_LINE_BRANCH="wp-${WP_LINE}"
WORKTREE_DIR="worktrees/${WP_LINE_BRANCH}"
WORKTREE_DIR="$(realpath -m "${WORKTREE_DIR}")"

if ! git rev-parse --verify --quiet "refs/heads/${WP_LINE_BRANCH}" >/dev/null; then
	cecho red "[STOP] Branch ${WP_LINE_BRANCH} not found" >&2
	exit 1
fi

if git rev-parse --verify --quiet "refs/tags/${RELEASE_TAG}" >/dev/null; then
	cecho "[STOP] Tag ${RELEASE_TAG} already exists" >&2
	exit 1
fi

run_php() {
	local cmd="$1"
	docker run --rm --name UNITEST_WP_COPY__php --user 1000:1000 \
		-v "${REPO_ROOT}:/app" -w /app \
		composer sh -c "${cmd}"
}

cecho cyan "[STEP] Switch wordpress/wordpress to ${WP_LINE}.*"
run_php "composer require --dev wordpress/wordpress:${WP_LINE}.*   --no-interaction --no-update"
run_php "composer update wordpress/wordpress   --no-interaction --with-dependencies"

cecho cyan "[STEP] Run parser"
run_php "php _parser/run.php"

cecho cyan "[STEP] Run tests"
run_php "composer run phpunit -- --colors=always"

cecho cyan "[STEP] Create/Reuse WORKTREE ${WORKTREE_DIR}"
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

cecho cyan "[STEP] Copy to ${WORKTREE_DIR}"
rm -rf "${WORKTREE_DIR}/wp-runtime"
cp -a zero.php wp-runtime "${WORKTREE_DIR}/"

cecho cyan "[STEP] Reset all changes in current branch"
git reset --hard HEAD
run_php "composer update wordpress/wordpress"

cecho cyan "[STEP] Commit to ${WORKTREE_DIR} and add tag ${RELEASE_TAG}"
git -C "${WORKTREE_DIR}" add zero.php wp-runtime

if git -C "${WORKTREE_DIR}" diff --cached --quiet; then
	echo "No artifact changes to commit on ${WP_LINE_BRANCH}."
else
	git -C "${WORKTREE_DIR}" commit -m "Release ${RELEASE_TAG}"
	git -C "${WORKTREE_DIR}" tag "${RELEASE_TAG}"
	echo "Release tag created: ${RELEASE_TAG}"
fi
