#!/usr/bin/env bash
set -euo pipefail

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

ARTIFACT_DIR="tmp/wp-${WP_LINE}"
ARTIFACT_BRANCH="wp-${WP_LINE}"

if ! git rev-parse --verify --quiet "refs/heads/${ARTIFACT_BRANCH}" >/dev/null; then
	cecho red "[STOP] Branch ${ARTIFACT_BRANCH} not found" >&2
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

cecho cyan "[1/6] Switch wordpress/wordpress to ~${WP_LINE}.0"
run_php "composer require --dev wordpress/wordpress:~${WP_LINE}.0   --no-interaction --no-update"
run_php "composer update wordpress/wordpress   --no-interaction --with-dependencies"

cecho cyan "[2/6] Run parser"
run_php "php _parser/run.php"
cecho cyan "[2/6] Run tests"
run_php "composer run phpunit -- --colors=always"

cecho cyan "[3/6] Build artifact in ${ARTIFACT_DIR}"
rm -rf "${ARTIFACT_DIR}"
mkdir -p "${ARTIFACT_DIR}"
cp -a zero.php wp-runtime "${ARTIFACT_DIR}/"

cecho cyan "[3/6] Reset all changes made for artifact creation"
git reset --hard HEAD
run_php "composer update wordpress/wordpress"


return # skip for now

cecho cyan "[4/6] Update ${ARTIFACT_BRANCH}, commit and tag ${RELEASE_TAG}"
WORKTREE_DIR="$(mktemp -d "/tmp/wp-releaser-${WP_LINE}-XXXXXX")"
git worktree add --force "${WORKTREE_DIR}" "${ARTIFACT_BRANCH}" >/dev/null
rm -rf "${WORKTREE_DIR}/zero.php" "${WORKTREE_DIR}/wp-runtime"
cp -a "${ARTIFACT_DIR}/zero.php" "${ARTIFACT_DIR}/wp-runtime" "${WORKTREE_DIR}/"
git -C "${WORKTREE_DIR}" add zero.php wp-runtime

if git -C "${WORKTREE_DIR}" diff --cached --quiet; then
	echo "No artifact changes to commit on ${ARTIFACT_BRANCH}."
else
	git -C "${WORKTREE_DIR}" commit -m "Release ${RELEASE_TAG}"
	git -C "${WORKTREE_DIR}" tag "${RELEASE_TAG}"
	echo "Release tag created: ${RELEASE_TAG}"
fi
