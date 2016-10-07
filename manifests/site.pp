exec {"apt-get update":
    path => "/usr/bin",
}

package {"apache2":
    ensure => "installed",
    require => Exec["apt-get update"],
}

package {"memcached":
    ensure => "installed",
    require => Exec["apt-get update"],
}

service {"apache2":
    ensure => "running",
    require => Package["apache2"],
}

service {"memcached":
    ensure => "running",
    require => Package["memcached"]
}

package {["mysql-client", "curl", "make"]:
    ensure => "installed",
    require => Exec["apt-get update"],
}

package {["libapache2-mod-php5", "php5", "php5-mysql", "php5-intl", "php5-xdebug", "php5-curl", "php5-memcached"]:
    ensure => "installed",
    notify => Service["apache2"],
    require => [Exec["apt-get update"], Package['mysql-client'], Package['apache2']],
}

exec {"/usr/sbin/a2enmod rewrite":
    unless => "/bin/readlink -e /etc/apache2/mods-enabled/rewrite.load",
    notify => Service["apache2"],
    require => Package["apache2"],
}

file {"/var/www/":
    ensure => "link",
    target => "/vagrant/project",
    require => Package["apache2"],
    notify => Service["apache2"],
    replace => yes,
    force => true,
}

file {"/etc/apache2/sites-enabled/000-default.conf":
    ensure => "link",
    target => "/vagrant/manifests/assets/vhost.conf",
    require => Package["apache2"],
    notify => Service["apache2"],
    replace => yes,
    force => true,
}

exec {"PHP5SessionConfiguration":
    command => "/bin/sed -i 's/session.save_handler = files/session.save_handler = memcached\\nsession.save_path = \"127.0.0.1:11211\"/' /etc/php5/apache2/php.ini",
    onlyif => "/bin/grep -c '^session.save_handler = files' /etc/php5/apache2/php.ini",
    require => Package["apache2"],
    notify => Service["apache2"],
}

exec {"ApacheUserChange":
    command => "/bin/sed -i 's/APACHE_RUN_USER=www-data/APACHE_RUN_USER=vagrant/' /etc/apache2/envvars",
    onlyif  => "/bin/grep -c 'APACHE_RUN_USER=www-data' /etc/apache2/envvars",
    require => Package["apache2"],
    notify  => Service["apache2"],
}

exec {"ApacheGroupChange":
    command => "/bin/sed -i 's/APACHE_RUN_GROUP=www-data/APACHE_RUN_GROUP=vagrant/' /etc/apache2/envvars",
    onlyif  => "/bin/grep -c 'APACHE_RUN_GROUP=www-data' /etc/apache2/envvars",
    require => Package["apache2"],
    notify  => Service["apache2"],
}

exec {"apache_lockfile_permissions":
    command => "/bin/chown -R vagrant:www-data /var/lock/apache2",
    require => Package["apache2"],
    notify  => Service["apache2"],
}