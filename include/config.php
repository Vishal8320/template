<?php
              error_reporting(1);
              $CONF = array();

              $CONF['host'] = 'localhost';
              $CONF['user'] =  'root';
              $CONF['pass'] =  '';
              $CONF['name'] =   'dairy';

              $host = "http://localhost/mvc3";

 
              $CONF['url'] =$host;
              $CONF['email'] = 'vishal.dh8320@gmail.com';
              $CONF['theme_path'] = 'theme';


          
              $action = array(
                            'login'            => 'login',
                            'home'             => 'home',
                            'modules'         => 'modules',
                            'info'            => 'info',
                            'global_admin'    => 'admin',
                            'welcome'         => 'welcome'
                            );
       
          define('COOKIE_PATH',preg_replace('|https?://[^/]+|i','',$CONF['url']).'/');       

?>