<?php

$time_start = microtime(true);
require_once(__DIR__ . '/include/autoload.php');
global $CONF, $TMPL, $user, $db;

$Actionpage = array_flip($action);

if (isset($_GET['a']) && isset($action[$_GET['a']])) {
    if (!empty($user)) {
        $page_name = $action[$_GET['a']];
    } elseif (isset($_GET['a']) && $_GET['a'] !== '' && !isset($action[$_GET['a']])) {
        $page_name = '404';
    } else {
        $page_name = 'login';
    }
} else {
    $page_name = 'login';
}

require_once("./Source/{$page_name}.php");

// Fetch content from PageMain function
$content = PageMain();



$TMPL['content'] = $content;

$TMPL['url'] = $CONF['url'];

if (isAjax()) {
    echo json_encode(array('content' => $content, 'script' => Skin::getStack('content_script'), 'title' => $TMPL['title']));
    mysqli_close($db);
    return;
}


$skin = new skin('wrapper');
echo $skin->make();

mysqli_close($db);
?>
