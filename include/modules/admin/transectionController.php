<?php


require_once(__DIR__ . '/baseController.php');

class transectionController extends BaseController {
    public function __construct($db) {
        parent::__construct($db);
    }

    public function index(){
        echo 'hi from admin transection function()';
    }
    public function user_permission($request) {
        global $TMPL;
        $uid = $request['GET']['uid'];
        
        // Fetch data based on UID
        $data = ($uid) ? $this->userPermissions(1, null) : $this->userPermissions();
        

        // Prepare data for the Blade template
        $TMPL['data'] = $data;
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = false; // Flag to track successful inserts
            
            foreach ($_POST['permissions'] as $module_name => $module_data) {
                // Fetch the module_id before deleting the existing records
                $module_id_sql = "SELECT mid FROM modules WHERE module_name = ?";
                $module_stmt = $this->db->prepare($module_id_sql);
                $module_stmt->bind_param("s", $module_name);
                $module_stmt->execute();
                $module_result = $module_stmt->get_result();
                $module_id = null;
                
                if ($module_result->num_rows > 0) {
                    $module_row = $module_result->fetch_assoc();
                    $module_id = $module_row['mid'];
                }
    
                $module_stmt->close();
    
                // Delete existing permissions for the user and the specific module_id
                if ($module_id) {
                    $delete_sql = "DELETE FROM users_permissions WHERE uid = ? AND module_id = ?";
                    $delete_stmt = $this->db->prepare($delete_sql);
                    $delete_stmt->bind_param("ii", $uid, $module_id);
                    $delete_stmt->execute();

                    // Check if rows were affected
                    if ($delete_stmt->affected_rows > 0) {
                        $rows_affected = true;
                    }
                    $delete_stmt->close();
                }
    
                foreach ($module_data['section'] as $section_name => $section_data) {
                    foreach ($section_data['action'] as $action_url => $status) {
                        if ($status === 'on') { // Only process actions that are 'on'
                            // Prepare SQL to get module_id, section_id, and action_id
                            $sql = "SELECT 
                                        a.ac_id AS action_id, 
                                        a.module_id, 
                                        a.section_id 
                                    FROM actions a
                                    JOIN sections s ON a.section_id = s.sid
                                    JOIN modules m ON a.module_id = m.mid
                                    WHERE m.module_name = ? 
                                    AND s.section_name = ? 
                                    AND a.action_url = ?";
    
                            // Prepare and bind parameters to the SQL statement
                            $stmt = $this->db->prepare($sql);
                            $stmt->bind_param("sss", $module_name, $section_name, $action_url);
                            $stmt->execute();
                            $result = $stmt->get_result();
    
                            // Fetch the result
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $module_id = $row['module_id'];
                                    $section_id = $row['section_id'];
                                    $action_id = $row['action_id'];
    
                                    // Insert data into users_permissions table
                                    $insert_sql = "INSERT INTO users_permissions (uid, module_id, section_id, action_id) VALUES (?, ?, ?, ?)";
                                    $insert_stmt = $this->db->prepare($insert_sql);
                                    $insert_stmt->bind_param("iiii", $uid, $module_id, $section_id, $action_id);
                                    $insert_stmt->execute();
                                    if ($insert_stmt->affected_rows > 0) {
                                        $success = true;
                                    }
                                    $insert_stmt->close();
                                }
                            }
                            $stmt->close();
                        }
                    }
                }
            }

            // if old records dropped then set auto_inceament 
            // if ($rows_affected) {
            //     $this->db->query("SET @rank := 0;");
            //     $this->db->query("UPDATE users_permissions SET pid = (@rank := @rank + 1);");
            // }
    
            // Set the output message
            if ($success) {
                $TMPL['output'] = ['status' => true, 'message' => 'Permissions have been successfully updated.'];
            } else {
                $TMPL['output'] = ['status' => false, 'error' => 'No permissions were updated. Please check the input data.'];
            }
        }
    
        // Create a new instance of the skin class
        $skin = new skin('admin/transection/user_permission');
        return $skin->make();
    }
    
    public function module($request)
    {
         global $TMPL, $CONF;
         
        //  $postData = isset($request['POST']) ? $request['POST'] : [];

         if(isset($_GET['mode']) && $_GET['mode'] == 'create'){

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = $_POST;

        $validationErrors = $this->validateModuleArray($data);
        if (!empty($validationErrors)) {
           $TMPL['output'] = ['status'=> false, $validationErrors];
        } else {
           
            $output = $this->insertModuleData($data);
            $TMPL['output'] = $output;
          if($output['status'] == true){
             
          }
        }
            }
        // Render the view
        $skin = new skin('admin/transection/module/create_module');

        return $skin->make();

    }elseif(isset($_GET['mode']) && $_GET['mode'] == 'update'){

        if(isset($_GET['module_name'])){

            $TMPL['data'] = $this->userPermissions(null,$_GET['module_name'])['all_permissions'];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = $_POST;
               $validationErrors = $this->validateModuleArray($data);
               if (!empty($validationErrors)) {
                   $TMPL['output'] = ['status'=> false, 'error' => $validationErrors];
               } else {
                   $output = $this->updateModuleData($data);
                   $TMPL['output'] = $output;
                 if($output['status'] == true){
                    $_POST[''];
                  
                 }
                   
               }
           }
          


           $skin = new skin('admin/transection/module/update_module');
           return $skin->make();
        }
        
       die('Invalid Module Name');
       
    }

    $query = "SELECT * FROM modules";
    $stmt = $this->db->prepare($query);
    
    $all_module = []; // Default to empty array
    
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        $all_module = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
    }

    // Assign to TMPL
    $TMPL['all_module'] = $all_module;

    $TMPL['update_map_link'] = permalink($CONF['url'].'/index.php?a=modules&module='.$_GET['module'].'&section='.$_GET['section'].'&action='.$_GET['action']);

    $skin = new skin('admin/transection/module');
    return $skin->make();

    }
    
    
    public function updateModuleData($formattedData) {
        // Assuming you have a $this->db for database connection (mysqli instance)
        $result = [];
        $db = $this->db;
    
        // Start transaction
        $db->begin_transaction();
    
        try {
            // Iterate over each module in the formatted data
            foreach ($formattedData['module'] as $moduleKey => $module) {
                // Prepare and sanitize module name
                $moduleName = trim($moduleKey);
    
                $moduleId = null;
    
                // Find the module ID using the module name
                $stmt = $db->prepare("SELECT mid FROM modules WHERE module_name = ?");
                $stmt->bind_param("s", $moduleName);
                $stmt->execute();
                $stmt->store_result();
    
                // Check if the module exists
                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($moduleId);
                    $stmt->fetch();
                    $stmt->close(); // Close the statement


                  /*
                  we required these drop links if do'nt create contrait. we created already so
                  we have CASECADE ON (update&delete) so we comment this section 
                  *
                  *
                  */
                    // 1. Delete `section_lists` data through `actions`
                    $stmt = $db->prepare("DELETE section_lists FROM section_lists 
                                          INNER JOIN actions ON section_lists.sl_id = actions.section_list_id 
                                          WHERE actions.module_id = ?");
                    $stmt->bind_param("i", $moduleId);
                    $stmt->execute();
                    $stmt->close();
    
                    // 2. Delete `sections` data through `actions`
                    $stmt = $db->prepare("DELETE sections FROM sections 
                                          INNER JOIN actions ON sections.sid = actions.section_id 
                                          WHERE actions.module_id = ?");
                    $stmt->bind_param("i", $moduleId);
                    $stmt->execute();
                    $stmt->close();
    
                    // 3. Delete `actions` data using `module_id`
                    $stmt = $db->prepare("DELETE FROM actions WHERE module_id = ?");
                    $stmt->bind_param("i", $moduleId);
                    $stmt->execute();
                    $stmt->close();

                    /*
                    *
                    *
                    *
                    useless drop table (cause of constrait) section ended
                    */

                    // 4. Delete from `modules` using `module_id`
                    $stmt = $db->prepare("DELETE FROM modules WHERE mid = ?");
                    $stmt->bind_param("i", $moduleId);
                    $stmt->execute();
                    $stmt->close();
    
                    // Reset the AUTO_INCREMENT value based on the current maximum value
                    $tables = [
                        'modules' => 'mid',
                        'sections' => 'sid',
                        'section_lists' => 'sl_id',
                        'actions' => 'ac_id'
                    ];
                    $maxId = null;
                    foreach ($tables as $table => $pk) {
                        // Get the maximum ID from the table
                        $stmt = $db->prepare("SELECT MAX($pk) FROM $table");
                        $stmt->execute();
                        $stmt->bind_result($maxId);
                        $stmt->fetch();
                        $stmt->close();
    
                        $maxId = $maxId ?: 0;
    
                        // Reset AUTO_INCREMENT value
                        $db->query("ALTER TABLE $table AUTO_INCREMENT = " . ($maxId + 1));
                    }
                    
                } else {
                    $result = ['status' => false, 'error' => "Warning: Module '{$moduleName}' does not exist in the database."];
                   
                }
    
                // Insert new data after deletion
               $insert = $this->insertModuleData($formattedData);
            }
    
            // Commit transaction
            $db->commit();
            if($insert['status'] === true){
                $msg = "Data Successfully Dropped And Created";
            }else{
                $msg = "Data Successfully Only Dropped";
            }

            $result = ['status' => true, 'message' => $msg];
    
        } catch (Exception $e) {
            // Rollback transaction in case of error
            $db->rollback();
           $result = ['status' => false, 'error' => "Error: " . $e->getMessage()];
        }

        return $result;
    }
    
    
    
    
    
    public function insertModuleData($formattedData) {
        $result = [];
        // Assuming you have a $this->db for database connection (mysqli instance)
        $db = $this->db;
    
        // Start transaction
        $db->begin_transaction();
    
        try {
            // Iterate over each module in the formatted data
            foreach ($formattedData['module'] as $moduleKey => $module) {
                // Prepare and sanitize module name and URL
                $moduleName = trim($moduleKey);
                $moduleUrl = $this->sanitizeUrl($moduleName); // Use sanitized module name as module_url
    
                // 1. Insert into `modules`
                $stmt = $db->prepare("INSERT INTO modules (module_name, module_url) VALUES (?, ?)");
                $stmt->bind_param("ss", $moduleName, $moduleUrl);
                $stmt->execute();
                $moduleId = $db->insert_id; // Get the last inserted module_id
    
                // Validate `module_data`
                if (!isset($module['module_data']) || !is_array($module['module_data'])) {
                    throw new Exception("Module data missing for module '{$moduleKey}'.");
                 
                }
    
                // 2. Insert into `sections`
                foreach ($module['module_data']['section'] as $sectionKey => $section) {
                    // Prepare and sanitize section name and URL
                    $sectionName = trim($sectionKey);
                    $sectionUrl = $this->sanitizeUrl($sectionName); // Use sanitized section name as section_url
    
                    $stmt = $db->prepare("INSERT INTO sections (module_id, section_name, section_url) VALUES (?, ?, ?)");
                    $stmt->bind_param("iss", $moduleId, $sectionName, $sectionUrl);
                    $stmt->execute();
                    $sectionId = $db->insert_id; // Get the last inserted section_id
    
                    // 3. Insert into `section_lists`
                    foreach ($section['section_list'] as $listKey => $sectionList) {
                        // Prepare and sanitize list name
                        $listName = trim($listKey);
                        
                        if ($listName === 'null') {
                            $sectionListId = null; // Skip the insertion and set sectionListId to null
                        } else {
                            // Sanitize the list name
                            $listName = strtolower(trim($listName));
    
                            $stmt = $db->prepare("INSERT INTO section_lists (list_name) VALUES (?)");
                            $stmt->bind_param("s",$listName);
                            $stmt->execute();
                            $sectionListId = $db->insert_id; // Get the last inserted section_list_id
                        }
    
                        // 4. Insert into `actions`
                        // Insert `action_name` values
                        foreach ($sectionList['action_name'] as $actionNameIndex => $actionName) {
                            // Prepare and sanitize action name and URL
                            $actionName = trim($actionName);
                            $actionUrl = isset($sectionList['action_url'][$actionNameIndex]) ? trim($sectionList['action_url'][$actionNameIndex]) : '';
                            $actionName = $this->sanitizeUrl($actionName); // Sanitize action name
                            $actionUrl = $this->sanitizeUrl($actionUrl);   // Sanitize action URL
    
                            $stmt = $db->prepare("INSERT INTO actions (section_list_id, section_id, module_id, action_name, action_url) VALUES (?, ?, ?, ?, ?)");
                            $stmt->bind_param("iiiss", $sectionListId, $sectionId, $moduleId, $actionName, $actionUrl);
                            $stmt->execute();
                        }
                    }
                }
            }
    
            // Commit transaction
            $db->commit();
           
            $result = ['status'=> true, 'message' => "Success: Data has been successfully inserted into the database."];
    
        } catch (Exception $e) {
            // Rollback transaction in case of error
            $db->rollback();
           
            $result = ['status'=> false, 'error' => "Error: " . $e->getMessage()];
        }

        return $result;
    }
    
    // Helper function to sanitize URLs and names
    private function sanitizeUrl($string) {
        // Remove spaces, convert to lowercase, and keep only alphanumeric characters and underscores
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9_]/', '_', $string);
        return $string;
    }
    
    
    
    
    
    function validateModuleArray($moduleArray) {
        $errors = [];
    
        // Step 1: Validate the presence of 'module' and its keys
        if (!isset($moduleArray['module']) || !is_array($moduleArray['module'])) {
            $errors[] = "Missing 'module' key or it is not an array.";
            return $errors;
        }
    
        foreach ($moduleArray['module'] as $moduleKey => $module) {
            if (!isset($module['module_data'])) {
                $errors[] = "Module data missing for module '{$moduleKey}'.";
                continue; // Skip further checks for this module
            }
    
            if (!isset($module['module_data']['section']) || !is_array($module['module_data']['section'])) {
                $errors[] = "'section' key is required and should be an array in module '{$moduleKey}'.";
                continue; // Skip further checks for this module
            }
    
            // Step 2: Validate each section
            foreach ($module['module_data']['section'] as $sectionKey => $section) {
                if (!is_array($section)) {
                    $errors[] = "Section '{$sectionKey}' should be an array.";
                    continue; // Skip further checks for this section
                }
    
                if (!isset($section['section_list']) || !is_array($section['section_list'])) {
                    $errors[] = "Section '{$sectionKey}' must have a 'section_list' array.";
                    continue; // Skip further checks for this section
                }
    
                // Step 3: Validate each section list within the section
                foreach ($section['section_list'] as $listKey => $sectionList) {
                    if (!is_array($sectionList)) {
                        $errors[] = "Section list '{$listKey}' in section '{$sectionKey}' should be an array.";
                        continue; // Skip further checks for this list
                    }
    
                    if (!isset($sectionList['action_name']) || !is_array($sectionList['action_name'])) {
                        $errors[] = "Section list '{$listKey}' in section '{$sectionKey}' must have an 'action_name' array.";
                    } else {
                        // Validate each action name
                        foreach ($sectionList['action_name'] as $actionNameIndex => $actionName) {
                            if (empty($actionName)) {
                                $errors[] = "Action name at index '{$actionNameIndex}' in section list '{$listKey}' of section '{$sectionKey}' is empty.";
                            }
                        }
                    }
    
                    if (!isset($sectionList['action_url']) || !is_array($sectionList['action_url'])) {
                        $errors[] = "Section list '{$listKey}' in section '{$sectionKey}' must have an 'action_url' array.";
                    } else {
                        // Validate each action URL
                        foreach ($sectionList['action_url'] as $actionUrlIndex => $actionUrl) {
                            if (empty($actionUrl)) {
                                $errors[] = "Action URL at index '{$actionUrlIndex}' in section list '{$listKey}' of section '{$sectionKey}' is empty.";
                            }
                        }
                    }
                }
            }
        }
    
        // Return the errors or an empty array if no errors
        return $errors;
    }
    

    
    
    
    

    public function edit($requestData) {
        // Edit logic
    }

    // Baaki methods
}



?>