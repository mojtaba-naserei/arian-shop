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
    $restaurant_type = $_POST['restaurant_type'];
    $restaurant_name = $_POST['restaurant_name'];
    $restaurant_city = $_POST['restaurant_city'];
    $restaurant_tel = $_POST['restaurant_tel'];
    $restaurant_address = $_POST['restaurant_address'];
    $manager_name = $_POST['manager_name'];
    $manager_mobile = $_POST['manager_mobile'];
    $manager_pass = md5($_POST['manager_pass']);

//============================================ check exsit
    $sql = "SELECT * FROM restaurant_managers WHERE manager_mobile = $manager_mobile";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo ' این فروشگاه وجود دارد . مجددا امتحان کنید';
    } 
    else {
        if(
            isset($restaurant_type) && 
            isset($restaurant_name) && 
            isset($restaurant_city) && 
            isset($restaurant_tel) && 
            isset($restaurant_address) && 
            isset($manager_name) && 
            isset($manager_mobile) && 
            isset($manager_pass)
            ){
            $sql = "INSERT INTO restaurant_managers (
                    restaurant_type,restaurant_name,restaurant_city,restaurant_tel,restaurant_address,
                    manager_name,manager_mobile,manager_pass
                )
            VALUES (
            '$restaurant_type','$restaurant_name','$restaurant_city','$restaurant_tel','$restaurant_address',
                    '$manager_name','$manager_mobile','$manager_pass'
            )";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "ثبت فروشگاه با موفقیت انجام شد";
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
<input type="text" placeholder="نام خود را وارد کنید" name="manager_name" required>
<br>
<label><b>شماره همراه</b></label>
<input type="text" placeholder="موبایل را وارد کنید" name="manager_mobile" required>
<br>
<label><b>کلمه عبور</b></label>
<input type="text" placeholder="کلمه عبور را وارد کنید" name="manager_pass" required>
<br>
<label><b>نام فروشگاه</b></label>
<input type="text" placeholder="نام فروشگاه خود را وارد کنید" name="restaurant_name" required>
<br>   
<label><b>نوع فروشگاه</b></label>
 <select name="restaurant_type" required>
  <option value="1">رستوران</option>
  <option value="2">فست فود</option>
</select> 
<br>   
<label><b>شهر</b></label>
<input type="text" placeholder="نام شهری که فروشگاه در آن قرار دارد را وارد کنید" name="restaurant_city" required>
<br>  
<label><b>تلفن</b></label>
<input type="text" placeholder="شماره تلفن فروشگاه را وارد کنید" name="restaurant_tel" required>
<br>   
<label><b>آدرس</b></label>
<textarea  placeholder="آدرس فروشگاه را وارد کنید" name="restaurant_address" cols="40" rows="10" required></textarea>

<br>
    <div class="clearfix">
      <button type="submit" class="signupbtn">ثبت </button>
    </div>
  </div>
</form> 

<form method="post"><input type="hidden" name="back" value="1"><input type="submit" value="برگشت"></form>