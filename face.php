<?php

if (isset($_POST["p1"])) {
    session_start();
    $email = $_SESSION['email'];
    $fun = $_SESSION['fun'];
    $image = $_POST['p1'];
    $output =  shell_exec("python3 ./facerec.py '" . $email . "' '" . $image . "' '" . $fun . "' ");
    echo $output;
}