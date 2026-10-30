<?php
include('../../config/config.php');
$tensanpham = $_POST['tensanpham'];
$masp = $_POST['masp'];
$giasp = $_POST['giasp'];
$soluong = $_POST['soluong'];
//xử lý hình ảnh
$hinhanh = $_FILES['hinhanh']['name'];
$hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
$hinhanh = time() . '_' . $hinhanh;
$tinhtrang = $_POST['tinhtrang'];
$baohanh = $_POST['baohanh'];
$danhmuc = $_POST['danhmuc'];
$tenth = $_POST['thuonghieu'];
  $mota = mysqli_real_escape_string($mysqli, $_POST['mota']); // lấy mô tả

if (isset($_POST['Themsanpham'])) {
    // Thêm sản phẩm mới
    $sql_them = "INSERT INTO tbl_sanpham(tensanpham,masp,giasp,soluong,hinhanh,tinhtrang,baohanh,id_danhmuc,id_thuonghieu,mota) 
    VALUES('$tensanpham','$masp','$giasp','$soluong','$hinhanh','$tinhtrang','$baohanh','$danhmuc','$tenth','$mota')";
    mysqli_query($mysqli, $sql_them);
    move_uploaded_file($hinhanh_tmp, 'uploads/' . $hinhanh);

    $id_sanpham = mysqli_insert_id($mysqli); // Lấy ID vừa thêm

    // Lưu kích thước nếu có
    if (!empty($_POST['size'])) {
        foreach ($_POST['size'] as $size => $quantity) {
            if ($quantity > 0) {
                $sql_size = "INSERT INTO tbl_kichthuoc (id_sanpham, size, soluong) VALUES ('$id_sanpham', '$size', '$quantity')";
                mysqli_query($mysqli, $sql_size);
            }
        }
    }

      // Nếu sản phẩm là vợt cầu lông, tạo số seri
    $sql_danhmuc = "SELECT tendanhmuc FROM tbl_danhmuc WHERE id_danhmuc = '$danhmuc' LIMIT 1";
    $query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);
    $row_danhmuc = mysqli_fetch_array($query_danhmuc);

    if (stripos($row_danhmuc['tendanhmuc'], 'vợt') !== false) {
        for ($i = 0; $i < $soluong; $i++) {
            // Seri dạng VTddmmyy-XXXX
            $ngay = date('dmy');
            $random = strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
            $seri = 'VT' . $ngay . '-' . $random;

            // Đảm bảo không trùng seri
            $check_seri = mysqli_query($mysqli, "SELECT * FROM tbl_seri WHERE seri = '$seri'");
            while (mysqli_num_rows($check_seri) > 0) {
                $random = strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
                $seri = 'VT' . $ngay . '-' . $random;
                $check_seri = mysqli_query($mysqli, "SELECT * FROM tbl_seri WHERE seri = '$seri'");
            }

            $sql_seri = "INSERT INTO tbl_seri (id_sanpham, seri, tinhtrang) VALUES ('$id_sanpham', '$seri', 'Chưa kích hoạt')";
            mysqli_query($mysqli, $sql_seri);
        }
    }

    header('Location:/admincf/index.php?action=quanlysp&query=them');
}  elseif (isset($_POST['suasanpham'])) {
    $id_sanpham = $_GET['idsanpham'];

    // Lấy số lượng cũ từ DB để so sánh
    $sql_old = "SELECT soluong, hinhanh FROM tbl_sanpham WHERE id_sanpham = '$id_sanpham' LIMIT 1";
    $query_old = mysqli_query($mysqli, $sql_old);
    $row_old = mysqli_fetch_assoc($query_old);
    $soluong_old = $row_old['soluong'];
    $hinhanh_old = $row_old['hinhanh'];

    // Update sản phẩm
    if ($hinhanh != '') {
        move_uploaded_file($hinhanh_tmp, 'uploads/' . $hinhanh);
        unlink('uploads/' . $hinhanh_old);
        $sql_update = "UPDATE tbl_sanpham SET tensanpham='$tensanpham', masp='$masp', giasp='$giasp', soluong='$soluong', hinhanh='$hinhanh', tinhtrang='$tinhtrang', baohanh='$baohanh', id_danhmuc='$danhmuc', id_thuonghieu='$tenth',mota='$mota' WHERE id_sanpham='$id_sanpham'";
    } else {
        $sql_update = "UPDATE tbl_sanpham SET tensanpham='$tensanpham', masp='$masp', giasp='$giasp', soluong='$soluong', tinhtrang='$tinhtrang', baohanh='$baohanh', id_danhmuc='$danhmuc', id_thuonghieu='$tenth', mota='$mota' WHERE id_sanpham='$id_sanpham'";
    }
    mysqli_query($mysqli, $sql_update);

    // Xử lý số seri nếu số lượng thay đổi
    $chenh_lech = $soluong - $soluong_old;

    if ($chenh_lech > 0) {
        // Tăng: thêm seri mới
        for ($i = 1; $i <= $chenh_lech; $i++) {
            $seri = strtoupper(substr(uniqid(), -8));
            $sql_seri = "INSERT INTO tbl_seri (id_sanpham, seri) VALUES ('$id_sanpham', '$seri')";
            mysqli_query($mysqli, $sql_seri);
        }
    } elseif ($chenh_lech < 0) {
        // Giảm: xóa seri gần nhất
        $sql_get_seri = "SELECT id_seri FROM tbl_seri WHERE id_sanpham='$id_sanpham' ORDER BY id_seri DESC LIMIT " . abs($chenh_lech);
        $result = mysqli_query($mysqli, $sql_get_seri);
        while ($row_seri = mysqli_fetch_assoc($result)) {
            $id_seri = $row_seri['id_seri'];
            mysqli_query($mysqli, "DELETE FROM tbl_seri WHERE id_seri = '$id_seri'");
        }
    }

    header('Location:/admincf/index.php?action=quanlysp&query=them');
} else {
    // Xóa sản phẩm
    $id = $_GET['idsanpham'];

    // Xóa ảnh
    $sql = "SELECT * FROM tbl_sanpham WHERE id_sanpham ='$id' LIMIT 1";
    $query = mysqli_query($mysqli, $sql);
    while ($row = mysqli_fetch_array($query)) {
        if (!empty($row['hinhanh']) && file_exists('uploads/' . $row['hinhanh'])) {
            unlink('uploads/' . $row['hinhanh']);
        }
    }

    // Xóa seri (nếu là vợt)
    mysqli_query($mysqli, "DELETE FROM tbl_seri WHERE id_sanpham = '$id'");

    // Xóa kích thước (size)
    mysqli_query($mysqli, "DELETE FROM tbl_kichthuoc WHERE id_sanpham = '$id'");

    // Xóa khuyến mãi sản phẩm
    mysqli_query($mysqli, "DELETE FROM tbl_khuyenmai WHERE id_sanpham = '$id'");

    // Có thể thêm nếu dùng: mysqli_query($mysqli, "DELETE FROM tbl_cart_details WHERE id_sanpham = '$id'");

    // Cuối cùng: Xóa sản phẩm
    $sql_xoa = "DELETE FROM tbl_sanpham WHERE id_sanpham='$id'";
    mysqli_query($mysqli, $sql_xoa);

    // Quay lại
    header('Location:/admincf/index.php?action=quanlysp&query=them');
}

?>