<?php
$username = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');
$email = filter_input(INPUT_POST, 'email');
$mobile = filter_input(INPUT_POST, 'phonenumber');

if (!empty($username)) {
    if (!empty($password)) {
        if (!empty($email)) {
            $host = "localhost";
            $dbusername = "root";
            $dbpassword = "";
            $dbname = "isaa_project";

            $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
            if (mysqli_connect_error()) {
                die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
            } else {
                if ($email != "") {
                    $res = mysqli_query($conn, "SELECT * from `user_details` where Email='" . $email . "'");
                    $num_rows = mysqli_num_rows($res);
                    if ($num_rows > 0) {
                        echo '<script>alert("Email Exists")</script>';
                        die();
                    }
                }
                if ($mobile != "") {
                    $res = mysqli_query($conn, "SELECT * FROM `user_details` where PhNo='" . $mobile . "'");
                    $num_rows = mysqli_num_rows($res);
                    if ($num_rows > 0) {
                        echo '<script>alert("Mobile Number already Exists")</script>';
                        die();
                    }
                }
                $sql = "INSERT INTO `user_details` VALUES ('" . $username . "', '" . $email . "', '" . $mobile . "', '" . $password . "')";
                if ($conn->query($sql) == TRUE) {
                    echo '<script>alert("Your account has been created successfully.")</script>';
                    "New record is inserted sucessfully";
                    $otp = rand(100000, 999999);
                    $phone = "+91" . $mobile; // target number; includes ISD


                    $api_key = '65c060f0-4f05-11ed-9c12-0200cd936042'; // API Key
                    $req = "https://2factor.in/API/V1/" . $api_key . "/SMS/" . $phone . "/" . $otp;

                    $sms = file_get_contents($req);
                    echo $sms;
                    $sms_status = json_decode($sms, true);
                    if ($sms_status['Status'] !== 'Success') {
                        $err['error'] = 'Could not send OTP to ' . $phone;
                    }
                    sleep(6);
                    session_start();
                    $_SESSION['otp'] = $otp;
                    $_SESSION['email'] = $email;
                    $_SESSION['fun'] = 1;

                    $url = "./otp.html";
                    header('Location: ' . $url);
                } else {
                    echo "Error: " . $sql . " " . $conn->error;
                }
                $conn->close();
            }
        } else {
            echo '<script>alert("Email should not be empty")</script>';
            die();
        }
    } else {
        echo '<script>alert("Password should not be empty")</script>';
        die();
    }
} else {
    echo '<script>alert("Username should not be empty")</script>';
    die();
}