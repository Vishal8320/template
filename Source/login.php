<?php
                // require_once(__DIR__ . '/../include/autoload.php');
              
          function PageMain(){
            global $TMPL, $LNG, $CONF, $db, $user;


            if(!empty($user)){

              if(isset($_COOKIE['module'])){
                $module = $_COOKIE['module'];
              }else{
                $module = $_SESSION['user_permission'][0];
              }

              header('location:'.permalink($CONF['url'].'/index.php?a=modules&module='.$module));
          }else{
            
            if (isset($_POST['submit'])) {
             
              // Get form input values
              $username = trim($_POST['username']);
              $password = trim($_POST['password']);
        
              // Instantiate User class
              $user = new User();
              $user->db = $db;
              $user->uname = $username;
              $user->pass = $password;
          
              
              $auth = $user->auth(1);
            
              
              if(!is_array($auth)) {
             // Login failed message
                $TMPL['message'] = "username or Password is not matched.";
              } else {

                if ($_GET['a'] != 'login') {
                  $url = ''; 
                  foreach ($_GET as $key => $value) {
                      // Build the URL using ternary operator
                      $url .= empty($url) ? '?' : '&';
                      $url .= urlencode(trim($key)) . '=' . urlencode(trim($value));
                  }
              }

                header("Location: " . permalink($CONF['url'] . "/index.php" . $url));

              }
             
          }


          // Prepare template data
        
          $TMPL['title'] = 'login Page';
          $TMPL['showContent'] = true;
          $TMPL['items'] = ['Item 1', 'Item 2', 'Item 3', 'item4', 'items5'];
          $TMPL['titles'] = 
          [
            'admin' => ['name' => 'vishal', 'role' => 'admin', 'joined' => '31 aug']

          ];
          

           }

           $skin = new skin('welcome/login');
           return $skin->make();
          }




            ?>
