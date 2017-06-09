<?php
class shop{
    public function getShop($id,$type,$conn) {
        if($id != null){
            $sql = "SELECT * FROM restaurant_managers WHERE  restaurant_id='$id'";
        }
        else if($type != null){
            $sql = "SELECT * FROM restaurant_managers WHERE  restaurant_type='$type'";
        }
        else {
            $sql = "SELECT * FROM restaurant_managers WHERE  1";
        }
        
        $result = $conn->query($sql);
        $mshop= null;
        if ($result->num_rows > 0) {
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $mshop[$i]['restaurant_id']= $row['restaurant_id'] ;
                $mshop[$i]['restaurant_type']= $row['restaurant_type'] ;
                $mshop[$i]['restaurant_name']= $row['restaurant_name'];
                $mshop[$i]['restaurant_city']= $row['restaurant_city'];
                $mshop[$i]['restaurant_tel']= $row['restaurant_tel'];
                $mshop[$i]['restaurant_address']= $row['restaurant_address'];
                $mshop[$i]['manager_name']= $row['manager_name'];
                $mshop[$i]['manager_mobile']= $row['manager_mobile'];
                $i++;
            }
        }
       
        return $mshop;
    }

    public function getPeyk($id,$conn) {
        if($id != null){
            $sql = "SELECT * FROM transporter WHERE  restaurant_id='$id'";
        }
        else {
            $sql = "SELECT * FROM transporter WHERE  1";
        }
        $peyk = null;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $peyk[$i]['transporter_id']= $row['transporter_id'] ;
                $peyk[$i]['transporter_name']= $row['transporter_name'] ;
                $peyk[$i]['transporter_mobile']= $row['transporter_mobile'];
                $peyk[$i]['status']= $row['status'];
                $i++;
            }
        }
       
        return $peyk;
    }

    public function randomPeyk($conn) {
        $sql = "SELECT transporter_id FROM transporter WHERE  1";
        
        $peyk = null;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $peyk[$i]= $row['transporter_id'];
                $i++;
            }
        }
        shuffle($peyk);
        return $peyk[0];
    }

    public function getUsers($user_id,$conn) {
        if($user_id != null){
            $sql = "SELECT * FROM users WHERE  user_id='$user_id'";
        }
        else {
            $sql = "SELECT * FROM users WHERE  1";
        }
        
        $result = $conn->query($sql);
        $user = null;
        if ($result->num_rows > 0) {
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $user[$i]['user_id']= $row['user_id'] ;
                $user[$i]['user_name_family']= $row['user_name_family'] ;
                $user[$i]['user_mobile']= $row['user_mobile'];
                $user[$i]['user_address']= $row['user_address'];
                $user[$i]['user_pass']= $row['user_pass'];
                $user[$i]['user_type']= $row['user_type'];
                $i++;
            }
        }
       
        return $user;
    }
    
    public function getProduct($product_code,$restaurant_id,$conn) {
        if($product_code != null){
            $sql = "SELECT * FROM products_menu WHERE  product_code='$product_code'";
        }
        else if($restaurant_id !=null){
            $sql = "SELECT * FROM products_menu WHERE  restaurant_id='$restaurant_id' AND product_number>=1";
        }
        else {
            $sql = "SELECT * FROM products_menu WHERE  1";
        }
        
        $result = $conn->query($sql);
        $food = null;
        if ($result->num_rows > 0) {
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $food[$i]['product_code']= $row['product_code'] ;
                $food[$i]['restaurant_id']= $row['restaurant_id'] ;
                $food[$i]['product_name']= $row['product_name'];
                $food[$i]['product_type']= $row['product_type'];
                $food[$i]['product_number']= $row['product_number'];
                $food[$i]['product_price']= $row['product_price'];
                $food[$i]['product_disscount']= $row['product_disscount'];
                $food[$i]['product_pic']= $row['product_pic'];
                $food[$i]['product_description']= $row['product_description'];
                $food[$i]['create_time']= $row['create_time'];
                $i++;
            }
        }
       
        return $food;
    }

    public function getOrder($userId,$session,$conn) {
        $sql = "SELECT * FROM orders WHERE  customer_id='$userId' AND session='$session'";
        $result = $conn->query($sql);
        $order = null;
        if ($result->num_rows > 0) {
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $order[$i]['order_id']= $row['order_id'] ;
                $order[$i]['customer_id']= $row['customer_id'] ;
                $order[$i]['total_amount']= $row['total_amount'] ;
                $order[$i]['total_disscount']= $row['total_disscount'];
                $order[$i]['peyk_code']= $row['peyk_code'];
                $order[$i]['create_time']= $row['create_time'];
                $order[$i]['edit_time']= $row['edit_time'];
                $order[$i]['session']= $row['session'];
                $i++;
            }
        }
       
        return $order;
    }

    public function products_order($orderId,$food_code,$conn) {
        if($food_code == null)
            $sql = "SELECT * FROM products_order WHERE  order_id='$orderId'";
        else 
            $sql = "SELECT * FROM products_order WHERE  order_id='$orderId' AND food_code='$food_code'";

        $result = $conn->query($sql);
        $porder = null;
        if ($result->num_rows > 0) {
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $porder[$i]['order_id']= $row['order_id'] ;
                $porder[$i]['food_code']= $row['food_code'] ;
                $porder[$i]['order_number']= $row['order_number'] ;
                $i++;
            }
        }
       
        return $porder;
    }
    
    public function getComments($orderId,$conn) {
        $sql = "SELECT * FROM comments WHERE  order_id='$orderId'";
        $result = $conn->query($sql);
        $comment = null;
        if ($result->num_rows > 0) {
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $comment[$i]['score']= $row['score'] ;
                $comment[$i]['description']= $row['description'] ;
                $comment[$i]['create_time']= $row['create_time'] ;
                $i++;
            }
        }
       
        return $comment;
    }

    public function getPayments($orderId,$conn) {
        $sql = "SELECT * FROM payments WHERE  order_id='$orderId'";
        $result = $conn->query($sql);
        $pay = null;
        if ($result->num_rows > 0) {
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $pay[$i]['order_id']= $row['order_id'] ;
                $pay[$i]['pay_id']= $row['pay_id'] ;
                $pay[$i]['time']= $row['time'] ;
                $pay[$i]['card_number']= $row['card_number'] ;
                $pay[$i]['port_name']= $row['port_name'] ;
                $pay[$i]['price']= $row['price'] ;
                $pay[$i]['status']= $row['status'] ;
                $i++;
            }
        }
       
        return $pay;
    }

    public function checkPayments($restaurant_id,$conn) {
        $sql = "SELECT * FROM payments 
        LEFT JOIN products_order ON payments.order_id=products_order.order_id  
        LEFT JOIN products_menu ON products_order.food_code=products_menu.product_code  
        WHERE  status=1 ";
        $result = $conn->query($sql);
        $pay = null;
        if ($result->num_rows > 0) {
            $i = 0;
            
            while($row = $result->fetch_assoc()) {
                if ($restaurant_id == $row['restaurant_id'] ){
                    if(isset($pay[$row['food_code']]))
                        $pay[$row['food_code']] += $row['order_number'] ;
                    else 
                        $pay[$row['food_code']] = $row['order_number'] ;
                }
            
                $i++;
            }
        }

        // $keys = array_keys($pay);
        // $values = array_values($pay);

        return $pay;
    }

    public function upload($file,$check){
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($file["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
       
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "متاسفانه این نوع فرمت اجازه آپلود ندارد";
            $uploadOk = 0;
        }

        if ($check){
            return $uploadOk;
        }
        else {
            if ($uploadOk == 0) {
              return false;
            } else {
                if (move_uploaded_file($file["tmp_name"], $target_file)) {
                    return true;
                } else {
                    return false;
                }
            }
        }        
    }

    public function logOut($conn){
        $userId =  $_SESSION['userid'];
        $uniqId =  $_SESSION['uniqid'];
        unset($_SESSION['userid']);
        unset($_SESSION['uniqid']);
        unset($_SESSION['managerid']);
        unset($_SESSION['message']);
        //======================= clear session for sabad
        $sql = "UPDATE orders SET session=''  
        WHERE customer_id='$userId' AND session='$uniqId'";
        $conn->query($sql);
        //======================= clear session for sabad
        header("Location: index.php");
    }

    public function checkAccess($stat,$userId,$managerId,$conn){
        if(isset($managerId)){
            $manager = $this->getShop($managerId,null,$conn)[0];
            if($stat == 0)
                return $manager;
            else 
                return 'manager';
        }
        else if (isset($userId)) {
            $user =  $this->getUsers($userId,$conn)[0]; 
            if ($user['user_type'] != 1){
                if($stat == 0)
                    return $user;
                else 
                    return 'user';
            }
            else {
               if($stat == 0) 
                    return $user;
               else 
                    return 'admin';
            }
        }
        else {
            return null;
        }
    }

}


?>