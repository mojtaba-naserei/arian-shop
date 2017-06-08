<?php
require_once('../lib/connection.php'); //connect to DB
require_once('../lib/jdf.php'); //for shamsi time
require_once('../lib/shop.php'); 
$shop = new Shop();
session_start();
if(isset($_POST['back'])){
    header("Location: manager.php");
    die();
}
//===========================================  check access
if(isset($_SESSION['userid'])) $userId=  $_SESSION['userid']; else $userId= null;
if(isset($_SESSION['managerid'])) $managerId=  $_SESSION['managerid']; else $managerId= null;
if($shop->checkAccess(1,$userId,$managerId,$conn) != 'admin' or $shop->checkAccess(1,$userId,$managerId,$conn) == null){
    $_SESSION['message'] = 'شما به این صفحه دسترسی ندارید';
    header("Location: ../index.php");
    die();
}
//===========================================  check access
//===================================== update
if(isset($_POST) && $_POST != null){
    if(isset($_POST['id'])){
        $id = $_POST['id'];
        $order_id = $_POST['order_id'];
        $tracking_code = $_POST['tracking_code'];
        $pay_id = $_POST['pay_id'];
        $time = explode('/',$_POST['time']); 
	    $newTime = strtotime(jalali_to_gregorian($time[0],$time[1],$time[2],'-'));
        $card_number = str_replace('-','',$_POST['card_number']);
        $port_name = $_POST['port_name'];
        $price = str_replace(',','',$_POST['price']);
        $status = $_POST['status'];
        $sql = "UPDATE payments 
        SET 
        order_id='$order_id',
        tracking_code='$tracking_code',
        pay_id='$pay_id',
        card_number='$card_number',
        port_name='$port_name',
        price='$price',
        status='$status',
        time='$newTime'

        WHERE id='$id'";

        if ($conn->query($sql) === TRUE) {
            echo "پرداختی با موفقیت بروزرسانی شد";
        } else {
            echo "خطا در بروز رسانی: " . $conn->error;
        }
    }
    else if(isset($_POST['del'])) {
         //================================================ add 
        $id = $_POST['del'];
        $sql = "DELETE FROM payments WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
             echo "پرداختی حذف شد";
        } else {
             echo "خطا در حذف: " . $conn->error;
        }
    }
}
//===================================== update

$sql = "SELECT * FROM payments WHERE 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        echo '<table border="1">';
        echo '<tr>
        <td>شناسه </td>
        <td>شماره سفارش</td>
        <td>کد پیگیری</td>
        <td>شناسه پرداخت</td> 
        <td>زمان پرداخت</td>
        <td>شماره کارت</td>
        <td>نام درگاه</td>
        <td>مبلغ پرداختی(ریال)</td>
        <td>وضعیت پرداختی</td>
        <td>عملیات</td></tr>';
        while($row = $result->fetch_assoc()) {
                echo '<form action="" method="post">';
                echo '<tr><td>'.$row["id"].'</td>';
                echo '<td><input type="text" name="order_id" value="'.$row["order_id"].'"></td>';
                echo '<td><input type="text" name="tracking_code" value="'.$row["tracking_code"].'"></td>';
                echo '<td><input type="text" name="pay_id" value="'.$row["pay_id"].'"></td>';
                echo '<td><input type="text" name="time" value="'.jdate("o/m/j",$row["time"]).'"></td>';
                echo '<td><input type="text" name="card_number" value="'.implode("-",str_split($row["card_number"],4)).'"></td>';
                 echo '<td>
                        <select name="port_name" required>';
                            if ($row["port_name"] == 'parsian') echo ' <option value="parsian" selected>پارسیان</option><option value="mellat">ملت</option><option value="saman">سامان</option>';
                            if ($row["port_name"] == 'mellat') echo ' <option value="mellat" selected>ملت</option><option value="parsian">پارسیان</option><option value="saman">سامان</option>';
                            if ($row["port_name"] == 'saman') echo ' <option value="saman" selected>سامان</option><option value="mellat">ملت</option><option value="parsian">پارسیان</option>';
                            
                echo '</select>
                    </td>';
                echo '<td><input type="text" name="price" value="'.number_format($row["price"]).'"></td>';
                echo '<td>
                        <select name="status" required>';
                            if ($row["status"] == 1) echo ' <option value="1" selected>پرداخت</option><option value="2">کنسل</option>';
                            if ($row["status"] == 2) echo ' <option value="2" selected>کنسل</option><option value="1">پرداخت</option>';  
                echo '</select>
                    </td>';
                echo '
                <td>  
                <input type="submit" value="بروز رسانی">
                <input type="hidden" name="id" value="'.$row["id"].'">  
                </form>
                <form action="" method="post">
                <input type="submit" value="حذف">
                <input type="hidden" name="del" value="'.$row["id"].'">  
                </tr>';
                echo '</form>';
        }
         echo '</table>';
    } 
    else {
        echo 'هیچ پرداختی وجود ندارد';
    }
  $conn->close(); 

?>

<form method="post"><input type="hidden" name="back" value="1"><input type="submit" value="برگشت"></form>