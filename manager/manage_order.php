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
if(isset($_POST['show'])){
    $order_id = $_POST['show'];
    $_SESSION["order_id"] = $order_id;
}
else if(isset($_SESSION["order_id"])){
    $order_id = $_SESSION["order_id"];
}
//===================================== update
if(isset($_POST) && $_POST != null){
    if(isset($_POST['id'])){
        $record_id = $_POST['id'];
        $food_code = $_POST['food_code'];
        $order_number = $_POST['order_number'];
        // $restaurant_id = $_POST['restaurant_id'];
        
        // $sql1= "UPDATE payments SET restaurant_id='$restaurant_id' WHERE order_id='$order_id'";
        // $conn->query($sql1);

        $sql = "UPDATE products_order SET food_code='$food_code',order_number='$order_number' WHERE id='$record_id'";

        if ($conn->query($sql) === TRUE) {
            echo "غذا با موفقیت بروزرسانی شد";
        } else {
            echo "خطا در بروز رسانی: " . $conn->error;
        }
    }
    else if(isset($_POST['del'])) {
         //================================================ delete 
        $record_id = $_POST['del'];
        $sql = "DELETE FROM products_order WHERE id='$record_id'";
        if ($conn->query($sql) === TRUE) {
             echo "غذا حذف شد";
        } else {
             echo "خطا در حذف: " . $conn->error;
        }
    }
}
//===================================== update
$sql = " SELECT products_order.* FROM products_order WHERE products_order.order_id = $order_id";
 $result = $conn->query($sql);

 
    if ($result->num_rows > 0) {
      
        echo '<table border="1">';
        echo '<tr>
        <td>کد غذا</td>
        <td>تعداد غذا</td>
        <td>نام رستوران</td>
        
        <td>عملیات</td>
        </tr>';
        while($row = $result->fetch_assoc()) {
                $orderId = $row["order_id"];
                echo '<form action="" method="post">';
                echo '<td><select name="food_code" required>';
                for($i=0;$i<count($shop->getProduct(null,null,$conn));$i++){
                    if($row["food_code"] == $shop->getProduct(null,null,$conn)[$i]['product_code'])
                     echo '<option value="'.$shop->getProduct(null,null,$conn)[$i]['product_code'].'" selected>'.$shop->getProduct(null,null,$conn)[$i]['product_code'].'</option>';    
                    else 
                     echo '<option value="'.$shop->getProduct(null,null,$conn)[$i]['product_code'].'" >'.$shop->getProduct(null,null,$conn)[$i]['product_code'].'</option>';    
                }
                echo '</select></td>';
                echo '<td><input type="text" name="order_number" value="'.$row["order_number"].'"></td>';
                echo '<td>'.$shop->getShop($shop->getProduct($row["food_code"],null,$conn)[0]['restaurant_id'],null,$conn)[0]['restaurant_name'].'</td>';
                echo '
                <td>  
                <input type="submit" value="بروز رسانی">
                <input type="hidden" name="id" value="'.$row["id"].'">  
                </form>
                    <form action="" method="post">
                    <input type="submit" value="حذف">
                    <input type="hidden" name="del" value="'.$row["id"].'">  
                </form>
                </form>
                </tr>';
        }

        echo '</table>';
    } 
    else {
        echo 'هیچ سفارشی وجود ندارد';
    }
  $conn->close(); 

?>

<form method="post"><input type="hidden" name="back" value="1"><input type="submit" value="برگشت"></form>