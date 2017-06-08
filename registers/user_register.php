<?php
require_once('../lib/connection.php'); //connect to DB
require_once('../lib/shop.php'); 
$shop = new Shop();
session_start();
//===========================================  check access
if(isset($_SESSION['userid']) or isset($_SESSION['managerid'])){
    $_SESSION['message'] = 'قبل از ساختن اکانت جدید باید از اکانت جاری خارج شوید';
    header("Location: ../index.php");
    die();
}
//=====================================================
if(isset($_POST['back'])){
    header("Location: ../index.php");
    die();
}

if(isset($_POST) && $_POST != null){
    $user_name_family = $_POST['user_name_family'];
    $user_mobile = $_POST['user_mobile'];
    $user_address = $_POST['user_address'];
    $user_pass = md5($_POST['user_pass']);

//============================================ check exsit
    $sql = "SELECT * FROM users WHERE user_mobile = $user_mobile";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo ' این کاربر وجود دارد . مجددا امتحان کنید';
    } 
    else {
        if(isset($user_name_family) && isset($user_mobile) && isset($user_address) && isset($user_pass)){
            $sql = "INSERT INTO users (user_name_family, user_mobile,user_address, user_pass)
            VALUES ('$user_name_family','$user_mobile','$user_address', '$user_pass')";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "ثبت نام با موفقیت انجام شد";
                header("Location: ../index.php");
                die();
            } 
            else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
//============================================ store
  $conn->close(); 
}



?> 
<form action="" method="post">
  <div class="container">
    <label><b>نام و نام خانوادگی</b></label>
    <input type="text" placeholder="نام خود را وارد کنید" name="user_name_family" required>
<br>
    <label><b>شماره همراه</b></label>
    <input type="text" placeholder="موبایل را وارد کنید" name="user_mobile" required>
<br>
    <label><b>کلمه عبور</b></label>
    <input type="text" placeholder="کلمه عبور را وارد کنید" name="user_pass" required>
<br>
    <label><b> آدرس </b></label>
    <textarea rows="5" cols="20" placeholder="آدرس خود را وارد کنید" name="user_address"></textarea>
<br>
    <div class="clearfix">
      <button type="submit" class="signupbtn">ثبت نام</button>
    </div>
  </div>
</form> 

<form method="post"><input type="hidden" name="back" value="1"><input type="submit" value="برگشت"></form>