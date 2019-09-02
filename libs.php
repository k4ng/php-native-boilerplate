<?php
    include 'config.php';
    
    function db($params = array())
    {
        if (isset($params['hostname'])) {
            $hostname = $params['hostname'];
        } else {
            $hostname = CONF_HOSTNAME;
        }

        if (isset($params['username'])) {
            $username = $params['username'];
        } else {
            $username = CONF_USERNAME;
        }

        if (isset($params['password'])) {
            $password = $params['password'];
        } else {
            $password = CONF_PASSWORD;
        }

        if (isset($params['database'])) {
            $database = $params['database'];
        } else {
            $database = CONF_DATABASE;
        }

        $conn = new mysqli($hostname, $username, $password, $database);

        mysqli_set_charset($conn,"utf8");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }

    function Q_array($sql = null)
    {
        $db = db();
        
        if ($sql === null) {
            return null;
        } else {
            if ($result = $db->query($sql)) 
            {
                return $result->fetch_all(MYSQLI_ASSOC);

                $result->free();
            }

            /* close connection */
            $db->close();
        }
    }

    function Q_execute($sql = null)
    {
        $db = db();
        
        if ($sql === null) {
            return null;
        } else {
            if ($result = $db->query($sql)) 
            {
                return $result;

                $result->free();
            }

            /* close connection */
            $db->close();
        }
    }

    function Q_count($sql = null)
    {
        $db = db();
        
        if ($sql === null) {
            return null;
        } else {
            if ($result = $db->query($sql)) 
            {
                return $result->num_rows;

                $result->free();
            }

            /* close connection */
            $db->close();
        }
    }

    function Q_mres($param = null){
        $db = db();

        if ($param === null) {
            return null;
        } else {
            return mysqli_real_escape_string($db, $param); 
        }
    }

    function redirect_to($page=null, $time=0.1)
    {
        if($page !== null){
            echo "<meta http-equiv='refresh' content='". $time ."; url=". $page ."'>";
        }
    }

    function site_url($slash=false)
    {
        $dir_project = CONF_DIR_PROJECT;
        $http_host = $_SERVER['HTTP_HOST'];
        $https_check = (!empty($_SERVER['HTTPS']) ? 'https' : 'http');

        if($slash){
            $siteurl =  $https_check . '://' . $http_host . '/' .$dir_project.'/';
        } else {
            $siteurl =  $https_check . '://' . $http_host . '/' .$dir_project;
        }

        return $siteurl;
    }

    function your_position(){
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        return $actual_link;
    }

    function session_me(){
        if(isset($_SESSION['login'])){
            if($_SESSION['login']){
                return true;
            }
        }

        return false;
    }

    function send_mail($p=array())
    {

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 2;                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'forsendmail4@gmail.com';                     // SMTP username
            $mail->Password   = '@123passwordnya@';                               // SMTP password
            $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom($p['from_mail'], $p['from_name']);
            $mail->addAddress($p['to_mail'], $p['to_name']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $p['subject'];
            $mail->Body    = $p['body'];

            $mail->send();

            return true;
            // echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        return false;
    }

    function curl_get($url, $array=true){
        $ch = curl_init($url);                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                                                                    
        $result = curl_exec($ch);
        curl_close($ch);

        if($array){ return json_decode($result); } else { return $result; }
    }
