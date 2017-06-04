<?php
require_once('../lib/connection.php'); //connect to DB
require_once('../lib/jdf.php'); //for shamsi time
require_once('../lib/shop.php');
$shop = new shop();
//===================================== update
if(isset($_POST) && $_POST != null){
    if(isset($_POST['id'])){
        $order_id = $_POST['id'];
        $status = $_POST['status'];
        $sql1= "UPDATE payments SET status='$status' WHERE order_id='$order_id'";
        $conn->query($sql1);

        $peyk_code = $_POST['peyk_code'];
        $customer_id = $_POST['customer_id'];
        $total_amount = str_replace(',','',$_POST['total_amount']);
        $time = explode('/',$_POST['edit_time']); 
	    $newTime = strtotime(jalali_to_gregorian($time[0],$time[1],$time[2],'-'));
        $sql = "UPDATE orders 
        SET 
        peyk_code='$peyk_code',
        customer_id='$customer_id',
        total_amount='$total_amount',
        edit_time='$newTime'

        WHERE order_id='$order_id'";

        if ($conn->query($sql) === TRUE) {
            echo "سفارش با موفقیت بروزرسانی شد";
        } else {
            echo "خطا در بروز رسانی: " . $conn->error;
        }
    }
    else if(isset($_POST['del'])) {
         //================================================ delete 
        $order_id = $_POST['del'];
        $sql1 = "DELETE FROM orders WHERE order_id='$order_id'";
        $sql2 = "DELETE FROM products_order WHERE order_id='$order_id'";
        $sql3 = "DELETE FROM payments WHERE order_id='$order_id'";
        if ($conn->query($sql1) === TRUE &&  $conn->query($sql2) === TRUE && $conn->query($sql3) === TRUE) {
             echo "سفارش حذف شد";
        } else {
             echo "خطا در حذف: " . $conn->error;
        }
    }
}
//===================================== update

    $sql = " SELECT orders.*,payments.status FROM orders LEFT JOIN payments ON orders.order_id = payments.order_id ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
       
        echo '<table border="1">';
        echo '<tr>
        <td>شماره سفارش</td>
        <td>وضعیت سفارش </td>
        <td>کد پیک</td>
        <td>نام مشتری</td>
        <td>مبلغ کل</td>
        <td>تاریخ ایجاد</td>
        <td>تاریخ ویرایش</td>
        <td>عملیات</td>
        </tr>';
        while($row = $result->fetch_assoc()) {
                echo '<form action="" method="post">';
                echo '<td>'.$row["order_id"].'</td>';
                echo '<td>
                        <select name="status" required>';
                            if ($row["status"] == 1) echo ' <option value="1" selected>پرداخت</option><option value="2">کنسل</option>';
                            if ($row["status"] == 2) echo ' <option value="2" selected>کنسل</option><option value="1">پرداخت</option>';  
                echo '</select>
                    </td>';
                echo '<td><select name="peyk_code" required>';
                for($i=0;$i<count($shop->getPeyk(null,$conn));$i++){
                    if($row["peyk_code"] == $shop->getPeyk(null,$conn)[$i]['transporter_id'])
                     echo '<option value="'.$shop->getPeyk(null,$conn)[$i]['transporter_id'].'" selected>'.$shop->getPeyk(null,$conn)[$i]['transporter_id'].'</option>';    
                    else 
                     echo '<option value="'.$shop->getPeyk(null,$conn)[$i]['transporter_id'].'" >'.$shop->getPeyk(null,$conn)[$i]['transporter_id'].'</option>';    
                }
                echo '</select></td>';
                echo '<td><select name="customer_id" required>';
                for($i=0;$i<count($shop->getUsers(null,$conn));$i++){
                    if($row["customer_id"] == $shop->getUsers(null,$conn)[$i]['user_id'])
                     echo '<option value="'.$shop->getUsers(null,$conn)[$i]['user_id'].'" selected>'.$shop->getUsers(null,$conn)[$i]['user_name_family'].'</option>';    
                    else 
                     echo '<option value="'.$shop->getUsers(null,$conn)[$i]['user_id'].'" >'.$shop->getUsers(null,$conn)[$i]['user_name_family'].'</option>';    
                }
                echo '</select></td>';
                echo '<td><input type="text" name="total_amount" value="'.number_format($row["total_amount"]).'"></td>';
                echo '<td>'.jdate("o/m/j",$row["create_time"]).'</td>';
                echo '<td><input type="text" name="edit_time" value="'.jdate("o/m/j",$row["edit_time"]).'"></td>';
                echo '
                <td>  
                <input type="submit" value="بروز رسانی">
                <input type="hidden" name="id" value="'.$row["order_id"].'">  
                </form>
                    <form action="" method="post">
                    <input type="submit" value="حذف">
                    <input type="hidden" name="del" value="'.$row["order_id"].'">  
                </form>
                </form>
                    <form action="manage_order.php" method="post">
                    <input type="submit" value="نمایش فاکتور">
                    <input type="hidden" name="show" value="'.$row["order_id"].'">  
                    </tr>
                </form>';
        }

         echo '</table>';
    } 
    else {
        echo 'هیچ سفارشی وجود ندارد';
    }
  $conn->close(); 

?>