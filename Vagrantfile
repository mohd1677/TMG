Vagrant.configure("2") do |config|
    config.vm.box = "ubuntu/trusty64"
    config.vm.hostname = "mrapi.dev"
    config.vm.network :forwarded_port, host: 8080, guest: 80

    # Required for NFS to work, pick any local IP
    config.vm.network :private_network, ip: '192.168.50.50'
    # Use NFS for shared folders for better performance
    config.vm.synced_folder '.', '/vagrant', nfs: true

    # Provision with Puppet stand alone. Puppet manifests are
    # contained in a directory path relative to this Vagrantfile.
    #
    config.vm.provision "puppet", run: "always" do |puppet|
        puppet.manifests_path = "manifests"
        puppet.manifest_file = "site.pp"
    end

    config.vm.provider "virtualbox" do |v|
      host = RbConfig::CONFIG['host_os']

      # Give VM 1/4 system memory & access to all cpu cores on the host
      if host =~ /darwin/
        cpus = `sysctl -n hw.ncpu`.to_i
        # sysctl returns Bytes and we need to convert to MB
        mem = `sysctl -n hw.memsize`.to_i / 1024 / 1024 / 4
      elsif host =~ /linux/
        cpus = `nproc`.to_i
        # meminfo shows KB and we need to convert to MB
        mem = `grep 'MemTotal' /proc/meminfo | sed -e 's/MemTotal://' -e 's/ kB//'`.to_i / 1024 / 4
      else # sorry Windows folks, I can't help you
        cpus = 2
        mem = 1024
      end

      v.customize ["modifyvm", :id, "--memory", mem]
      v.customize ["modifyvm", :id, "--cpus", cpus]
    end
end
