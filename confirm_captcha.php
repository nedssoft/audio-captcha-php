<?php
session_start();
$status = '';

if (isset($_POST['captcha'])) {
    
    if(!isset($_POST['_token']) || $_POST['_token'] !== $_SESSION['CSRF_Token']) {
        die('This page has expired');
    }
    // Validation: Checking entered captcha code with the generated captcha code

    // trim the captcha input
    $captcha = trim($_POST['captcha']);
    // Sanitize and strip accent
    $captcha = iconv('UTF-8', 'ASCII//TRANSLIT', $captcha);
    
    if (strcmp($_SESSION['captcha'], $captcha) != 0) {
        // Note: the captcha code is compared case insensitively.
        // if you want case sensitive match, update the check above to strcmp()
        $status = "<p class='status' style='color:#FFFFFF; font-size:20px'><span style='background-color:#FF0000;'>Wrong captcha!!, try again</span></p>";
    } else {
        $status = "<p class='status' style='color:#FFFFFF; font-size:20px'><span style='background-color:#46ab4a;'>Correct captcha!!</span></p>";
    }
}
?>