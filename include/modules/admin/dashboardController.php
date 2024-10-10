<?php

require_once(__DIR__ . '/baseController.php');


class DashboardController extends BaseController {
    public function __construct($db) {
        parent::__construct($db);
    }

    public function index(){
        echo 'admin module dashboard index';

    }
    public function create($requestData) {
        // Apne custom logic
        $this->commonFunction(); // Reuse base class function
    }

    public function edit($requestData) {
        // Edit logic
    }

    // Baaki methods
}



?>