<?php

/*
 * VALIDATION logic
 */

define("MIN_USERNAME_LENGTH", 4);
define("MIN_PASSWORD_LENGTH", 8);

function validateName($name) {
 if (strlen($name) < MIN_USERNAME_LENGTH) {
   $nameErr = "Username should be at least " . MIN_USERNAME_LENGTH . " characters long.";
 } else if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
   $nameErr = "Only letters and white space allowed.";
 } else if (!nameIsAvailable($name)) {
   $nameErr = "Such username already exists.";
 } else {
   return true;
 }

 $_SESSION['Error'] = $nameErr;
}

function validatePassword($pwd) {
 if (strlen($pwd) < MIN_PASSWORD_LENGTH) {
   $pwdErr = "Password should be at least " . MIN_PASSWORD_LENGTH . " characters long.";
 } else {
   return true;
 }

 $_SESSION['Error'] = $pwdErr;
}

function nameIsAvailable($name) {
  $conn = db();
  $sql = "SELECT username FROM users WHERE username='{$name}';";
  $result =  mysqli_fetch_row(mysqli_query($conn, $sql))[0];
  if (is_string($result)) {
    return false;
  } else {
    return true;
  }
}