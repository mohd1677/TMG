# Matrix Reloaded API

[![Build Status](http://ci.travelmediagroup.com/buildStatus/icon?job=MrAPI)](http://ci.travelmediagroup.com/job/MrAPI/)

## Setup
This project uses an Ubuntu/Trusty64 Vagrant box for development. This box is the recommended development environment. This helps to ensure that the environment the application is developed against matches it's production environment as close as possible. As such, how to set this project up outside of the VM will not be covered here. To get started you will need to have a box "provider" installed and ready to go. The Vagrant docs and these instructions assume you have [VirtualBox](https://www.virtualbox.org/wiki/Downloads) installed. For more information about Vagrant Providers, see [this page](http://docs.vagrantup.com/v2/getting-started/providers.html).

Next you will need to have [Vagrant](https://www.vagrantup.com/) installed. You can download Vagrant for most platforms [here](https://www.vagrantup.com/downloads.html). Once you have Vagrant installed and ready to go, you can procede with these setup instructions.

1. Clone the repository.
  * `git clone git@github.dominionenterprises.com:TravelMediaGroup/matrix-reloaded-api.git`

2. Drop into the newly created project.
  * `cd matrix-reloaded-api`

3. Setup and configure the VM.
  * `vagrant up`
  
  Vagrant will automatically provision the VM when it is booted up. This means that Apache, PHP, all of the necessary PHP extensions and any other packages will be installed and configured automatically. We simply need to worry about setting up the project itself.

4. Once the vagrant box is up and running, SSH into it.
  * `vagrant ssh`

5. Switch to the project directory on the VM.
  * `cd /var/www/`

6. Run setup.sh and give it the dev flag.
  * `./setup.sh -d`
  
  The setup script will:
    * look for [Composer](https://getcomposer.org/) and download it, if it can't be found.
    * Run `composer install` with the appropriate flags for the environment being configured.
      * The composer install will prompt you for several bits of information:
        * database_driver: accept default
        * database_host: evoke.travelmediagroup.com
        * database_port: accept default
        * database_name: qa-matrix-reloaded
        * database_user: {your username}
        * database_password: {your password}
        * mailer_transport: accept default
        * mailer_host: accept default
        * mailer_user: accept default
        * mailer_password: accept default
        * locale: accept default
        * secret: accept default
        * debug_toolbar: accept default
        * debug_redirects: accept default
        * use_assetic_controller: accept default
        * api_version: accept default
        * newrelic_reporting_enabled: accept default
        * newrelic_application_name: accept default
        * newrelic_api_key: accept default
        * newrelic_license_key: accept default
    * Clear the cache for all environments.

7. Logout of the VM.
  * `exit`

At this point you should be able to open your browser and navigate to http://localhost:8080/

## Notes
### Mapped Directories
* Vagrant auotmatically mapps your project directory to `/vagrant` on the VM. The `project` directory is then automatically symlinked to `/var/www` on the VM. Meaning any changes you make in `project`, will automatically and instantly appear inside of the VM at `/var/www`. This allows you to use your favorite IDE to work with the local files the way you normally would. Likewise, any changes done in the root of the project will automatically and instantly appear inside of the vm at `/vagrant`

### Apache Logs
* Taking advantage of the mapped directories, Apache writes it's log files to `project/app/logs/apache_*.log`. This means you can review Apache's log files with your "local" tools.

### Remote Access to app_dev.php
* If you want to avoid caching during development, you will need to make a slight modification to `project/web/app_dev.php` to make it allow connections from "remote" hosts. Open the file in your favorite text editor and comment out lines 12 - 18. The reason these lines are not commented out in the repo is because we don't want to accidentally allow access to this file when the application is running in beta/production environments. Once you have commented out the lines and saved the file, You should be able to hit it in your browser at http://localhost:8080/app_dev.php/.
  * If you would like to avoid accidentally commiting changes to this file, use git's `update-index` functionality. 
    * `git update-index --assume-unchanged project/web/app_dev.php`
    
    However keep in mind, this will get in the way if you have legitamate changes to make to the file. If you need to start tracking changes again, use
    * `git update-index --no-assume-unchanged project/web/app_dev.php`
  
    Then you can start commiting changes to that file again.
  

