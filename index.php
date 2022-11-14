<?php

$email = filter_input(INPUT_POST, 'email');
$password = filter_input(INPUT_POST, 'password');
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
		if ($num_rows == 0) {
			echo "Email Does not Exists";
			die();
		} else {
			while ($query = mysqli_fetch_array($res)) {
				if ($password == $query["Password"]) {
					echo "Password Success";
					$mobile = $query["PhNo"];
				} else {
					echo "Incorrect Password";
				}
			}
		}
	}

	$conn->close();
	$otp = rand(100000, 999999);
	$phone = "+91" . $mobile; // target number; includes ISD
	echo $otp . " m:" . $phone;
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
	$_SESSION['fun'] = 2;

	$url = "./otp.html";
	header('Location: ' . $url);
}