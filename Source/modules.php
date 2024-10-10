<?php

function PageMain(){
    global $TMPL, $LNG, $CONF, $db, $user;
    

    $TMPL['url'] = $CONF['url'];
    $TMPL['user'] = $user;

    // Step 1: Check User Permission
    if (empty($user) || empty($_SESSION['user_permission'])) {
        $skin = new skin('error/401');
        return $skin->make();
    }
    $TMPL['profile_pic'] = permalink($CONF['url'].'/image.php?t=a&src=default.jpg');
    $TMPL['username'] = $user['username'];

    $userPermissions = $_SESSION['user_permission'];
    $TMPL['permissions'] = $userPermissions;
    $TMPL['module_data'] = $userPermissions[$_GET['module']];

    $TMPL['module_link'] = permalink($CONF['url'].'/index.php?a=modules&module=');
    $TMPL['module_link2'] = permalink($CONF['url'].'/index.php?a=modules&module='.$_GET['module']);
    $TMPL['current_module'] = $_GET['module'];
    
    // Step 2: Match Module, Section, and Action
    $requestedModule = $_GET['module'] ?? '';
    $requestedSection = $_GET['section'] ?? '';
    $requestedAction = $_GET['action'] ?? 'index'; // Default action is 'index'


    $moduleFound = false;
    $sectionFound = false;
    $actionFound = false;

    foreach ($userPermissions as $permission) {
       
       
        if ($permission['module_url'] === $requestedModule) {
        
            $moduleFound = true;
            
            foreach ($permission['section'] as $section) {
            

                if ($section['section_url'] === $requestedSection) {

                    $sectionFound = true;
                   
                    foreach ($section['section_list'] as $list) {
                        foreach ($list as $action) {

                            if ($action['action_url'] === $requestedAction || empty($requestedAction) || $requestedAction == 'index') {
                                $actionFound = true;
                               
                                break 3;
                            }
                        }
                    }
                }
            }
        }
    }


    // echo $moduleFound. ' <= m ';
    // echo '<br>';
    // echo $sectionFound. ' <= s';
    // echo '<br>';
    //  echo $actionFound. ' <= ac';
    // die;
    // Step 3: Check if Valid Module, Section, and Action
    $baseDir = realpath(__DIR__ . "/../include/modules/");

    if($moduleFound && (!$requestedAction || $requestedAction == 'index') && !$sectionFound){
       
        $controllerFile = $baseDir . "/{$requestedModule}/baseController.php";
        $controllerClassName = 'BaseController';
    }elseif($moduleFound && $sectionFound && $actionFound){
        
        $controllerFile = $baseDir . "/{$requestedModule}/{$requestedSection}Controller.php";
        $controllerClassName = ucfirst($requestedSection) . 'Controller';
    }else{
        
        $skin = new skin('error/401');  
        return  $skin->make();
        
    }
    
  
    //  echo $controllerFile;
    //  die;

    // Step 4: Include the Correct Section Controller
    if (!file_exists($controllerFile)) {
          
        $skin = new skin('error/500');
        return $skin->make();
    }

    require_once $controllerFile;
    
     

    if (!class_exists($controllerClassName)) {
       
        $skin = new skin('error/500');
        return $skin->make();
    }

     setcookie('module', $_GET['module'], 0, '/');

    // Step 5: Instantiate the Section Controller and Call the Appropriate Action
    $controller = new $controllerClassName($db);

    if (method_exists($controller, $requestedAction)) {
       
       // Handle request data
       $requestData = array_merge(['GET' => $_GET], ['POST' => $_POST]); // Combine GET and POST data
    
       $result = $controller->$requestedAction($requestData);

    } elseif (method_exists($controller,  $requestedAction)) {
       
      // Handle request data
      $requestData = array_merge(['GET' => $_GET], ['POST' => $_POST]); // Combine GET and POST data
      $result = $controller->index($requestData);
    } else {
        $skin = new skin('error/500');
        return $skin->make();
    }
     
      $TMPL['container'] = $result;
      $TMPL['logout_url'] = permalink($CONF['url'].'/index.php?a=home&rt=logout');

      $layout = new skin('layout/layout');

      $layout =  $layout->make();
   
      return $layout;
}
?>
