<?php
                require_once(__DIR__ . '/states.php');
                
                
                function getSettings() {
                    $querySettings = "SELECT * from `settings`";
                    return $querySettings;
                }
                function menu(){
                    
                }


              
                class load_cites{
                    public $db;
                    public $state;
                    public $district;
                    public $sub_district;
                    
                    public function do_query($type,$data){
                        if($type==1){
                            $query = sprintf('SELECT `d_id`,`district_name` FROM `districts` WHERE `states_id` = %s',$data);
                        }elseif($type==2){
                            $query = sprintf('SELECT `sd_id`,`sub_district_name` FROM `sub_districts` WHERE `district_id` = %s',$data);
                        }
                        
                        return $query;
                    }
                    public function load_district($state,$current=null){
                        global $LNG;
                        $query = $this->do_query(1,$state);
                        $result = $this->db->query($query);
                        
                        $rows = "<option value=''>{$LNG['select_districts']}</option>";
                        if($result && $result->num_rows > 0){
                            while($row = $result->fetch_assoc()) {
                                if($row['d_id'] == $current) {
                                    $selected = ' selected="selected"';
                                } else {
                                    $selected = '';
                                }
                                $rows .= '<option value="'.$row['d_id'].'"'.$selected.'>'.$row['district_name'].'</option>';
                
                            }    
                    }
                    return $rows;
                    }
                    public function load_sub_district($district,$current=null){
                        global $LNG;
                        $query = $this->do_query(2,$district);
                        $result = $this->db->query($query);
                        $rows = "<option value=''>{$LNG['select_sub_districts']}</option>";
                        if($result && $result->num_rows > 0){
                            while($row = $result->fetch_assoc()) {
                                if($row['sd_id'] == $current) {
                                    $selected = ' selected="selected"';
                                } else {
                                    $selected = '';
                                }
                                $rows .= '<option value="'.$row['sd_id'].'"'.$selected.'>'.$row['sub_district_name'].'</option>';
                            }    
                    }
                    return $rows;
                    }
                    public function process($type){
                        if($type==1){
                        $output =  $this->load_district($this->state);
                        }elseif($type==2){
                        $output = $this->load_sub_district($this->district);
                        }
                        return $output;
                    }

                }
       class locality{
        public $db;
        public $state;
        public $district;
        public $sub_district;
        public $area;
        public $search_term;

        public function locality_query($type=null,$state,$district,$sub_district,$search_term){
            if($type){
                $query = sprintf("SELECT `village_name`, `local_name` FROM `villages` WHERE (`state_id` = %s AND `district_id` = %s AND `sub_district_id` = %s) AND `village_name` LIKE '%s';", 
                    $this->db->real_escape_string($state),
                    $this->db->real_escape_string($district),
                    $this->db->real_escape_string($sub_district),
                    $this->db->real_escape_string($search_term.'%'));
            } else {
                $query = sprintf("SELECT `colony_name` FROM `colonies` WHERE (`state_id` = %s AND `district_id` = %s AND `sub_district_id` = %s) AND `colony_name` LIKE '%s';",
                    $this->db->real_escape_string($state),
                    $this->db->real_escape_string($district),
                    $this->db->real_escape_string($sub_district),
                    $this->db->real_escape_string($search_term.'%'));
            }

            $result = $this->db->query($query);
            $num_rows = $result->num_rows;
            if($result && $num_rows > 0){
                $output = $result->fetch_all(MYSQLI_ASSOC);
            }
            return ['rows' => $num_rows, 'output' =>$output];
        }
        public function process(){

            if($this->area==1){
                $local = $this->locality_query(1,$this->state,$this->district,$this->sub_district,$this->search_term);
                // For Village
                $output = $local['output'];
            
            $rows = $local['rows'];
            $count = 1;
            $suggestion = '';
            $h = 1;
            $pattern = '/\((\d+)?\)/';

            foreach($output as $locality){
               $class = 'row_' . ($h % 2 + 1);

                $filter_village = trim(preg_replace($pattern, '', $locality['village_name']));
               
               $suggestion .=  "<div class='$class' id='local_$count' village_$count='{$filter_village}' onclick='fill_suggestion(\"#local_$count\", \"#locality_id\",$count)'>{$locality['village_name']}</div>";

                    $count++; // Increment the counter
                    $h++;
                    if ($count >= 4) { // Break the loop after 3 iterations
                        break;
                    }
            }

            }else{
                $local = $this->locality_query(0,$this->state,$this->district,$this->sub_district,$this->search_term);
                // For Colonies
                $output = $local['output'];
            
            $rows = $local['rows'];
            $count = 1;
            $suggestion = '';
            $h = 1;
            $pattern = '/\((\d+)?\)/';

            foreach($output as $locality){
               $class = 'row_' . ($h % 2 + 1);

                $filter_village = trim(preg_replace($pattern, '', $locality['colony_name']));
               $suggestion .=  "<div class='$class' id='local_$count' village_$count='{$filter_village}' onclick='fill_suggestion(\"#local_$count\", \"#locality_id\",$count)'>{$locality['colony_name']}</div>";

                    $count++; // Increment the counter
                    $h++;
                    if ($count >= 4) { // Break the loop after 3 iterations
                        break;
                    }
            }

            }
           
            
            return ['rows' => $rows, 'suggestion' => $suggestion];
            
        }
       }
       class send_sms{

       public function hit_api($message_id,$p_num,$array){
          $a = $this->array_into_readable($array);
            // $url = sprintf("https://www.fast2sms.com/dev/bulkV2?authorization=%s&sender_id=%s&message=%s&variables_values=%s&route=dlt&numbers=%s",'Vkz9BH16LITExd3gvn2YAPGibp8oau4MKRlhDU50qO7wJmWcseirT87ZyPRAlapFwbCch1W2Ln9mzIg4','DUDBZR',$message_id,urlencode($username.'|'.$otp),urlencode($p_num));
            $url = sprintf("https://www.fast2sms.com/dev/bulkV2?authorization=%s&sender_id=%s&message=%s&variables_values=%s&route=dlt&numbers=%s",
                'Vkz9BH16LITExd3gvn2YAPGibp8oau4MKRlhDU50qO7wJmWcseirT87ZyPRAlapFwbCch1W2Ln9mzIg4',
                'DUDBZR',
                $message_id,
                urlencode($this->array_into_readable($array)),
                urlencode($p_num)
            );
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 1,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
            echo "cURL Error #:" . $err;
            } else {
            $output = $response;
            }
            return $output;
          }

       public function array_into_readable($array) {
            if (count($array) == 2) {
                return $array[0] . '|' . $array[1];
            } else if (count($array) == 1) {
                return $array[0];
            } else {
                return '';
            }
        }
        
        public function process($message_id,$p_num,$array){

            $output = $this->hit_api($message_id,$p_num,$array);
           return $output;
           
        }
        
        
       }

       
    
    function percentage($current, $old) {
        // Prevent dividing by zero
        if($old != 0) {
            $result = number_format((($current - $old) / $old * 100), 0);
        } else {
            $result = 0;
        }
        if($result < 0) {
            return '<span class="negative">'.$result.'%</span>';
        } elseif($result > 0) {
            return '<span class="positive">+'.$result.'%</span>';
        } else {
            return '<span class="neutral">'.$result.'%</span>';
        }
    }
    function parseCallback($matches) {
        // If match www. at the beginning of the string, add http before
        if(substr($matches[1], 0, 4) == 'www.') {
            $url = 'http://'.$matches[1];
        } else {
            $url = $matches[1];
        }
        return '<a href="'.$url.'" target="_blank" rel="nofollow">'.$matches[1].'</a>';
    }
    function generateTimezoneForm($current) {
        global $LNG;
        $rows = '<option value="" '.($current == '' ? ' selected' : '').'>'.$LNG['default'].'</option>';
        foreach(timezone_identifiers_list() as $value) {
            $rows .= '<option value="'.htmlspecialchars($value).'" '.($current == $value ? ' selected' : '').'>'.$value.'</option>';
        }
        
        return $rows;
    }
    function generateDateForm($type, $current) {
        global $LNG;
        $rows = '';
        if($type == 0) {
            $rows .= '<option value="">'.$LNG['year'].'</option>';
            for($i = date('Y'); $i >= (date('Y') - 100); $i--) {
                if($i == $current) {
                    $selected = ' selected="selected"';
                } else {
                    $selected = '';
                }
                $rows .= '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
            }
        } elseif($type == 1) {
            $rows .= '<option value="">'.$LNG['month'].'</option>';
            for($i = 1; $i <= 12; $i++) {
                if($i == $current) {
                    $selected = ' selected="selected"';
                } else {
                    $selected = '';
                }
                $rows .= '<option value="'.$i.'"'.$selected.'>'.$LNG["month_$i"].'</option>';
            }
        } elseif($type == 2) {
            $rows .= '<option value="">'.$LNG['day'].'</option>';
            for($i = 1; $i <= 31; $i++) {
                if($i == $current) {
                    $selected = ' selected="selected"';
                } else {
                    $selected = '';
                }
                $rows .= '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
            }
        }
        return $rows;
    }
    function generateDate_reg($type) {
        global $LNG;
        $rows = '';
        if($type == 0) {
            $rows .= '<option value="">'.$LNG['year'].'</option>';
            for($i = date('Y'); $i >= (date('Y') - 100); $i--) {
                // if($i == $current) {
                //     $selected = ' selected="selected"';
                // } else {
                //     $selected = '';
                // }
                $rows .= '<option value="'.$i.'"'.$i.'>'.$i.'</option>';
            }
        } elseif($type == 1) {
            $rows .= '<option value="">'.$LNG['month'].'</option>';
            for($i = 1; $i <= 12; $i++) {
                // if($i == $current) {
                //     $selected = ' selected="selected"';
                // } else {
                //     $selected = '';
                // }
                $rows .= '<option value="'.$i.'"'.$i.'>'.$LNG["month_$i"].'</option>';
            }
        } elseif($type == 2) {
            $rows .= '<option value="">'.$LNG['day'].'</option>';
            for($i = 1; $i <= 31; $i++) {
                // if($i == $current) {
                //     $selected = ' selected="selected"';
                // } else {
                //     $selected = '';
                // }
                $rows .= '<option value="'.$i.'"'.$i.'>'.$i.'</option>';
            }
        }
        return $rows;
    }
    function generateStatsForm($type, $current, $min = null) {
        global $LNG;
        $rows = '';
        if($type == 0) {
            if(empty($min)) {
                $min = date('Y');
            }
            $rows .= '<option value="">'.$LNG['year'].'</option>';
            for($i = date('Y'); $i >= $min; $i--) {
                if($i == $current) {
                    $selected = ' selected="selected"';
                } else {
                    $selected = '';
                }
                $rows .= '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
            }
        } elseif($type == 1) {
            $rows .= '<option value="">'.$LNG['month'].'</option>';
            for($i = 1; $i <= 12; $i++) {
                if($i == $current) {
                    $selected = ' selected="selected"';
                } else {
                    $selected = '';
                }
                $rows .= '<option value="'.$i.'"'.$selected.'>'.$LNG["month_$i"].'</option>';
            }
        } elseif($type == 2) {
            $rows .= '<option value="">'.$LNG['day'].'</option>';
            for($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $_GET['month'], $_GET['year']); $i++) {
                if($i == $current) {
                    $selected = ' selected="selected"';
                } else {
                    $selected = '';
                }
                $rows .= '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
            }
        }
        return $rows;
    }
    function saniscape($value) {
        return htmlspecialchars(addslashes($value), ENT_QUOTES, 'UTF-8');
    }
       function generateOTP() {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        if(isset($_SESSION['otp'])){
            return $otp;
            }
            
        }

      function verifyOTP($otp) {
        if (isset($_SESSION['otp']) && $_SESSION['otp'] == $otp) {
            unset($_SESSION['otp']);
            return true;
        } else {
            return false;
        }
    }

       function message_id($type){
        if($type == 'customer_otp'){
            $sms_id = 155157;
        }
        if($type == 'customer_simple_otp'){
            $sms_id = 155158;
        }
        if($type == 'c_acc_created'){
            $sms_id = 156287;
        }
        if($type == 'milk_cleared'){
            $sms_id = 156286;
        }
        if($type == 'update_milk'){
            $sms_id = 156288;
        }
        if($type == 'verification_code'){
            $sms_id = 155593;
        }
        
        return $sms_id;
     }
     function load_state($current=null) {
        global $db;
        $query = 'SELECT * FROM `states`';
        $result = $db->query($query);
        $rows = '';

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                if($row['sid'] == $current) {
                    $selected = ' selected="selected"';
                } else {
                    $selected = '';
                }
                $rows .= '<option value="'.$row['sid'].'"'.$selected.'>'.$row['state_name'].'</option>';
            }
        }
        return $rows;
        mysqli_close($db);
    }
    
     
     // message template

     // find date
     function find_date($date_val){
        switch($date_val){
            case '0':
                $date = [date("Y-m-d")];
                break;
            case '1':
                $date = [date("Y-m-d", strtotime("-1 day"))];
                break;
            case '7':
                $date = [date("Y-m-d", strtotime("-6 days")), date("Y-m-d")];
                break;
            case '10':
                $date = [date("Y-m-d", strtotime("-9 days")), date("Y-m-d")];
                break;
            case '30':
                $date = [date("Y-m-d", strtotime("-29 days")), date("Y-m-d")];
                break;
            case 'this_week':
                $monday = strtotime("last Monday");
                $thisWeekStart = date("Y-m-d", $monday);
                $thisWeekEnd = date("Y-m-d");
                $date = [$thisWeekStart, $thisWeekEnd];
                break;
            case 'this_month':
                $thisMonthStart = date("Y-m-01");
                $thisMonthEnd = date("Y-m-d");
                $date = [$thisMonthStart, $thisMonthEnd];
                break;
            case 'last_month':
                $lastMonthStart = date("Y-m-01", strtotime("first day of previous month"));
                $lastMonthEnd = date("Y-m-t", strtotime("last day of previous month"));
                $date = [$lastMonthStart, $lastMonthEnd];
                break;
            default:
                $date = [date("Y-m-d")];
                break;
        }
        return $date;
    }
      // Format date
      function format_dates($dates) {
        $formatted_dates = array();
        
        foreach ($dates as $date) {
            $timestamp = strtotime($date);
            
            if ($timestamp === false) {
                $formatted_dates[] = 'Invalid date format: ' . $date;
            } else {
                if (strpos($date, ':') === false) {
                    $formatted_dates[] = date('d-m-Y', $timestamp);
                } else {
                    $formatted_dates[] = date('d-m-Y h:i A', $timestamp);
                }
            }
        }
        
        return $formatted_dates;
    }
    // return formated date if pass in offset like (+2 days, -2days)
    function str_datetime($currentDateTime, $offset){
                // Convert the current date and time to a DateTime object
                $currentDateTimeObj = new DateTime($currentDateTime);

                // Add or subtract the offset to the current date and time
                $currentDateTimeObj->modify($offset);

                // Format the resulting date and time to match the MySQL format
                $formattedDateTime = $currentDateTimeObj->format('Y-m-d H:i:s');

                // Return the formatted date and time
                return $formattedDateTime;
            }
    // format Phone Number

    function formatPhoneNumber($number) {
        $formattedNumber = substr($number, 0, 1) . str_repeat("*", 6) . substr($number, -3);
        return $formattedNumber;
      }
      // formating name
      function formatFullName($fname, $lname = null) {
        // Trim the first name
        $fname = trim($fname);
    
        // Check if last name is provided
        if ($lname !== null) {
            // Concatenate the first and last name with a space
            $name = $fname . ' ' . trim($lname);
        } else {
            // Last name is not provided, use only first name
            $name = $fname;
        }
    
        // Check if the name is longer than 15 characters
        if (strlen($name) > 15) {
            // Truncate the name to 15 characters and add ellipsis at the end
            $name = substr($name, 0, 15) . '...';
        }
    
        // Return the formatted name
        return $name;
    }
      function formatlocality($name) {
        // Check if the name is longer than 15 characters
        if (strlen($name) > 20) {
            // Truncate the name to 15 characters and add ellipsis at the end
            $name = substr($name, 0, 20) . '...';
        }
        // Return the formatted name
        return $name;
    }
    
      // get past date
      function getPastDate($numDays) {
        $pastDate = date('Y-m-d', strtotime('-'.$numDays.' days'));
        return $pastDate;
      }
            function convertDates($dates) {
            $convertedDates = array();
          
            foreach ($dates as $date) {
              $parts = explode('-', $date);
              if (count($parts) == 3) {
                if (strlen($parts[0]) == 2 && strlen($parts[1]) == 2 && strlen($parts[2]) == 4) {
                  $convertedDates[] = "$parts[2]-$parts[1]-$parts[0]";
                } else if (strlen($parts[0]) == 4 && strlen($parts[1]) == 2 && strlen($parts[2]) == 2) {
                  $convertedDates[] = $date;
                } else {
                  $convertedDates[] = null; // invalid date format
                }
              } else {
                $convertedDates[] = null; // invalid date format
              }
            }
          
            return $convertedDates;
          }
 
        function base64Image($string, $name) {
            $explode = explode(',', $string, 2);

            $image = imagecreatefromstring(base64_decode($explode[0]));
            
           
            // header('Content-Type: image/png');
            if(!$image) {
                
                return false;
            }else{
               
                // Store the image info
            $path = __DIR__ .'/../uploads/media/'.$name;
            imagepng($image, $path);

            $info = getimagesize($path);
            $filesize = filesize($path);
            // Delete the temporary image
            
            unlink($path);
        
            if($info[0] > 0 && $info[1] > 0 && $info['mime'] == 'image/png') {
                // Return the image data
                return array('size' => $filesize, 'data' => base64_decode($explode[0]));
            }
            }
        
            
            return false;
        }
                function info_urls() {
                    global $CONF, $db;
                
                    $pages = $db->query("SELECT `url`, `title` FROM `info_pages` WHERE `public` = 1 ORDER BY `id` ASC");
                
                    $output = '';
                    while($row = $pages->fetch_assoc()) {
                        
                        $output .= '<span><a href="'.permalink($CONF['url'].'/index.php?a=info&b='.$row['url']).'" rel="loadpage">'.skin::parse($row['title']).'</a></span>';
                    }
                
                    return $output;
                }

            function isAjax() {
                /*
                 * Check if the request is dynamic (ajax)
                 *
                 * @return bolean
                 */
            
                if(	isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
                    // || isset($_GET['live'])
                    ) {
                    return true;
                } else {
                    return false;
                }
            }
            function getLanguage($url, $ln = null, $type = null) {
                global $settings;
                // Type 1: Output the available languages
            
                // Define the languages folder
                    $lang_folder = __DIR__ .'/../languages/';

                // Open the languages folder
                if($handle = opendir($lang_folder)) {
                    // Read the files (this is the correct way of reading the folder)
                    while(false !== ($entry = readdir($handle))) {
                        // Excluse the . and .. paths and select only .php files
                        if($entry != '.' && $entry != '..' && substr($entry, -4, 4) == '.php') {
                            $name = pathinfo($entry);
                            $languages[] = $name['filename'];
                        }
                    }
                    closedir($handle);
                }
                // Sort the languages by name
                
                sort($languages);
                if($type == 1) {
                    // Add to array the available languages
                    $available = '';
                    foreach($languages as $lang) {
                        // The path to be parsed
                        $path = pathinfo($lang);
            
                        // Add the filename into $available array
                        $available .= '<span><a href="'.permalink($url.'/index.php?lang='.$path['filename']).'" rel="loadpage">'.ucfirst(mb_strtolower($path['filename'])).'</a></span>';
                    }
                    return $available;
                } else {
                    // If get is set, set the cookie and stuff
                    $lang = $settings['language']; // Default Language
                  if(isset($_GET['lang'])) {
                            if(in_array($_GET['lang'], $languages)) {
                                $lang = $_GET['lang'];
                                setcookie('lang', $lang, time() + (10 * 365 * 24 * 60 * 60), COOKIE_PATH); // Expire in one month
                            } else {
                                setcookie('lang', $lang, time() + (10 * 365 * 24 * 60 * 60), COOKIE_PATH); // Expire in one month
                            }
                        } elseif(isset($_COOKIE['lang'])) {
                            if(in_array($_COOKIE['lang'], $languages)) {
                                $lang = $_COOKIE['lang'];
                            }
                        } else {
                            setcookie('lang', $lang, time() + (10 * 365 * 24 * 60 * 60), COOKIE_PATH); // Expire in one month
                        }
            
                        // If the language file doens't exist, fall back to an existent language file
                        if(!file_exists($lang_folder.$lang.'.php')) {
                            $lang = $languages[0];
                        }
                    
            
                        // If the language file doens't exist, fall back to an existent language file
                        if(!file_exists($lang_folder.$lang.'.php')) {
                            $lang = $languages[0];
                        }
                    }
            
                    return $lang_folder.$lang.'.php';
                }
            

            function permalink($url){
                   global $settings;

                    if($settings['permalink']){
                    $path['login'] = 'index.php?a=login';
                    $path['register'] = 'index.php?a=register';
                    $path['home'] = 'index.php?a=home';
                    $path['add_user'] = 'index.php?a=add_user';
                    $path['manage_user'] = 'index.php?a=manage_user';
                    $path['calculate'] = 'index.php?a=calculate';
                    $path['list'] = 'index.php?a=list';

                    $path['settings'] = 'index.php?a=settings';
                    $path['info'] = 'index.php?a=info';
                    $path['lng'] = 'index.php?lang=';
                    $path['image'] = 'index.php?a=image';
                    
                    if (strpos($url, $path['login'])) {
                        $url = str_replace(array($path['login'], '&fp=', '$user'), array('login', '/', '/'), $url);
                    }elseif (strpos($url, $path['register'])) {
                        $url = str_replace(array($path['register'], '&b='), array('register', '/'), $url);
                    }elseif(strpos($url,$path['settings'])){
                        $url = str_replace(array($path['settings'], '&b='), array('settings', '/'), $url);
                    }elseif(strpos($url,$path['info'])){
                        $url = str_replace(array($path['info'], '&b='), array('info', '/'), $url);
                    } elseif (strpos($url, $path['lng'])) {
                        // Replace /lng/xyz with /lang=xyz
                        $url = str_replace(array($path['lng'], '?lang='), array('lng/'), $url);
                    } elseif(strpos($url,$path['home'])){
                        $url = str_replace(array($path['home'], '&b='), array('home', '/'), $url);
                    }elseif(strpos($url,$path['add_user'])){
                        $url = str_replace(array($path['add_user'], '&b='), array('add_user', '/'), $url);
                    }elseif(strpos($url,$path['manage_user'])){
                        $url = str_replace(array($path['manage_user'], '&b=','&c_id'), array('manage_user', '/','?c_id'), $url);
                    }elseif(strpos($url,$path['calculate'])){
                        $url = str_replace(array($path['calculate'], '&b='), array('calculate', '/'), $url);
                    }elseif(strpos($url,$path['list'])){
                        $url = str_replace(array($path['list'], '&b='), array('list', '/'), $url);
                    } elseif(strpos($url, $path['image'])) {
                        $url = str_replace(array($path['image'], '?t=', '&w=', '&h=', '&src='), array('image', '/', '/', '/', '/'), $url);
                    }
                }
                    return $url;
                
            }


            function GenrateToken()
            {
                $token_id = md5(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 10));
                return $token_id;
            }
            function GenerateToken_2($type = null) {
                if($type) {
                    return '<input type="hidden" name="token_id" value="'.$_SESSION['token_id'].'">';
                } else {
                    if(!isset($_SESSION['token_id'])) {
                        $token_id = md5(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10));
                        $_SESSION['token_id'] = $token_id;
                        return $_SESSION['token_id'];
                    }
                    return $_SESSION['token_id'];
                }
            }

?>