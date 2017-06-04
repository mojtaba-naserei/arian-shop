<?php
require_once('../lib/connection.php'); //connect to DB
require_once('../lib/jdf.php'); //for shamsi time
require_once('../lib/shop.php');
$shop = new shop();
session_start();
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

        $sql = "UPDATE products_order 
        SET 
        food_code='$food_code',
        order_number='$order_number'
       
        WHERE id='$record_id'";

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

$sql = " SELECT orders.*,payments.status,products_order.*,products_menu.restaurant_id FROM orders 
LEFT JOIN payments ON orders.order_id = payments.order_id 
LEFT JOIN products_order ON orders.order_id = products_order.order_id 
LEFT JOIN products_menu ON products_order.food_code = products_menu.product_code 
";
 $result = $conn->query($sql);

 
    if ($result->num_rows > 0) {
      
        echo '<table border="1">';
        echo '<tr>
        <td>کد غذا</td>
        <td>تعداد غذا</td>
        <td>کد رستوران</td>
        
        <td>عملیات</td>
        </tr>';
        while($row = $result->fetch_assoc()) {
                $customerId = $row["customer_id"];
                $orderId = $row["order_id"];
                echo '<form action="" method="post">';
                echo '<td><select name="food_code" required>';
                for($i=0;$i<count($shop->getProduct(null,$conn));$i++){
                    if($row["food_code"] == $shop->getProduct(null,$conn)[$i]['product_code'])
                     echo '<option value="'.$shop->getProduct(null,$conn)[$i]['product_code'].'" selected>'.$shop->getProduct(null,$conn)[$i]['product_code'].'</option>';    
                    else 
                     echo '<option value="'.$shop->getProduct(null,$conn)[$i]['product_code'].'" >'.$shop->getProduct(null,$conn)[$i]['product_code'].'</option>';    
                }
                echo '</select></td>';
                echo '<td><input type="text" name="order_number" value="'.$row["order_number"].'"></td>';
                echo '<td>'.$shop->getShop($row["restaurant_id"],$conn)[0]['restaurant_name'].'</td>';
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
        // echo '<tr>
        // <td>کد مشتری</td>
        // <td>کد سفارش</td>
        // <td>وضعیت سفارش</td>
        // <td>جمع کل</td>
        // <td>تخفیف</td>
        // <td>کد غذا</td>
        // <td>تعداد غذا</td>
        // <td>کد رستوران</td>
        
        // <td>عملیات</td>
        // </tr>';
        // echo '<table border="1"><tr>';
        // echo '<td>'.$orderId.'</td><td>شماره سفارش</td></tr>';
        // echo '<tr><td>'.$shop->getUsers($customerId,$conn)[0]['user_name_family'].'</td><td>نام مشتری </td>';
        // echo '</tr></table>';
    } 
    else {
        echo 'هیچ سفارشی وجود ندارد';
    }
  $conn->close(); 

?>