#!/bin/sh

# Run tests

# Set ANSI Color Codes
nc='\033[0m'
red='\033[0;31m'
green='\033[0;32m'
yellow='\033[1;33m'

# Checks that need to be run before we can start
initialChecks() {
    # Gotta have PHP installed and in your path
    if [ ! $(which php) ]; then
        # If PHP isn't available, we can't do anything
        printf "${red}You need PHP to run this script.${nc}\n"

        # Set errors to true
        errors=true
    else
        # If PHP is installed, we'll set $php to the executable
        php=$(which php)
    fi

    # Next we'll check for PHP_CodeSniffer
    if [ ! -f bin/phpcs ]; then
        # We must have the composer installed version, not a globally
        # installed excutable. This is because of version dependencies.
        printf "${red}You need PHP_CodeSniffer to run this script.${nc}\n"
        printf "${red}Did you run setup.sh with the \"-t\" or \"-d\" argument \
first?${nc}"

        # Set errors to true
        errors=true
    fi

    # Next we'll check for PHPUnit
    if [ ! -f bin/phpunit ]; then
        # We must have the composer installed version, not a system globally
        # installed excutable. This is because of version dependencies.
        printf "${red}You need PHPUnit to run this script.${nc}\n"
        printf "${red}Did you run setup.sh with the \"-t\" or \"-d\" \
option first?${nc}\n"

        # Set errors to true
        errors=true
    fi

    # If there were any errors, return false. Otherwise, return true
    # This method will let us add more checks later on
    if [ $errors ]; then
        return 1
    else
        return 0
    fi
}

# Check that the composer.lock and composer.json files are in sync
runComposerCheck() {
    # Provided by a console command
    $php app/console tools:check-composer-lock

    if [ $? -eq 0 ]; then
        printf "${green}Ok${nc}\n"
        composer=true
    else
        printf "${red}Failed!${nc}\n"
        composer=false
    fi
}

runCodeSniffs() {
    # Run PHP_CodeSniffer to check coding style
    bin/phpcs --standard=PSR2 --ignore=web/bundles,web/js/vendor,web/css/vendor src web/css web/js

    if [ $? -eq 0 ]; then
        printf "${green}Ok${nc}\n"
        phpcs=true
    else
        printf "${red}Failed!${nc}\n"
        phpcs=false
    fi
}

runTests() {
    # Run PHPUnit tests
    bin/phpunit -c app

    if [ $? -eq 0 ]; then
        printf "${green}Ok${nc}\n"
        phpu=true
    else
        printf "${red}Failed!${nc}\n"
        phpu=false
    fi
}

run() {
    # Check composer.lock file
    printf "Checking that composer.lock and composer.json are in sync...\n"
    runComposerCheck

    # Run PHP_CodeSniffer
    printf "Running PHP_CodeSniffer to check for coding standard violations...\
\n"
    runCodeSniffs

    # Run PHPUnit
    printf "Running Unit/Functional tests...\n"
    runTests

    # Provide advice for some errors
    if [ $composer = false -o $phpcs = false -o $phpu = false ]; then
        printf "\n${red}Errors were encountered!${nc}\n"
        printf "${red}--------------------------------------------------\
---------------------${nc}\n"
        if [ $composer = false ]; then
            printf "\ncomposer.lock and composer.json are not in sync.\n"
            printf "If you made changes to composer.json, you need to run \"\
composer update\" to fix this.\n"
        fi
        if [ $phpcs = false ]; then
            printf "\nPHP_CodeSniffer detected some code standard violations.\n"
            printf "Most violations can be fixed by running this script with \
the \"-f\" option.\n"
        fi
        if [ $phpu = false ]; then
            printf "\nSome Unit/Functional tests failed.\n"
            printf "You'll need to figure out why and fix any issues before \
this branch will pass a CI build.\n"
        fi
        printf "${red}--------------------------------------------------\
---------------------${nc}\n"
        printf "\n${red}This branch will not pass a CI build!${nc}\n"
    else
        printf "\n${green}Completed Successfully!${nc}\n"
        printf "${green}--------------------------------------------------\
---------------------${nc}\n"
        printf "${green}Note this tool may not find all of the problems that \
could have an affect later\ndown the road. However, at this time, it was \
unable to find anything that would\nstop this branch from passing a CI build!\
${nc}\n"
        printf "${green}--------------------------------------------------\
---------------------${nc}\n"
        printf "${green}This branch should pass a CI build!\n"
    fi
}

runCodeFixer() {
    # Rub PHP Code Beautifier and Fixer
    bin/phpcbf --standard=PSR2 --ignore=web/bundles,web/js/vendor,web/css/vendor src web/css web/js
    if [ $? -eq 0 ]; then
        printf "${green}Your code should be much prettier now${nc}\n"
        printf "${green}Note this tool may not have fixed all of the issues.\
${nc}\n"
        printf "${green}You should probably run the test suite again to be sure.\
${nc}\n"
    else
        printf "${red}An unknown error occured.${nc}\n"
    fi
}

usage() {
    # Help text
    printf "Usage: $0 [OPTION](ARGUMENT)\n\nOptions:\n\t-h, --help\tDisplays \
this help text.\n\t-r, --run\tRuns the test suite.\n\t-f, --fix\tAutomatically \
fixes most PSR violations.\n"
    exit
}

trapUser() {
    # Trap user interupts
    # In case we run into a need to handle them later
    printf "\n\nTerminated by user!\n\n"

    # Remove temporary file created by PHPCBF
    rm 'phpcbf-fixed.diff'

    exit 1
}

trapGeneral() {
    # Trap other errors
    # In case we run into a need to handle them later
    printf "\n\nUnexpected problem encountered!\n\n"

    # Remove temporary file created by PHPCBF
    rm 'phpcbf-fixed.diff'

    exit 1
}

# Trap user interupts
trap trapUser INT QUIT

# Trap other trappable interupts
trap trapGeneral HUP TERM

# Parse options
case "$1" in
    -h | --help)
        usage
    ;;
    -r | --run)
        # Just run the test suite
        if initialChecks; then
            run
        fi
    ;;
    -f | --fix)
        # Lets beautify the code a bit
        if initialChecks; then
            runCodeFixer
        fi
    ;;
    *)
        # Invalid option, show the help text
        usage
    ;;
esac

echo ""
exit 0
