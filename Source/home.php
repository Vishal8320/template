<?php
              
          function PageMain(){
            global $TMPL, $LNG, $CONF, $db, $user;
          
            if(!empty($user)){

              $TMPL['logout_url'] = permalink($CONF['url'].'/index.php?a=home&rt=logout');
             
              if(isset($_GET['rt']) && $_GET['rt'] == 'logout'){
             
              $user = new User();
              $user->db = $db;
              $user->logOut(true);

              header('location:'.permalink($CONF['url'].'/index.php?a=login'));

          }
          $skin = new skin('error/404');
          return $skin->make();

        }else{
          $skin = new skin('error/401');
          return $skin->make();
        }
    }

            ?>
