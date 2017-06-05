<?php
require_once('../lib/connection.php'); //connect to DB
require_once('../lib/jdf.php'); //for shamsi time
require_once('../lib/shop.php'); 
$shop = new Shop();
session_start();
//===========================================  check access
if(isset($_SESSION['userid'])) $userId=  $_SESSION['userid']; else $userId= null;
if(isset($_SESSION['managerid'])) $managerId=  $_SESSION['managerid']; else $managerId= null;
if($shop->checkAccess(1,$userId,$managerId,$conn) != 'admin'){
    $_SESSION['message'] = 'شما به این صفحه دسترسی ندارید';
    header("Location: ../index.php");
    die();
}
//===========================================  check access
//===================================== update
if(isset($_POST) && $_POST != null){
    if(isset($_POST['transporter_id'])){
        $transporter_id = $_POST['transporter_id'];
        $transporter_name = $_POST['transporter_name'];
        $transporter_mobile = $_POST['transporter_mobile'];
        $sql = "UPDATE transporter SET transporter_name='$transporter_name',transporter_mobile='$transporter_mobile'  WHERE transporter_id='$transporter_id'";

        if ($conn->query($sql) === TRUE) {
            echo "پیک با موفقیت بروزرسانی شد";
        } else {
            echo "خطا در بروز رسانی: " . $conn->error;
        }
    }
    //======================================== delete
    else if(isset($_POST['del'])){
        $transporter_id =  $_POST['del'];
        $sql = "DELETE FROM transporter WHERE transporter_id='$transporter_id'";
        if ($conn->query($sql) === TRUE) {
          echo "پیک حذف شد";
        } else {
         echo "خطا در حذف: " . $conn->error;
        }
    }
    else {
        //================================================ add 
        $transporter_name = $_POST['transporter_name'];
        $transporter_mobile = $_POST['transporter_mobile'];

        //============================================ check exsit
        $sql = "SELECT * FROM transporter WHERE transporter_mobile = $transporter_mobile";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo ' این پیک وجود دارد . مجددا امتحان کنید';
        } 
        else {
            if(isset($transporter_name) && isset($transporter_mobile)){
                $sql = "INSERT INTO transporter (transporter_name, transporter_mobile)
                VALUES ('$transporter_name','$transporter_mobile')";

                if ($conn->query($sql) === TRUE) {
                    echo "پیک با موفقیت اضافه شد";
                } 
                else {
                    echo "خطا: " . $sql . "<br>" . $conn->error;
                }
            }
        }
    }
}



$sql = "SELECT * FROM transporter WHERE 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        echo '<table border="1">';
        echo '<tr><td>شناسه پیک</td><td>نام و نام خانوادگی</td><td>موبایل</td><td>َعملیات</td> </tr>';
        while($row = $result->fetch_assoc()) {
                echo '<form action="" method="post">';
                echo '<tr><td>'.$row["transporter_id"].'</td>';
                echo '<td><input type="text" name="transporter_name" value="'.$row["transporter_name"].'"></td>';
                echo '<td><input type="text" name="transporter_mobile" value="'.$row["transporter_mobile"].'"></td>';
                echo '
                <td>  
                <input type="submit" value="بروز رسانی">
                <input type="hidden" name="transporter_id" value="'.$row["transporter_id"].'">  
                ';
                echo '</form>';
                echo '<form action="" method="post">
                 
                <input type="submit" value="حذف">
                <input type="hidden" name="del" value="'.$row["transporter_id"].'">  
                </td>
                </tr>';
                echo '</form>';
        }
         echo '</table>';
    } 
    else {
        echo 'هیچ پیکی وجود ندارد';
    }
?>
<br>
<h2>اضافه کردن پیک</h2>
<div> 
<form action="" method="post">
  <div class="container">
    <label><b>نام و نام خانوادگی</b></label>
    <input type="text" placeholder="نام پیک را وارد کنید" name="transporter_name" required>
<br>
    <label><b>شماره همراه</b></label>
    <input type="text" placeholder="موبایل پیک را وارد کنید" name="transporter_mobile" required>
<br>
    <div class="clearfix">
      <button type="submit" class="signupbtn">ثبت نام</button>
    </div>
  </div>
</form> 

</div>

<button onclick="window.history.back()">برگشت </button>