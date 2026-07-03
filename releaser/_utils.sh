
run_php() {
	local cmd="$1"
	docker run --rm --name UNITEST_WP_COPY__php --user 1000:1000 \
		-v "${REPO_ROOT}:/app" -w /app \
		composer sh -c "${cmd}"
}

function echo_red(){ _echo_color 31 "$@"; }
function echo_green(){ _echo_color 32 "$@"; }
function echo_yellow(){ _echo_color 33 "$@"; }
function echo_blue(){ _echo_color 34 "$@"; }
function echo_magenta(){ _echo_color 35 "$@"; }
function echo_cyan(){ _echo_color 36 "$@"; }
function echo_white(){ _echo_color 37 "$@"; }
function echo_darkgray(){ _echo_color 90 "$@"; }
function echo_lightblue(){ _echo_color 94 "$@"; }

# Display a message using the given ANSI color code.
function _echo_color(){
	local color_code="$1"
	shift
	echo -e "\033[${color_code}m$*\033[0m"
}

# Build release tag from WP line and VERSION file.
#
# Parameters:
#   $1 (wp_line):      Target WordPress line (example: 6.8).
#   $2 (version_file): Path to VERSION file.
#
# Output:
#   Prints generated release tag to stdout.
#
# Returns:
#   0 on success, non-zero on validation error.
#
function build_release_tag(){
	local wp_line="$1"
	local version_file="$2"
	local version_value
	local -a version_parts
	local part
	local version_suffix
	local release_tag

	if [[ -z "${wp_line}" ]]; then
		echo_red "[STOP] Set required env var: WP_LINE (example: 6.8)" >&2
		return 1
	fi

	if [[ ! -f "${version_file}" ]]; then
		echo_red "[STOP] VERSION file not found: ${version_file}" >&2
		return 1
	fi

	version_value="$(tr -d '[:space:]' < "${version_file}")"
	if [[ -z "${version_value}" ]]; then
		echo_red "[STOP] VERSION file is empty" >&2
		return 1
	fi

	IFS='.' read -r -a version_parts <<< "${version_value}"
	if (( ${#version_parts[@]} < 2 )); then
		echo_red "[STOP] VERSION must contain at least two dot-separated numbers (got: ${version_value})" >&2
		return 1
	fi

	for part in "${version_parts[@]}"; do
		if [[ ! "${part}" =~ ^[0-9]+$ ]]; then
			echo_red "[STOP] VERSION must contain numbers only (got: ${version_value})" >&2
			return 1
		fi
	done

	version_suffix="${version_parts[$((${#version_parts[@]} - 2))]}.${version_parts[$((${#version_parts[@]} - 1))]}"
	release_tag="${wp_line}.${version_suffix}"

	if [[ ! "${release_tag}" =~ ^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
		echo_red "[STOP] RELEASE_TAG format is invalid: ${release_tag}" >&2
		return 1
	fi

	echo "${release_tag}"
}
