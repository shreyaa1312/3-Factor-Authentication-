<?php
session_start();
$otp = $_SESSION['otp'];
$enteredotp = $_GET['otp'];
if ($enteredotp == $otp) {
    $url = "./face.html";
    header('Location: ' . $url);
} else {
    echo '<script>alert("Incorrect OTP Enter Again")</script>';
}