<?php


class User {
    public $db;
    public $uname;
    public $pass;

    function auth($type = null) {
        global $LNG;

        // Check if cookies are set
        if (isset($_COOKIE['username']) && isset($_COOKIE['userToken'])) {
            $this->uname = $_COOKIE['username'];
            $auth = $this->get(1); // Pass type as 1 to fetch using login_token

            if ($auth && $auth['login_token'] === $_COOKIE['userToken']) {
                $_SESSION['uid'] = $auth['uid']; // Store user ID in session
                $this->fetchPermissions($_SESSION['uid']); // Fetch permissions with UID
                $logged = true;
            } else {
                $logged = false;
            }
        } 
        // Check if session is set
        elseif (isset($_SESSION['username']) && isset($_SESSION['password'])) {
            $this->uname = $_SESSION['username'];
            $this->pass = $_SESSION['password'];
            $auth = $this->get();

            if ($auth && $this->pass === $auth['password']) {
                $_SESSION['uid'] = $auth['uid']; // Store user ID in session
                $this->fetchPermissions($_SESSION['uid']); // Fetch permissions with UID
                $logged = true;
            } else {
                $logged = false;
            }
        } 
        // Process login form input
        elseif ($type) {
            $auth = $this->get();
            
            if ($auth && !empty($auth['password']) && password_verify($this->pass, $auth['password'])) {
                    setcookie("username", $auth['username'], time() + 30 * 24 * 60 * 60, COOKIE_PATH);
                    setcookie("userToken", $auth['login_token'], time() + 30 * 24 * 60 * 60, COOKIE_PATH);
                

                $_SESSION['username'] = $auth['username'];
                $_SESSION['password'] = $auth['password'];
                $_SESSION['uid'] = $auth['uid']; // Store user ID in session

                $this->fetchPermissions($_SESSION['uid']); // Fetch permissions with UID

                $logged = true;
                session_regenerate_id();
            } else {
                return $LNG['login_faild'];
            }
        }

        if (isset($logged) && $logged == true) {
            return $auth;
        } elseif (isset($logged) && $logged == false) {
            $this->logOut();
            return $LNG['login_faild'];
        }

        return false;
    }

    function get($type = null) {
       
        $extra = $type ? sprintf(" AND `login_token` = '%s'", $this->db->real_escape_string($_COOKIE['userToken'])) : '';
       
        $field = filter_var($this->uname, FILTER_VALIDATE_EMAIL) ? 'email' : (ctype_digit($this->uname) ? 'phone_no' : 'username');
        
        $query = sprintf(
            "SELECT * FROM `users` WHERE `%s` = '%s' %s",
            $field,
            $this->db->real_escape_string(trim(strtolower($this->uname))),
            $extra
        );

     
        if (!$result = $this->db->query($query)) {
            return false;
        }

        return $result->fetch_assoc();
    }

    function logOut($rt = null) {
        if ($rt == true) {
            $this->resetToken();
        }
        setcookie("userToken", '', time()-3600, COOKIE_PATH);
        setcookie("username", '', time()-3600, COOKIE_PATH);
        unset($_SESSION['username']);
        unset($_SESSION['password']);
        unset($_SESSION['user_permission']);
        unset($_SESSION['uid']);
        unset($_SESSION['token_id']);
    }

    function resetToken() {
        $newToken = bin2hex(random_bytes(16)); // Generate a new secure token
        $this->db->query(sprintf(
            "UPDATE `users` SET `login_token` = '%s' WHERE `username` = '%s'",
            $newToken,
            $this->db->real_escape_string($this->uname)
        ));
    }

    // Function to fetch user permissions and store them in session
    function fetchPermissions($uid) {
        global $db;
        if (empty($_SESSION['user_permission'])) {
           
        
        // SQL query to fetch user permissions with accurate joins
        $query = "
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
                WHERE up.uid = ?;
        ";
       
       
        // Prepare statement to prevent SQL injection
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $uid); // 'i' denotes integer parameter type
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Check if query executed successfully
        if (!$result) {
            die('Error in query: ' . $db->error);
        }
    
        $permissions = [];
    
        // Process the result set
        while ($row = $result->fetch_assoc()) {
            $module_name = $row['module_name'];
            $section_name = $row['section_name'];
            $section_list_name = $row['section_list_name'];
    
            // Ensure module structure
            if (!isset($permissions[$module_name])) {
                $permissions[$module_name] = [
                    'module_name' => $row['module_name'],
                    'module_url' => $row['module_url'],
                    'section' => []
                ];
            }
    
            // Ensure section structure
            if (!isset($permissions[$module_name]['section'][$section_name])) {
                $permissions[$module_name]['section'][$section_name] = [
                    'section_name' => $row['section_name'],
                    'section_url' => $row['section_url'],
                    'section_list' => []
                ];
            }
    
            // Ensure section_list structure
            if (!isset($permissions[$module_name]['section'][$section_name]['section_list'][$section_list_name])) {
                $permissions[$module_name]['section'][$section_name]['section_list'][$section_list_name] = [];
            }
    
            // Add actions
            $permissions[$module_name]['section'][$section_name]['section_list'][$section_list_name][] = [
                'action_name' => $row['action_name'],
                'action_url' => $row['action_url']
            ];
        }
        // Store permissions in session
        $_SESSION['user_permission'] = $permissions;
    }
    
    }
    
    
    
    
}


?>