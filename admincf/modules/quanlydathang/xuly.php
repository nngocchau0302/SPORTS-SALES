<!--?php
    // Bao gồm tệp cấu hình
    include('../../config/config.php');
  
    // Kiểm tra nếu biến GET đã được thiết lập
    if (isset($_GET['cart_status']) && isset($_GET['code'])) {
        $status = $_GET['cart_status'];
        $code = $_GET['code'];

        // Tạo câu truy vấn SQL
        $sql = "UPDATE tbl_cart SET cart_status='" . mysqli_real_escape_string($mysqli, $status) . "' WHERE code_cart='" . mysqli_real_escape_string($mysqli, $code) . "'";

        // Thực hiện truy vấn
        if (mysqli_query($mysqli, $sql)) {
            // Nếu truy vấn thành công, chuyển hướng
            
            header('Location: ../../index.php?action=quanlydathang&query=lietke');
            exit(); // Dừng thực thi sau khi chuyển hướng
        } else {
            // Nếu có lỗi trong truy vấn
            echo "Lỗi khi cập nhật trạng thái đơn hàng: " . mysqli_error($mysqli);
        }
    } 
    else if(isset($_POST['update_cart'])){
            $code_cart = $_POST['code_cart'];
            $tinhtrangdonhang = $_POST['tinhtrangdonhang'];
            $sql_update_tinhtrang = mysqli_query($mysqli, "UPDATE tbl_cart SET cart_status ='$tinhtrangdonhang' WHERE code_cart='$code_cart'");
            header('Location: ../../index.php?action=quanlydathang&query=lietke');
    }else {
        // Nếu không có biến GET
        echo "Thiếu thông tin để cập nhật.";
    }

   
   

    use Carbon\Carbon;
    use Carbon\CarbonInterval;
    require('../../../carbon/autoload.php');
    include('../../config/config.php');

    if(isset($_GET['code'])){
        $code_cart = $_GET['code'];
        $sql_update = "UPDATE tbl_cart SET cart_status=0 WHERE code_cart='".$code_cart."'";
        $query = mysqli_query($mysqli,$sql_update);

        //thống kê doanh thu
        $sql_lietke_dh = "SELECT * FROM tbl_cart_details, tbl_sanpham WHERE tbl_cart_details.id_sanpham=tbl_sanpham.id_sanpham AND tbl_cart_details.code_cart='$code_cart' ORDER BY tbl_cart_details.id_cart_details DESC ";
        $query_lietke_dh = mysqli_query($mysqli,$sql_lietke_dh);

        $sql_thongke = "SELECT * FROM tbl_thongke WHERE ngaydat='$now'";
        $query_thongke =mysqli_query($mysqli,$sql_thongke);
        
        $soluongmua = 0;
        $doanhthu = 0;

        while($row = mysqli_fetch_array($query_lietke_dh)){
            $soluongmua+=$row['soluongmua'];
            $doanhthu+=$row['giasp'];
        }
        if(mysqli_num_rows($query_thongke)==0){
            $soluongmua += $doanhthu;
            $doanhthu = $doanhthu;
            $donhang = 1;
            $sql_update_thongke = mysqli_query($mysqli,"INSERT INTO tbl_thongke (ngaydat,soluongban,doanhthu,donhang) VALUE('$now','$soluongban', '$doanhthu','$donhang' )");



        }elseif(mysqli_num_rows($query_thongke)!=0){
            while($row_tk = mysqli_fetch_array($query_thongke)){
                $soluongban = $row_tk['soluongban'] +$soluongban;
                $doanhthu = $row_tk['doanhthu'] +$doanhthu;
                $donhang = $row_tk['donhang'] +1;
                $sql_update_thongke = mysqli_query($mysqli,"UPDATE  tbl_thongke SET soluongban='$soluongban', doanhthu='$doanhthu', donhang='$donhang' WHERE ngaydat='$now'");

            }
        }
    
    header('Location:../../index.php?action=quanlydathang&query=lietke');
    
    }
?-->


<?php
// Bao gồm tệp cấu hình
include('../../config/config.php');
use Carbon\Carbon;
require('../../../carbon/autoload.php');

$now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

if (isset($_GET['cart_status']) && isset($_GET['code'])) {
    $status = $_GET['cart_status'];
    $code = $_GET['code'];
    $code_cart = $_POST['code_cart'];
    $tinhtrangdonhang = $_POST['tinhtrangdonhang'];
    // Cập nhật trạng thái đơn hàng
    $sql = "UPDATE tbl_cart SET cart_status='" . mysqli_real_escape_string($mysqli, $status) . "' WHERE code_cart='" . mysqli_real_escape_string($mysqli, $code) . "'";
    $sql_update_tinhtrang = mysqli_query($mysqli, "UPDATE tbl_cart SET cart_status ='$tinhtrangdonhang' WHERE code_cart='$code_cart'");
    $query = mysqli_query($mysqli,$sql);
    $query = mysqli_query($mysqli,$sql_update_tinhtrang);
    if (mysqli_query($mysqli, $sql)) {
        // Thống kê doanh thu dựa trên đơn hàng
        $sql_orders = "SELECT SUM(cd.soluongmua) AS total_quantity, SUM(cd.giasp) AS total_sales
                       FROM tbl_cart_details cd
                       WHERE cd.code_cart='$code'";
                        
        $result_orders = mysqli_query($mysqli, $sql_orders);
        $order_data = mysqli_fetch_array($result_orders);
        
        $soluongmua = $order_data['total_quantity'];
        $doanhthu = $order_data['total_sales'];

        // Cập nhật hoặc thêm dữ liệu vào bảng thống kê
        $sql_thongke = "SELECT * FROM tbl_thongke WHERE ngaydat='$now'";
        $query_thongke = mysqli_query($mysqli, $sql_thongke);

        if (mysqli_num_rows($query_thongke) == 0) {
            // Nếu chưa có dữ liệu cho ngày hôm nay
            $donhang = 1;
            $sql_insert = "INSERT INTO tbl_thongke (ngaydat, soluongban, doanhthu, donhang) VALUES ('$now', '$soluongmua', '$doanhthu', '$donhang')";
            mysqli_query($mysqli, $sql_insert);
        } else {
            // Cập nhật dữ liệu thống kê
            $row_tk = mysqli_fetch_array($query_thongke);
            $soluongban_new = $row_tk['soluongban'] + $soluongmua;
            $doanhthu_new = $row_tk['doanhthu'] + $doanhthu;
            $donhang_new = $row_tk['donhang'] + 1;

            $sql_update = "UPDATE tbl_thongke SET soluongban='$soluongban_new', doanhthu='$doanhthu_new', donhang='$donhang_new' WHERE ngaydat='$now'";
            mysqli_query($mysqli, $sql_update);
        }
        
        header('Location: ../../index.php?action=quanlydathang&query=lietke');
        exit();
    } else {
        echo "Lỗi khi cập nhật trạng thái đơn hàng: " . mysqli_error($mysqli);
    }
} else if(isset($_POST['update_cart'])){
    $code_cart = $_POST['code_cart'];
    $tinhtrangdonhang = $_POST['tinhtrangdonhang'];
    $sql_update_tinhtrang = mysqli_query($mysqli, "UPDATE tbl_cart SET cart_status ='$tinhtrangdonhang' WHERE code_cart='$code_cart'");
    header('Location: ../../index.php?action=quanlydathang&query=lietke');
}else {
// Nếu không có biến GET
echo "Thiếu thông tin để cập nhật.";
}

?>