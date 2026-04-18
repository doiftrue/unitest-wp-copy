
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
