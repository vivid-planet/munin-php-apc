CONTENTS OF THIS FILE
---------------------

 * Requirements
 * Documentation
 * Usage
 * Archive contents
 * TODO
 * Licensing


REQUIREMENTS
------------

  The php_apc_ plugin is made for the Munin v1.4.0+ monitoring system.

  The Munin homepage can be found at: http://munin-monitoring.org/


DOCUMENTATION
-------------

  Project homepage:  http://code.google.com/p/munin-php-apc/


USAGE
-----

  Copy the file apc_info.php to a location accessable to the web server, such as:
    www.example.com/apc_info.php
  
  Add the following lines to the munin-node file, usually found in:
    /etc/munin/plugin-conf.d/munin-node
  
  [php_apc_*]
  user root
  env.url http://www.example.com/apc_info.php?auto
  
  
  There are 6 available graphs for this multi graph plugin:
    - php_apc_files
    - php_apc_fragmentation
    - php_apc_hit_miss
    - php_apc_purge
    - php_apc_rates
    - php_apc_usage

  Each required graph should be added to the plugins directory, usually found in:
    /etc/munin/plugins/
    
  The common approach is to copy the file apc_php_ to the directory /usr/share/munin/plugins/
  and the add a symantic link to it from /etc/munin/plugins/
  
  For example:
    sudo ln -s /usr/share/munin/plugins/php_apc_ /etc/munin/plugins/php_apc_files
    sudo ln -s /usr/share/munin/plugins/php_apc_ /etc/munin/plugins/php_apc_fragmentation
    ..


  For more information regarding installation of Munin plugins, consult:
    http://munin-monitoring.org/wiki/Documentation
    

ARCHIVE CONTENTS
----------------

  The complete project archive contains the following files:

    php_apc_        - the php_apc_ Munin plugin.
    apc_info.php    - the PHP script that is called by the plugin.
    CHANGELOG.txt   - a list of changes made to the project.
    README.txt      - this file.


TODO
----

    - Rewrite Munin plugin in PHP rather than Perl.


LICENSING
---------

  php_apc_ is licensed under MIT (http://www.opensource.org/licenses/mit-license.php).