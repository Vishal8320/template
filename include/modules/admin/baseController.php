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
    public function userPermissions($uid = null, $module = null) {
        // Initialize the final arrays
        $resultArray = [
            'all_permissions' => [], // Array for all available permissions
            'user_permissions' => [] // Array for user-specific permissions
        ];
    
        // Base query for all permissions
        // $queryAllBase = "
        //   SELECT 
        //     m.module_name, 
        //     m.module_url, 
        //     s.section_name,
        //     s.section_url, 
        //     sl.list_name AS section_list_name, 
        //     a.action_name, 
        //     a.action_url
        //     FROM actions a
        //     LEFT JOIN sections s ON a.section_id = s.sid
        //     LEFT JOIN modules m ON a.module_id = m.mid
        //     LEFT JOIN section_lists sl ON a.section_list_id = sl.sl_id
        // ";
        $queryAllBase = "
                        SELECT 
                        m.module_name, 
                        m.module_url, 
                        s.section_name, 
                        s.section_url, 
                        sl.list_name AS section_list_name, 
                        a.action_name, 
                        a.action_url
                    FROM modules m
                    LEFT JOIN sections s ON m.mid = s.module_id
                    LEFT JOIN actions a ON s.sid = a.section_id
                    LEFT JOIN section_lists sl ON a.section_list_id = sl.sl_id";

        
        // Append module filter if $module is provided
        if ($module !== null) {
            $queryAllBase .= " WHERE m.module_name = ?";
        }
    
        $queryAll = $queryAllBase . " ORDER BY m.module_name, s.section_name, sl.list_name, a.action_name";
    
        // Query for user permissions
        $queryUserPermissions = "
                            SELECT 
                    m.module_name, 
                    m.module_url, 
                    s.section_name, 
                    s.section_url, 
                    sl.list_name AS section_list_name, 
                    a.action_name, 
                    a.action_url
                    FROM users_permissions up
                    JOIN modules m ON up.module_id = m.mid
                    JOIN sections s ON up.section_id = s.sid
                    JOIN actions a ON up.action_id = a.ac_id
                    LEFT JOIN section_lists sl ON a.section_list_id = sl.sl_id
                    WHERE up.uid = ?

        ";
    
        if ($module !== null) {
            $queryUserPermissions .= " AND m.module_name = ?";
        }
    
        // Fetch all permissions and user permissions in a single function
        $this->db->autocommit(false); // Start transaction
    
        try {
            // Fetch all permissions
            $stmtAll = $this->db->prepare($queryAll);
            if ($module !== null) {
                $stmtAll->bind_param('s', $module); // 's' denotes string parameter type
            }
            $stmtAll->execute();
            $resultAll = $stmtAll->get_result();
            if (!$resultAll) {
                throw new Exception('Error in query: ' . $this->db->error);
            }
    
            // Process all modules and permissions into the all_permissions array
            while ($row = $resultAll->fetch_assoc()) {
                $module_name = $row['module_name'];
                $section_name = $row['section_name'];
                $section_list_name = $row['section_list_name'];
    
                // Ensure module structure
                if (!isset($resultArray['all_permissions'][$module_name])) {
                    $resultArray['all_permissions'][$module_name] = [
                        'module_name' => $row['module_name'],
                        'module_url' => $row['module_url'],
                        'sections' => []
                    ];
                }
    
                // Ensure section structure
                if (!isset($resultArray['all_permissions'][$module_name]['sections'][$section_name])) {
                    $resultArray['all_permissions'][$module_name]['sections'][$section_name] = [
                        'section_name' => $row['section_name'],
                        'section_url' => $row['section_url'],
                        'section_lists' => []
                    ];
                }
    
                // Ensure section list structure
                if (!isset($resultArray['all_permissions'][$module_name]['sections'][$section_name]['section_lists'][$section_list_name])) {
                    $resultArray['all_permissions'][$module_name]['sections'][$section_name]['section_lists'][$section_list_name] = [];
                }
    
                // Add actions
                if (!in_array(['action_name' => $row['action_name'], 'action_url' => $row['action_url']], $resultArray['all_permissions'][$module_name]['sections'][$section_name]['section_lists'][$section_list_name])) {
                    $resultArray['all_permissions'][$module_name]['sections'][$section_name]['section_lists'][$section_list_name][] = [
                        'action_name' => $row['action_name'],
                        'action_url' => $row['action_url']
                    ];
                }
            }
    
            if ($uid !== null) {
                // Prepare and execute user permissions query
                $stmtUser = $this->db->prepare($queryUserPermissions);
                if ($module !== null) {
                    $stmtUser->bind_param('si', $module, $uid); // 's' for string and 'i' for integer
                } else {
                    $stmtUser->bind_param('i', $uid); // 'i' denotes integer parameter type
                }
                $stmtUser->execute();
                $resultUser = $stmtUser->get_result();
    
                if (!$resultUser) {
                    throw new Exception('Error in query: ' . $this->db->error);
                }
    
                // Process user permissions into the user_permissions array
                while ($row = $resultUser->fetch_assoc()) {
                    $module_name = $row['module_name'];
                    $section_name = $row['section_name'];
                    $section_list_name = $row['section_list_name'];
    
                    // Ensure module structure
                    if (!isset($resultArray['user_permissions'][$module_name])) {
                        $resultArray['user_permissions'][$module_name] = [
                            'module_name' => $row['module_name'],
                            'module_url' => $row['module_url'],
                            'sections' => []
                        ];
                    }
    
                    // Ensure section structure
                    if (!isset($resultArray['user_permissions'][$module_name]['sections'][$section_name])) {
                        $resultArray['user_permissions'][$module_name]['sections'][$section_name] = [
                            'section_name' => $row['section_name'],
                            'section_url' => $row['section_url'],
                            'section_lists' => []
                        ];
                    }
    
                    // Ensure section list structure
                    if (!isset($resultArray['user_permissions'][$module_name]['sections'][$section_name]['section_lists'][$section_list_name])) {
                        $resultArray['user_permissions'][$module_name]['sections'][$section_name]['section_lists'][$section_list_name] = [];
                    }
    
                    // Add actions
                    $resultArray['user_permissions'][$module_name]['sections'][$section_name]['section_lists'][$section_list_name][] = [
                        'action_name' => $row['action_name'],
                        'action_url' => $row['action_url']
                    ];
                }
            }
    
            // Commit transaction
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
            die($e->getMessage());
        } finally {
            $this->db->autocommit(true); // End transaction
        }
    
        return $resultArray;
    }
    

public function list_name() {
    $query = "SELECT * FROM `section_lists`";
    $run = $this->db->query($query);

    if ($run) {
        // Fetch all rows as an associative array
        $result = $run->fetch_all(MYSQLI_ASSOC);
        return $result;
    } else {
        // Handle query error
        return [];
    }
}




    // Baaki common methods
}




?>