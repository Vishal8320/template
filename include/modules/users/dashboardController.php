<?php



require_once(__DIR__ . '/baseController.php');

class DashboardController extends BaseController {
    public function __construct($db) {
        parent::__construct($db);
    }

    public function index(){
  
       $skin = new skin('users/dashboard/content');
       return $skin->make();
        
    }

    public function view_url($requestData) {
        echo 'this from dashboard create function';
        // Apne custom logic
        $this->commonFunction(); // Reuse base class function
    }

    public function edit($requestData) {
        // Edit logic
    }

    // Baaki methods
}



?>