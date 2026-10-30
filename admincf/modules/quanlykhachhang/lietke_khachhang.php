<?php
$sql_khachhang = "SELECT * FROM tbl_dangky ORDER BY id_dangky DESC";
$query_khachhang = mysqli_query($mysqli, $sql_khachhang);
?>

<h1>Danh sách khách hàng </h1>
<!-- Liệt kê sản phẩm -->
<table style="width:100%" border="1" style="border-collapse:collapse;">
    <tr>
        <th>Id</th>
        <th>Tên khách hàng</th>
        <th>Email</th>
        <th>Địa chỉ</th>
        <th>Điện thoại</th>
        <th>Tổng đơn hàng</th>
        <th>Tổng tiền đã chi</th>
        <th>Bình luận</th>
        <th>Quản lý</th>

    </tr>
    <?php
    while ($row = mysqli_fetch_array($query_khachhang)) {
        $id_khachhang = $row['id_dangky'];

        // Đếm số đơn hàng
        $sql_donhang = "SELECT code_cart FROM tbl_cart WHERE id_khachhang = $id_khachhang";
        $lay_donhang = mysqli_query($mysqli, $sql_donhang);
        $donhang_count = mysqli_num_rows($lay_donhang);

        // Tính tổng tiền đã chi
        $tongtien = 0;
        while ($row_don = mysqli_fetch_array($lay_donhang)) {
            $code_cart = $row_don['code_cart'];

            $sql_chitiet = "SELECT sp.giasp, ct.soluongmua FROM tbl_cart_details AS ct
                        JOIN tbl_sanpham AS sp ON ct.id_sanpham = sp.id_sanpham
                        WHERE ct.code_cart ='$code_cart'";

            $lay_chitiet = mysqli_query($mysqli, $sql_chitiet);

            while ($row_ct = mysqli_fetch_array($lay_chitiet)) {
                $tongtien += $row_ct['giasp'] * $row_ct['soluongmua'];
            }
        }

        // Đếm số bình luận
        $sql_binhluan = "SELECT COUNT(*) AS tong_bl FROM tbl_binhluan WHERE id_dangky = $id_khachhang";
        $lay_binhluan = mysqli_query($mysqli, $sql_binhluan);
        $binhluan = mysqli_fetch_assoc($lay_binhluan)['tong_bl'];
    ?>



        <tr>
             <td><?php echo $row['id_dangky']; ?></td>
        <td><?php echo $row['tenkhachhang']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['diachi']; ?></td>
        <td><?php echo $row['dienthoai']; ?></td>
        <td style="text-align: center;"><?php echo $donhang_count; ?></td>
        <td style="text-align: right;"><?php echo number_format($tongtien, 0, ',', '.') . ' đ'; ?></td>
        <td style="text-align: center;"><?php echo $binhluan; ?></td>
        <td>
            <a href="modules/quanlykhachhang/xuly.php?id=<?php echo $row['id_dangky']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?')" title="Xóa"><i class="fas fa-trash-alt"></i></a>
        </td>

          

        </tr>
    <?php
    }
    ?>
</table>
<style>
    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

table {
  width: 100%;
  border-collapse: collapse;
  background-color: #fff;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
  overflow: hidden;
}
td {
  padding: 20px;
  border-bottom: 1px solid #eee;
  text-align: center;
  color: #333;
  font-size: 15px;
}


   th {
  background: linear-gradient(to right, #3498db, #2980b9);
  color: white;
  padding: 14px 10px;
  text-transform: uppercase;
  font-size: 14px;
  border-bottom: 2px solid #ddd;
}


    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #e6f7ff;
    }

    td:last-child a {
        background-color: #ff4d4d;
        color: white;
        padding: 6px 10px;
        text-decoration: none;
        border-radius: 4px;
        font-weight: bold;
    }

    td:last-child a:hover {
        background-color: #e60000;
    }
</style>
