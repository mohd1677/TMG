#!/bin/sh

# Initial setup of the project

# Define a few default states.
preserveLogs=false

initialChecks() {
    # Gotta have PHP installed and in your path
    if [ ! $(which php) ]; then
        # If PHP isn't available, we can't contine
        echo "You need PHP to run this script."

        # Set errors to true
        errors=true
    else
        # If PHP is installed, we'll set $php to the executable
        php=$(which php)
    fi

    # If there were any errors, return false. Otherwise, return true
    # This method will let us add more checks later on
    if [ $errors ]; then
        return 1
    else
        return 0
    fi
}

# Function to get composer
# Uses composer.phar if it exists, otherwise checks to see if composer is
# installed globally. If we still don't have composer, we'll download it
getComposer() {
    # Check for "composer.phar" file in current directory
    if [ -f composer.phar ]; then
        composer="composer.phar"
    else
        # If it's not there, check to see if it's installed globally
        if [ $(which composer) ]; then
            composer=$(which composer)
        else
            # We couldn't find it, lets download it
            echo "Couldn't find composer anywhere, attempting to download it..."
            $php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
            $php -r "
                if (
                    hash('SHA384', file_get_contents('composer-setup.php'))
                    === 'fd26ce67e3b237fffd5e5544b45b0d92c41a4afe3e3f778e942e43ce6be197b9cdc7c251dcde6e2a52297ea269370680'
                ) {
                    echo 'Installer verified';
                } else {
                    echo 'Installer corrupt';
                    unlink('composer-setup.php');
                }"

            if [ -f composer-setup.php ]; then
                $php composer-setup.php
                $php -r "unlink('composer-setup.php');"
            fi

            if [ -f composer.phar ]; then
                composer="composer.phar"
            else
                # We couldn't get composer so we must die here
                echo "Failed to download composer!"
                exit 1
            fi
        fi
    fi

    # Once we have composer, lets see if it needs to be self-updated
    if test "`find $composer -mmin +43200`"; then
        if [ -w $composer ]; then
            echo "Updating Composer"
            $php $composer self-update
        else
            printf "Composer needs to be updated but the file is not writable.\
\nPerhaps you need to use \"sudo\"."
            exit 1
        fi
    fi
}

runMigrations() {
    printf "Checking for new database migrations...\n"

    # Here we are calling Doctrine to see if there are any migration available
    # The "awk" part of the command is simply search for "New Migrations:" and
    # then printing the corresponding field.
    # The "sed" portion of the command simply strips the formatting codes.
    migrations=`$php app/console doctrine:migrations:status \
| awk '/New Migrations:/ {print $NF}' \
| sed -e "s/\x1B\[([0-9]{1,2}(;[0-9]{1,2})?)?[m|K]//g"`

    if [ "$migrations" -gt "0" ]; then
        # If there are more than "0" "New Migrations", run them.
        # The migration itself already prompts to continue or bail
        # so we don't need to worry about it.
        printf "$migrations new migrations.\n"
        $php app/console doctrine:migrations:migrate
    else
        # If there aren't any, life is grand!
        printf "No new migrations.\n"
    fi
}

usage() {
    # Help text
    printf "Usage: $0 [OPTION](ARGUMENT)\n\nOptions:\n\t-h, --help\t\tDisplays \
this help text.\n\t-d, --dev\t\tSetup the project in \"dev\" mode.\n\t-t, \
--test\t\tSetup the project in \"test\" mode.\n\t-p, \
--prod\t\tSetup the project in \"production\" mode. \
(Excludes dev dependencies)\n\t-u, --update\t\tUpdate dependencies.\n"
    exit
}

trapUser() {
    # Trap user interupts
    # In case we run into a need to handle them later
    printf "\n\nTerminated by user!\n\n"
    exit 1
}

trapGeneral() {
    # Trap other errors
    # In case we run into a need to handle them later
    printf "\n\nUnexpected problem encountered!\n\n"
    exit 1
}

# Trap user interupts
trap trapUser INT QUIT

# Trap other trappable interupts
trap trapGeneral HUP TERM

# Parse option
case "$1" in
    -h | --help)
        usage
    ;;
    -d | --dev)
        # Development
        if initialChecks; then
            echo "Setting up project for development..."

            # Find composer or download it
            getComposer

            # Setup Symfony and dependencies
            export SYMFONY_ENV=dev
            $php $composer install --optimize-autoloader --prefer-dist

            # Check for migrations
            runMigrations

            # Correct permissions on cache and logs directories
            chmod a+rwx app/cache
            chmod a+rwx app/logs

            # Clear the cache (hardcore mode)
            echo "Clearing cache..."
            rm -rf app/cache/*
        fi
    ;;
    -t | --test)
        #Test
        if initialChecks; then
            echo "Setting up project for test..."

            # Find composer or download it
            getComposer

            # Setup Symfony and dependencies
            export SYMFONY_ENV=test
            $php $composer install --optimize-autoloader --prefer-dist
            $php app/console doctrine:schema:create
            $php app/console doctrine:fixtures:load

            # Correct permissions on cache and log directories
            chmod a+rwx app/cache
            chmod a+rwx app/logs

            # Clear the cache (hardcore mode)
            echo "Clearing Cache..."
            rm -rf app/cache/*
        fi
    ;;
    -p | --prod)
        # Production
        if initialChecks; then
            echo "Seting up project for production..."

            # Find composer or download it
            getComposer

            # Setup Symfony and dependencies
            export SYMFONY_ENV=prod
            $php $composer install --no-dev --optimize-autoloader --prefer-dist

            # Check for migrations
            runMigrations

            # Correct permissions on cache and log directories
            chmod a+rwx app/cache
            chmod a+rwx app/logs

            # Clear the cache (hardcore mode)
            echo "Clearing cache..."
            rm -rf app/cache/*
        fi
    ;;
    -u | --update)
        # Update composer
        if initialChecks; then
            echo "Updating..."

            # Update Composer dependencies
            $php $composer update --optimize-autoloader --prefer-dist

            # Fix permissions on cache directory
            chmod a+rwx app/cache

            # Clear the cache (hardcore mode)
            rm -rf app/cache/*
        fi
    ;;
    *)
        # Invalid option, show the help text
        usage
    ;;
esac
echo ""
exit 0
