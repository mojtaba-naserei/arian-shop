<?php
require_once('../lib/connection.php'); //connect to DB
require_once('../lib/shop.php'); 
$shop = new Shop();
session_start();
if(isset($_POST['back'])){
    header("Location: manager.php");
    die();
}
//===========================================  check access
if(isset($_SESSION['userid']) or isset($_SESSION['managerid'])){
    $_SESSION['message'] = 'شما یک بار وارد شده اید';
    header("Location: ../index.php");
    die();
}
if(isset($_POST) && $_POST != null){
    $mobile = $_POST['username'];
    $password = md5($_POST['password']);
    $uniqid = uniqid();
    $sql = "SELECT * FROM users WHERE 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($row["user_mobile"] == $mobile && $row["user_pass"] == $password){
                $userID = $row["user_id"];
                $_SESSION['userid'] = $userID;
                $_SESSION['uniqid'] = $uniqid;
                unset($_SESSION['message']);
                header("Location: ../index.php");
                die();
            }
        }
        $_SESSION['message'] = 'نام کاربری یا کلمه عبور اشتباه است';
        header("Location: ../index.php");
    } 
    else {
        $_SESSION['message'] = 'نام کاربری یا کلمه عبور اشتباه است';
        header("Location: ../index.php");
    }
  $conn->close();
}
else {
    $_SESSION['message'] = 'نام کاربری یا کلمه عبور اشتباه است';
    header("Location: ../index.php");
} 
?>
