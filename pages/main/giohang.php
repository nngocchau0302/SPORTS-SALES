<style>
    .wrapper {
        height: 995px;
    }

    .wrapper_sanpham {
        margin-top: 20px;

    }

    .dathang {
        color: #1A3636;
        background: rgb(185, 203, 222);
        border-radius: 20px;
        padding: 7px 47px;
    }

    .dathang:hover {

        color: #DEAC80;

    }

    /*--test---*/
    .container-next {
        margin-bottom: 30px;
    }

    .arrow-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .arrow-steps .step {
        font-size: 15px;
        text-align: center;
        color: #fff;
        cursor: pointer;
        padding: 10px 8px;
        width: 20%;
        background-color: #ddd;
        border-radius: 4px;
        user-select: none;
        transition: background-color 0.3s, transform 0.3s;
        box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
    }

    .arrow-steps .step:hover {
        background-color: #cccccc;
        transform: translateY(-2px);
    }

    .arrow-steps .step.current {
        background-color: #5599e5;
    }

    .arrow-steps .step.done {
        background-color: #2f69aa;
    }

    .arrow-steps .step span {
        position: relative;
    }

    .nav {
        margin-top: 20px;
        text-align: center;
    }

    .nav .prev,
    .nav .next {
        display: inline-block;
        padding: 10px 20px;
        color: #fff;
        background-color: #5599e5;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s, transform 0.3s;
        box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
    }

    .nav .prev:hover,
    .nav .next:hover {
        background-color: #4179c5;
        transform: translateY(-2px);
    }

    .nav .prev:active,
    .nav .next:active {
        transform: translateY(0);
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!---code mới thêm vào--->
<div class="container-next">
    <!--Responsive...--->
    <div class="arrow-steps clearfix">
        <div class="step current"> <span> <a href='index.php?quanly=giohang'>Giỏ hàng</a> </span></div>
        <div class="step "> <span> <a href='index.php?quanly=vanchuyen'>Vận chuyển</a> </span></div>
        <div class="step "> <span> <a href='index.php?quanly=thongtinthanhtoan'>Thanh toán</a> </span></div>
        <div class="step "> <span> <a href='index.php?quanly=donhangdadat'>Lịch sử đơn hàng</a> </span></div>

    </div>
    <!---end Responsive--->
    <!-- <div class="nav clearfix">
        <a href='#' class="prev">Previous</a>
        <a href='#' class="next pull-right">Next</a>
    </div> -->
</div>
<?php
if (!empty($_SESSION['message'])) {
    echo '
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        ' . $_SESSION['message'] . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    unset($_SESSION['message']); // Xoá sau khi hiển thị
}
?>

<p class="title_giohang">Giỏ hàng của bạn <?php
                                            if (isset($_SESSION['dangky'])) {
                                                echo $_SESSION['dangky'];
                                            }
                                            ?> </p>
<div class="row">
    <?php

    if (isset($_SESSION['cart'])) {
    }
    ?>
    <table style="width:100%; text-align:center; border-collapse:collapse; margin-bottom: 20px;" border="1">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="w-1/12 py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Id</th>
                <th class="w-1/12 py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Mã sp</th>
                <th class="w-2/12 py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Tên sản phẩm</th>
                <th class="w-2/12 py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Hình ảnh</th>
                <th class="w-2/12 py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Kích thước</th>
                <th class="w-1/12 py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Số lượng</th>
                <th class="w-2/12 py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Giá sản phẩm</th>
                <th class="w-2/12 py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Thành Tiền</th>
                <th class="w-1/12 py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Quản lý</th>
            </tr>
        </thead>
        <?php
if (isset($_SESSION['cart'])) {
    $i = 0;
    $tongtien = 0;
    foreach ($_SESSION['cart'] as $cart_item) {
        $thanhtien =  $cart_item['soluong'] * $cart_item['giasp'];
        $tongtien += $thanhtien;
        $i++;
?>
    <tr class="border-b border-gray-300">
        <td class="py-3 px-4 border border-gray-300"><?php echo $i; ?></td>
        <td class="py-3 px-4 border border-gray-300"><?php echo $cart_item['masp'] ?></td>
        <td class="py-3 px-4 border border-gray-300"><?php echo $cart_item['tensanpham'] ?></td>
        <td class="py-3 px-4 border border-gray-300">
            <img src="admincf/modules/quanlysp/uploads/<?php echo $cart_item['hinhanh'] ?>" width="150px" alt="Image of <?php echo $cart_item['tensanpham'] ?>">
        </td>

        <!-- Cột Kích thước -->
        <td class="py-3 px-4 border border-gray-300">
            <?php
            $id_danhmuc = $cart_item['id_danhmuc'];
            if (in_array($id_danhmuc, [2, 7, 8, 9])) {
                echo $cart_item['size'];
            } else {
                echo "Không có";
            }
            ?>
        </td>

        <!-- Nút cộng/trừ số lượng -->
        <td>
            <a href="pages/main/themgiohang.php?cong=<?php echo $cart_item['id']; ?>&size=<?php echo $cart_item['size']; ?>"><i class="fas fa-plus-circle"></i></a>
            <?php echo $cart_item['soluong']; ?>
            <a href="pages/main/themgiohang.php?tru=<?php echo $cart_item['id']; ?>&size=<?php echo $cart_item['size']; ?>"><i class="fas fa-minus-circle"></i></a>
        </td>

        <!-- Cột giá sản phẩm -->
        <td class="py-3 px-4 border border-gray-300">
            <?php
            if (isset($cart_item['gia_goc']) && $cart_item['gia_goc'] > $cart_item['giasp']) {
                echo '<span style="text-decoration: line-through; color: gray;">' . number_format($cart_item['gia_goc'], 0, ',', '.') . 'đ</span><br>';
                echo '<span style="color: red; font-weight: bold;">' . number_format($cart_item['giasp'], 0, ',', '.') . 'đ</span>';
            } else {
                echo number_format($cart_item['giasp'], 0, ',', '.') . 'đ';
            }
            ?>
        </td>

        <!-- Cột thành tiền -->
        <td class="py-3 px-4 border border-gray-300">
            <?php echo number_format($thanhtien, 0, ',', '.') . 'đ'; ?>
        </td>

        <!-- Xóa sản phẩm -->
        <td>
            <a href="pages/main/themgiohang.php?xoa=<?php echo $cart_item['id']; ?>&size=<?php echo $cart_item['size']; ?>" class="text-red-500"><i class="fas fa-trash-alt"></i></a>
        </td>
    </tr>
<?php
    }
?>
    <!-- Tổng tiền -->
    <tr class="bg-gray-100">
        <td colspan="9" class="py-3 px-4 border border-gray-300">
            <div class="flex justify-between items-center">
                <p class="font-semibold">Tổng tiền: <?php echo number_format($tongtien, 0, ',', '.') . 'đ'; ?></p>
                <div>
                    <a href="pages/main/themgiohang.php?xoatatca=1" class="text-red-500">Xóa tất cả</a>
                </div>
            </div>
            <div style="clear:both;"></div>
            <?php if (isset($_SESSION['dangky'])) { ?>
                <p><a class="dathang" href="index.php?quanly=vanchuyen">Thông tin vận chuyển</a></p>
            <?php } else { ?>
                <p><a class="dathang" href="index.php?quanly=dangky">Đăng ký để đặt hàng</a></p>
            <?php } ?>
        </td>
    </tr>
<?php
} else {
?>
    <tr>
        <td colspan="9">
            <p>Hiện tại giỏ hàng trống</p>
        </td>
    </tr>
<?php } ?>

    </table>
</div>