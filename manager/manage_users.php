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
    $user_id = $_POST['user_id'];
    $user_name_family = $_POST['user_name_family'];
    $user_mobile = $_POST['user_mobile'];
    $user_address = $_POST['user_address'];
    $sql = "UPDATE users SET user_name_family='$user_name_family',user_mobile='$user_mobile',user_address='$user_address'  WHERE user_id='$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "کاربر با موفقیت بروزرسانی شد";
    } else {
        echo "خطا در بروز رسانی: " . $conn->error;
    }

}
//===================================== update


$sql = "SELECT * FROM users WHERE 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        echo '<table border="1">';
        echo '<tr><td>شناسه کاربر</td><td>نام و نام خانوادگی</td><td>موبایل</td><td>ویرایش</td> </tr>';
        while($row = $result->fetch_assoc()) {
            if($row["user_id"] != 1){
                echo '<form action="" method="post">';
                echo '<tr><td>'.$row["user_id"].'</td>';
                echo '<td><input type="text" name="user_name_family" value="'.$row["user_name_family"].'"></td>';
                echo '<td><input type="text" name="user_mobile" value="'.$row["user_mobile"].'"></td>';
                echo '<td><textarea rows="5" cols="20"  name="user_address">'.$row["user_address"].'</textarea></td>';
                echo '
                <td>  
                <input type="submit" value="بروز رسانی">
                <input type="hidden" name="user_id" value="'.$row["user_id"].'">  
                </td>
                </tr>';
                echo '</form>';
            }
            
        }
         echo '</table>';
    } 
    else {
        echo 'هیچ کاربری وجود ندارد';
    }
  $conn->close(); 

?>
<br>
<h2>اضافه کردن کاربر</h2>
<iframe height="250"  width="500" src="../registers/user_register.php"></iframe> <!-- add users -->

<button onclick="window.history.back()">برگشت </button>