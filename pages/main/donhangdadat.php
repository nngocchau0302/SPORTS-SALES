<?php
$id_khachhang = $_SESSION['id_khachhang'];
$sql_lietke_dh = "SELECT * FROM tbl_cart 
                  JOIN tbl_dangky ON tbl_cart.id_khachhang = tbl_dangky.id_dangky 
                  WHERE tbl_cart.id_khachhang='$id_khachhang' 
                  ORDER BY tbl_cart.id_cart DESC";

$query_lietke_dh = mysqli_query($mysqli, $sql_lietke_dh);
if (!$query_lietke_dh) {
    die("Query failed: " . mysqli_error($mysqli));
}
?>
<?php
//update tình trạng
if(isset($_GET['update_tinhtrang'])){
    $update_tinhtrang = $_GET['update_tinhtrang'];
    $sql_update_tinhtrangdonhang = mysqli_query($mysqli, "UPDATE tbl_cart SET cart_status =3  WHERE code_cart='$update_tinhtrang'");
    header('Location:index.php?quanly=donhangdadat');
}
?>
<!-- <p class="title_giohang">Lịch sử đơn hàng</p> -->
<div class="container-next">
    <!--Responsive...--->
    <div class="arrow-steps clearfix">
        <div class="step done"> <span>   <a href='index.php?quanly=giohang'>Giỏ hàng</a>   </span></div>
        <div class="step done"> <span>   <a href='index.php?quanly=vanchuyen'>Vận chuyển</a>   </span></div>
        <div class="step done"> <span>   <a href='index.php?quanly=thongtinthanhtoan'>Thanh toán</a>   </span></div>
        <div class="step current"> <span>   <a href='index.php?quanly=donhangdadat'>Lịch sử đơn hàng</a>   </span></div>
        
    </div>
    
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-6xl">
        <div class="p-8 border-b">
            <h2 class="text-3xl font-bold text-center text-gray-800">Lịch sử đơn hàng</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>

                    <tr>
                        <th class="py-3 px-6 bg-gray-200 border-b text-left text-sm font-semibold text-gray-700">Id</th>
                        <th class="py-3 px-6 bg-gray-200 border-b text-left text-sm font-semibold text-gray-700">Mã đơn hàng</th>
                        <th class="py-3 px-6 bg-gray-200 border-b text-left text-sm font-semibold text-gray-700">Tên khách hàng</th>
                        <th class="py-3 px-6 bg-gray-200 border-b text-left text-sm font-semibold text-gray-700">Địa Chỉ</th>
                        <th class="py-3 px-6 bg-gray-200 border-b text-left text-sm font-semibold text-gray-700">Tình trạng</th>
                        <th class="py-3 px-6 bg-gray-200 border-b text-left text-sm font-semibold text-gray-700">Ngày đặt</th>
                        <th class="py-3 px-6 bg-gray-200 border-b text-left text-sm font-semibold text-gray-700">Hình thức thanh toán</th>
                        <th class="py-3 px-6 bg-gray-200 border-b text-left text-sm font-semibold text-gray-700">Quản lý</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    while ($row = mysqli_fetch_array($query_lietke_dh)) {
                        $i++;
                    ?>
                        <tr class="hover:bg-gray-100">
                            <td class="py-4 px-6 border-b text-sm text-gray-700"><?php echo $i ?></td>
                            <td class="py-4 px-6 border-b text-sm text-gray-700"><?php echo $row['code_cart'] ?></td>
                            <td class="py-4 px-6 border-b text-sm text-gray-700"><?php echo $row['tenkhachhang'] ?></td>
                            <td class="py-4 px-6 border-b text-sm text-gray-700"><?php echo $row['diachi'] ?></td>
                            <!-- <td class="py-2 px-4 border-b"><-?php echo $row['email'] ?></td>
    <td class="py-2 px-4 border-b"><-?php echo $row['dienthoai'] ?></td> -->
                            <td class="py-4 px-6 border-b text-sm text-gray-700">
                                <?php
                                if ($row['cart_status'] == 1) {
                                    echo '<span style="color:blue;">Chờ xác nhận</span>';
                                } elseif($row['cart_status']==2){
                                    echo'<span style="color:#EE7600;"><a onclick="return confirm("Xác nhận đã nhận")" href="'. $_SERVER['REQUEST_URI'].'&update_tinhtrang='. $row['code_cart'].' " >Đang vận chuyển</span>';
                                }else{
                                    echo'<span style="color:green;">Đã nhận hàng</span>';
                                }
                                ?>
                            </td>
                            <td class="py-4 px-6 border-b text-sm text-gray-700"><?php echo $row['cart_date'] ?></td>

                            <td class="py-4 px-6 border-b text-sm text-gray-700">
                                <?php 
    if ($row['cart_payment'] == 'vnpay') {
        echo '<span class="text-blue-500 font-semibold">VNPAY</span>';
    } elseif ($row['cart_payment'] == 'momo') {
        echo '<span class="text-pink-500 font-semibold">MOMO</span>';
    }elseif ($row['cart_payment'] == 'momo_qr') {
        echo '<span class="text-pink-800 font-semibold">MOMO_QR</span>';
    } 
    else {
         echo '<span class="text-green-500 font-semibold">Thanh toán tiền mặt</span>';
    }
?>
                            
                            </td>
                            <td class="py-4 px-6 border-b text-sm text-gray-700">
                                <a class="bg-blue-500 text-white px-2 py-1 rounded" href="index.php?quanly=xemdonhang&code=<?php echo $row['code_cart'] ?>">Xem đơn hàng</a>
                                <!-- <a-- class="bg-blue-500 text-white px-2 py-1 rounded"  href="index.php?action=donhang&query=indonhang&code=<-?php echo $row['code_cart'] ?>">In đơn hàng</a-->
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
    
</div>