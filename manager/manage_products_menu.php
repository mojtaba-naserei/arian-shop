<?php
require_once('../lib/connection.php'); //connect to DB
require_once('../lib/jdf.php'); //for shamsi time
require_once('../lib/shop.php'); 
$shop = new Shop();
session_start();
//===========================================  check access
if(isset($_SESSION['userid'])) $userId=  $_SESSION['userid']; else $userId= null;
if(isset($_SESSION['managerid'])) $managerId=  $_SESSION['managerid']; else $managerId= null;
if($shop->checkAccess(1,$userId,$managerId,$conn) == 'user'){
    $_SESSION['message'] = 'شما به این صفحه دسترسی ندارید';
    header("Location: ../index.php");
    die();
}
//===========================================  check access

//===================================== update
$shop = new shop();
if(isset($_POST) && $_POST != null){
    if(isset($_POST['product_code'])){
        $product_code = $_POST['product_code'];
        $restaurant_id = $_POST['restaurant_id'];
        $product_name = $_POST['product_name'];
        $product_type = $_POST['product_type'];
        $product_number = $_POST['product_number'];
        $product_price = $_POST['product_price'];
        $product_disscount = $_POST['product_disscount'];
        $product_description = $_POST['product_description'];
        $time = explode('/',$_POST['create_time']); 
	    $newTime = strtotime(jalali_to_gregorian($time[0],$time[1],$time[2],'-'));
        $sql = "UPDATE products_menu 
        SET 
        restaurant_id='$restaurant_id',
        product_name='$product_name',
        product_type='$product_type',
        product_number='$product_number',
        product_price='$product_price',
        product_disscount='$product_disscount',
        product_description='$product_description',
        create_time='$newTime'

        WHERE product_code='$product_code'";

        if ($conn->query($sql) === TRUE) {
            echo "منو با موفقیت بروزرسانی شد";
        } else {
            echo "خطا در بروز رسانی: " . $conn->error;
        }
    }
    //======================================== delete
    else if(isset($_POST['del'])){
        $product_code =  $_POST['del'];
        $sql = "DELETE FROM products_menu WHERE product_code='$product_code'";
        if ($conn->query($sql) === TRUE) {
          echo "منو حذف شد";
        } else {
         echo "خطا در حذف: " . $conn->error;
        }
    }
    //====================================== update pic
    else if(isset($_POST['pic'])){  
        if($shop->upload($_FILES["fileToUpload"],true)){
            $shop->upload($_FILES["fileToUpload"],false); //upload file
            //----------------------- upadate name file
            $product_pic = $_FILES["fileToUpload"]["name"];
            $product_code = $_POST['pic']; 
            $sql = "UPDATE products_menu SET product_pic='$product_pic' WHERE product_code='$product_code'";

            if ($conn->query($sql) === TRUE) {
                echo "تصویر با موفقیت بروزرسانی شد";
            } else {
                echo "خطا در بروز رسانی: " . $conn->error;
            }
        }
        else {
            echo 'این عکس مجاز نیست';
        }   
    }
    else {
        //================================================ add 
        $restaurant_id = $_POST['restaurant_id'];
        $product_name = $_POST['product_name'];
        $product_type = $_POST['product_type'];
        $product_number = $_POST['product_number'];
        $product_price = str_replace(',','',$_POST['product_price']);
        $product_disscount = $_POST['product_disscount'];
        $ctime = time();
        if($shop->upload($_FILES["fileToUpload"],true))
             $product_pic = $_FILES["fileToUpload"]["name"];
        else 
             $product_pic = 'empty';

        $product_description = $_POST['product_description'];
    
        if(
            isset($restaurant_id) && 
            isset($product_name)&&
            isset($product_type)&&
            isset($product_number)&&
            isset($product_price)&&
            isset($product_disscount)&&
            isset($product_description) &&
            isset($product_pic) 
        ){
            $sql = "INSERT INTO products_menu (restaurant_id, product_name,product_type,product_number,product_price,product_disscount,product_description,product_pic,create_time)
            VALUES ('$restaurant_id','$product_name','$product_type','$product_number','$product_price','$product_disscount','$product_description','$product_pic','$ctime')";

            if ($conn->query($sql) === TRUE) {
                //============== upload image
                $shop->upload($_FILES["fileToUpload"],false);
                //============== upload image
                echo "منو با موفقیت اضافه شد";
            } 
            else {
                echo "خطا: " . $sql . "<br>" . $conn->error;
            }
        }
        else {
            echo 'هیچ فیلدی نباید خالی باشد';
        }
    }
}
//================================================== load data
    if($shop->checkAccess(1,$userId,$managerId,$conn) == 'admin'){
        $sql = "SELECT * FROM products_menu WHERE 1";
    }
    else {
        $restaurantId = $shop->checkAccess(0,$userId,$managerId,$conn)['restaurant_id'];
        $sql = "SELECT * FROM products_menu WHERE restaurant_id='$restaurantId'";
    }
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {

        echo '<table border="1">';
        echo '<tr><td>شناسه محصول</td><td>نام فروشگاه</td><td>نام محصول</td><td>نوع محصول</td><td>تعداد موجودی</td><td>قیمت</td><td>تخفیف(درصد)</td><td>َتصویر</td><td>َتوضیحات</td><td>َزمان ایجاد</td><td>عملیات</td> </tr>';
        while($row = $result->fetch_assoc()) {
                echo '<form action="" method="post">';
                echo '<tr><td>'.$row["product_code"].'</td>';
                
                if($shop->checkAccess(1,$userId,$managerId,$conn) == 'admin'){
                    echo '<td><select name="restaurant_id" required>';
                    for($i=0;$i<count($shop->getShop(null,null,$conn));$i++){
                        if($row["restaurant_id"] == $shop->getShop(null,null,$conn)[$i]['restaurant_id'])
                             echo '<option value="'.$shop->getShop(null,null,$conn)[$i]['restaurant_id'].'" selected>'.$shop->getShop(null,null,$conn)[$i]['restaurant_name'].'</option>';    
                        else 
                             echo '<option value="'.$shop->getShop(null,null,$conn)[$i]['restaurant_id'].'" >'.$shop->getShop(null,null,$conn)[$i]['restaurant_name'].'</option>';    
                     }
                     echo '</select></td>';
                }
                else {
                     echo ' <input type="hidden" name="restaurant_id" value="'.$row['restaurant_id'].'"> ';
                     echo '<td>'.$shop->getShop($row['restaurant_id'],null,$conn)[0]['restaurant_name'].'</td>';
                }
                echo '<td><input type="text" name="product_name" value="'.$row["product_name"].'"></td>';
                echo '<td><input type="text" name="product_type" value="'.$row["product_type"].'"></td>';
                echo '<td><input type="text" name="product_number" value="'.$row["product_number"].'"></td>';
                echo '<td><input type="text" name="product_price" value="'.number_format($row["product_price"]).'"></td>';
                echo '<td><input type="text" name="product_disscount" value="'.$row["product_disscount"].'"></td>';
                if($row["product_pic"] != 'empty')
                    echo '<td><img  src="../uploads/'.$row["product_pic"].'" height="100" width="100"></td>';
                else
                    echo '<td>بدون تصویر</td>';
                echo '<td><input type="text" name="product_description" value="'.$row["product_description"].'"></td>';
                echo '<td><input type="text" name="create_time" value="'.jdate("o/m/j",$row["create_time"]).'"></td>';
                echo '
                <td>  
                <input type="submit" value="بروز رسانی">
                <input type="hidden" name="product_code" value="'.$row["product_code"].'">  
                ';
                echo '</form>';
                echo '<form action="" method="post" enctype="multipart/form-data">
                        <table>
                        <tr>
                            <td><input type="submit" value="تعویض عکس" name="submit"></td> 
                            <td><input type="file" name="fileToUpload" id="fileToUpload"></td> 
                        </tr>
                            <input type="hidden" name="pic" value="'.$row["product_code"].'">  
                        </table>
                    </form>';
                echo '<form action="" method="post">
                 
                <input type="submit" value="حذف">
                <input type="hidden" name="del" value="'.$row["product_code"].'">  
                </td>
                </tr>';
                echo '</form>';
        }
         echo '</table>';
    } 
    else {
        echo 'هیچ منویی وجود ندارد';
    }



if($shop->checkAccess(1,$userId,$managerId,$conn) != 'user'){
?>

<br>
<h2>اضافه کردن منو</h2>
<div> 
<form action="" method="post" enctype="multipart/form-data">
  <div class="container">
   <label><b>نام فروشگاه</b></label>
<?php if($shop->checkAccess(1,$userId,$managerId,$conn) == 'admin'){ ?>
    <select name="restaurant_id" required>
           <?php     
            for($i=0;$i<count($shop->getShop(null,null,$conn));$i++){
                echo '<option value="'.$shop->getShop(null,null,$conn)[$i]['restaurant_id'].'" >'.$shop->getShop(null,null,$conn)[$i]['restaurant_name'].'</option>';    
            } 
            ?>
    </select>
<?php } 
else { 
 echo ' <input type="hidden" name="restaurant_id" value="'.$restaurantId.'"> ';   
 echo '<td>'.$shop->getShop($restaurantId,null,$conn)[0]['restaurant_name'].'</td>';
}
?>
<br>
    <label><b>نام محصول</b></label>
    <input type="text" placeholder="نام محصول را وارد کنید" name="product_name" required>
<br>
    <label><b>نوع محصول</b></label>
    <input type="text" placeholder="نوع محصول را وارد کنید" name="product_type" required>
<br>
    <label><b>تعداد موجودی</b></label>
    <input type="text" placeholder="تعداد موجودی را وارد کنید" name="product_number" required>
<br>
    <label><b>قیمت محصول(ریال)</b></label>
    <input type="text" placeholder="قیمت محصول را وارد کنید" name="product_price" required>
<br>
    <label><b>تخفیف(درصد)</b></label>
    <input type="text" placeholder="مقدار تخفیف به عدد وارد کنید" name="product_disscount" required>
<br> 
<br>
    <label><b>توضیحات</b></label>
    <textarea type="text" name="product_description" required> </textarea>
<br>
عکس را انتخاب کنید
    <input type="file" name="fileToUpload" id="fileToUpload">
<br>
    <div class="clearfix">
      <button type="submit" name="submit" class="signupbtn">ثبت </button>
    </div>
  </div>
</form> 
</div>

<?php } ?>

<button onclick="window.history.back()">برگشت </button>