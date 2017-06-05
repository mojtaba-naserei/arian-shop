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
        $mshop= '';
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
        
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $peyk[$i]['transporter_id']= $row['transporter_id'] ;
                $peyk[$i]['transporter_name']= $row['transporter_name'] ;
                $peyk[$i]['transporter_mobile']= $row['transporter_mobile'];
                $i++;
            }
        }
       
        return $peyk;
    }

    public function getUsers($user_id,$conn) {
        if($user_id != null){
            $sql = "SELECT * FROM users WHERE  user_id='$user_id'";
        }
        else {
            $sql = "SELECT * FROM users WHERE  1";
        }
        
        $result = $conn->query($sql);
        $user ='';
        if ($result->num_rows > 0) {
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $user[$i]['user_id']= $row['user_id'] ;
                $user[$i]['user_name_family']= $row['user_name_family'] ;
                $user[$i]['user_mobile']= $row['user_mobile'];
                $user[$i]['user_address']= $row['user_address'];
                $user[$i]['user_pass']= $row['user_pass'];
                $user[$i]['user_type']= $row['user_type'];
                $user[$i]['session']= $row['session'];
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
        $food = '';
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
}


?>