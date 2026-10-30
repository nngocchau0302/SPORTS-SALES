<?php
session_start();
include('../../admincf/config/config.php');



if (isset($_POST['themgiohang'])) {
    $id = $_GET['idsanpham'];
    $soluong = 1;
    $size = $_POST['selected_size'];

    $sql_sp = "SELECT * FROM tbl_sanpham WHERE id_sanpham='$id' LIMIT 1";
    $query_sp = mysqli_query($mysqli, $sql_sp);
    $row_sp = mysqli_fetch_array($query_sp);

    // Kiểm tra giá khuyến mãi nếu có
    $today = date('Y-m-d');
    $sql_km = "SELECT * FROM tbl_khuyenmai 
               WHERE id_sanpham = '$id' 
               AND '$today' BETWEEN ngaybatdau AND ngayketthuc 
               LIMIT 1";
    $query_km = mysqli_query($mysqli, $sql_km);
    if (mysqli_num_rows($query_km) > 0) {
        $row_km = mysqli_fetch_array($query_km);
        $phantram = $row_km['phantramgiam'];
        $giasp = $row_sp['giasp'] * (1 - $phantram / 100);
    } else {
        $giasp = $row_sp['giasp'];
    }

    $item = array(
        'tensanpham' => $row_sp['tensanpham'],
        'id' => $id,
        'soluong' => $soluong,
        'giasp' => $giasp,
        'gia_goc' => $row_sp['giasp'],
        'hinhanh' => $row_sp['hinhanh'],
        'masp' => $row_sp['masp'],
        'size' => $size,
        'id_danhmuc' => $row_sp['id_danhmuc']
    );

    if (isset($_SESSION['cart'])) {
        $found = false;
        foreach ($_SESSION['cart'] as &$sp) {
            if ($sp['id'] == $id && $sp['size'] == $size) {
                $sp['soluong'] += $soluong;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $_SESSION['cart'][] = $item;
        }
    } else {
        $_SESSION['cart'][] = $item;
    }
    header('Location: ../../index.php?quanly=giohang');
}
//xoa tat ca
if (isset($_GET['xoatatca']) && $_GET['xoatatca'] == 1) {
    unset($_SESSION['cart']);
    header('Location:../../index.php?quanly=giohang');
}
// Xóa sản phẩm khỏi giỏ hàng
if (isset($_SESSION['cart']) && isset($_GET['xoa'])) {
    $id = $_GET['xoa'];
    $size = $_GET['size'];

    foreach ($_SESSION['cart'] as $key => $cart_item) {
        if ($cart_item['id'] == $id && $cart_item['size'] == $size) {
            unset($_SESSION['cart'][$key]);
            break; // Dừng ngay sau khi xóa
        }
    }

    // Đặt lại chỉ số key để tránh lỗi
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header('Location:../../index.php?quanly=giohang');
    exit;
}


// === Tăng số lượng ===
if (isset($_GET['cong'])) {
    $id = $_GET['cong'];
    $size = $_GET['size'];
    $_SESSION['message'] = '';

    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['id'] == $id && $cart_item['size'] == $size) {
            if ($cart_item['soluong'] < 10) {
                $cart_item['soluong']++;
            } else {
                $_SESSION['message'] = "⚠️ Bạn không thể thêm quá 10 sản phẩm cho <strong>{$cart_item['tensanpham']}</strong>.";
            }
            break;
        }
    }
    header('Location:../../index.php?quanly=giohang');
    exit;
}

// === Giảm số lượng ===
if (isset($_GET['tru'])) {
    $id = $_GET['tru'];
    $size = $_GET['size'];

    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['id'] == $id && $cart_item['size'] == $size) {
            if ($cart_item['soluong'] > 1) {
                $cart_item['soluong']--;
            }
            break;
        }
    }
    header('Location:../../index.php?quanly=giohang');
    exit;
}

?>