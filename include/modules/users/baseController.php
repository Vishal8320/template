<?php

require_once(__DIR__ . '/dashboardController.php');

class BaseController {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }
    public function index(){
        $dashboard = new DashboardController($this->db);
        return $dashboard->index();
    }

    // Common function
    public function commonFunction() {
        // Common logic
    }

    // Baaki common methods
}




?>