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
    $restaurant_id = $_POST['restaurant_id'];
    $restaurant_type = $_POST['restaurant_type'];
    $restaurant_name = $_POST['restaurant_name'];
    $restaurant_city = $_POST['restaurant_city'];
    $restaurant_tel = $_POST['restaurant_tel'];
    $restaurant_address = $_POST['restaurant_address'];
    $manager_name = $_POST['manager_name'];
    $manager_mobile = $_POST['manager_mobile'];
    $sql = "UPDATE restaurant_managers SET 
        restaurant_type='$restaurant_type',
        restaurant_name='$restaurant_name',  
        restaurant_city='$restaurant_city', 
        restaurant_tel='$restaurant_tel', 
        restaurant_address='$restaurant_address', 
        manager_name='$manager_name',
        manager_mobile='$manager_mobile'  
   
    WHERE restaurant_id='$restaurant_id'";

    if ($conn->query($sql) === TRUE) {
        echo "فروشگاه با موفقیت بروزرسانی شد";
    } else {
        echo "خطا در بروز رسانی: " . $conn->error;
    }

}
//===================================== update


$sql = "SELECT * FROM restaurant_managers WHERE 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table border="1">';
        echo '<tr><td> شناسه فروشگاه</td><td>نوع فروشگاه</td><td>نام فروشگاه</td><td>شهر</td><td>تلفن</td><td>آدرس</td><td>نام مدیر</td><td>موبایل مدیر</td><td> ویرایش</td> </tr>';
        while($row = $result->fetch_assoc()) {
                echo '<form action="" method="post">';
                echo '<tr><td>'.$row["restaurant_id"].'</td>';
                echo '<td>
                    <label><b>نوع فروشگاه</b></label>
                        <select name="restaurant_type" required>';
                            if ($row["restaurant_type"] == 1) echo ' <option value="1" selected>رستوران</option><option value="2">فست فود</option>';
                            if ($row["restaurant_type"] == 2) echo ' <option value="2" selected>فست فود</option><option value="1">رستوران</option>';  
                     echo '</select>
                    </td>';
                echo '<td><input type="text" name="restaurant_name" value="'.$row["restaurant_name"].'"></td>';
                echo '<td><input type="text" name="restaurant_city" value="'.$row["restaurant_city"].'"></td>';
                echo '<td><input type="text" name="restaurant_tel" value="'.$row["restaurant_tel"].'"></td>';
                echo '<td><textarea rows="5" cols="20"  name="restaurant_address">'.$row["restaurant_address"].'</textarea></td>';
                echo '<td><input type="text" name="manager_name" value="'.$row["manager_name"].'"></td>';
                echo '<td><input type="text" name="manager_mobile" value="'.$row["manager_mobile"].'"></td>';
                echo '
                <td>  
                <input type="submit" value="بروز رسانی">
                <input type="hidden" name="restaurant_id" value="'.$row["restaurant_id"].'">  
                </td>
                </tr>';
                echo '</form>';
        }
         echo '</table>';
    } 
    else {
       echo 'هیچ فروشگاهی وجود ندارد';
    }
  $conn->close(); 

?>
<br>
<h2>اضافه کردن فروشگاه</h2>
<iframe height="450"  width="400" src="../registers/manager_register.php"></iframe> <!-- add users -->


<button onclick="window.history.back()">برگشت </button>