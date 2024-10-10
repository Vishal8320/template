<?php


require_once(__DIR__ . '/baseController.php');

class DashboardController extends BaseController {
    public function __construct($db) {
        parent::__construct($db);
    }

    public function index(){
        
        echo 'hi from user transection index function()';
    }

   

    public function edit($requestData) {
        // Edit logic
    }

    // Baaki methods
}



?>