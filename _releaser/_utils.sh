
# Display a color-coded message on the terminal.
#
# Parameters:
#   $1 (color_name): The name of the color. One of:
#                    red, green, yellow, blue, magenta, cyan, white, darkgray, lightblue.
#   $@ (message):    The message. Backslash escapes characters are allowed: \t, \n.
#
# Returns: None
#
function cecho(){
    local color_name="$1"
    shift
    local message="$@"
    local color_code

    # Convert color name to respective color code
    case "$color_name" in
        red)       color_code=31  ;;
        green)     color_code=32  ;;
        yellow)    color_code=33  ;;
        blue)      color_code=34  ;;
        magenta)   color_code=35  ;;
        cyan)      color_code=36  ;;
        white)     color_code=37  ;;
        darkgray)  color_code=90  ;;
        lightblue) color_code=94  ;;
        *)         color_code=0   ;; # Default: No color
    esac

    echo -e "\033[${color_code}m${message}\033[0m"
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
        cecho red "[STOP] Set required env var: WP_LINE (example: 6.8)" >&2
        return 1
    fi

    if [[ ! -f "${version_file}" ]]; then
        cecho red "[STOP] VERSION file not found: ${version_file}" >&2
        return 1
    fi

    version_value="$(tr -d '[:space:]' < "${version_file}")"
    if [[ -z "${version_value}" ]]; then
        cecho red "[STOP] VERSION file is empty" >&2
        return 1
    fi

    IFS='.' read -r -a version_parts <<< "${version_value}"
    if (( ${#version_parts[@]} < 2 )); then
        cecho red "[STOP] VERSION must contain at least two dot-separated numbers (got: ${version_value})" >&2
        return 1
    fi

    for part in "${version_parts[@]}"; do
        if [[ ! "${part}" =~ ^[0-9]+$ ]]; then
            cecho red "[STOP] VERSION must contain numbers only (got: ${version_value})" >&2
            return 1
        fi
    done

    version_suffix="${version_parts[$((${#version_parts[@]} - 2))]}.${version_parts[$((${#version_parts[@]} - 1))]}"
    release_tag="${wp_line}.${version_suffix}"

    if [[ ! "${release_tag}" =~ ^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
        cecho red "[STOP] RELEASE_TAG format is invalid: ${release_tag}" >&2
        return 1
    fi

    echo "${release_tag}"
}
