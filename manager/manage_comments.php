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
    if(isset($_POST['id'])){
        $id = $_POST['id'];
        $order_id = $_POST['order_id'];
        $score = $_POST['score'];
        $description = $_POST['description'];
        $time = explode('/',$_POST['create_time']); 
	    $newTime = strtotime(jalali_to_gregorian($time[0],$time[1],$time[2],'-'));
        $sql = "UPDATE comments 
        SET 
        order_id='$order_id',
        score='$score',
        description='$description',
        create_time='$newTime'

        WHERE id='$id'";

        if ($conn->query($sql) === TRUE) {
            echo "نظر با موفقیت بروزرسانی شد";
        } else {
            echo "خطا در بروز رسانی: " . $conn->error;
        }
    }
    else if(isset($_POST['del'])) {
         //================================================ add 
        $id = $_POST['del'];
        $sql = "DELETE FROM comments WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
             echo "نظر حذف شد";
        } else {
             echo "خطا در حذف: " . $conn->error;
        }
    }
}
//===================================== update
// order_id
// score
// description
// create_time
$sql = "SELECT * FROM comments WHERE 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        echo '<table border="1">';
        echo '<tr>
        <td>شناسه </td>
        <td>شماره سفارش</td>
        <td>امتیاز</td>
        <td>توضیحات</td>
        <td>زمان ایجاد</td>
        <td>عملیات</td></tr>';
        while($row = $result->fetch_assoc()) {
                echo '<form action="" method="post">';
                echo '<tr><td>'.$row["id"].'</td>';
                echo '<td><input type="text" name="order_id" value="'.$row["order_id"].'"></td>';
                echo '<td><input type="text" name="score" value="'.$row["score"].'"></td>';
                echo '<td><textarea rows="10" cols="20" name="description">'.$row["description"].'</textarea></td>';
                echo '<td><input type="text" name="create_time" value="'.jdate("o/m/j",$row["create_time"]).'"></td>';
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
        echo 'هیچ نظری وجود ندارد';
    }
  $conn->close(); 

?>

<button onclick="window.history.back()">برگشت </button>