<?php

$code = $_GET['code'];


$sql_lietke_dh = "SELECT * FROM tbl_cart_details 
JOIN tbl_sanpham ON tbl_cart_details.id_sanpham = tbl_sanpham.id_sanpham 
WHERE tbl_cart_details.code_cart='" . $code . "' 
ORDER BY tbl_cart_details.id_cart_details ASC";

$query_lietke_dh = mysqli_query($mysqli, $sql_lietke_dh);

// Kiểm tra kết nối cơ sở dữ liệu

if (!$mysqli) {

  echo "Lỗi kết nối cơ sở dữ liệu: " . mysqli_connect_error();

  exit();
}

// Kiểm tra truy vấn SQL

if ($query_lietke_dh === false) {

  echo "Lỗi truy vấn SQL: " . mysqli_error($mysqli);

  exit();
}

// Kiểm tra số lượng kết quả trả về

$num_rows = mysqli_num_rows($query_lietke_dh);

if ($num_rows == 0) {

  echo "Không tìm thấy đơn hàng với mã: " . $code;

  exit();
}
// Lấy phí vận chuyển từ bảng shipping
$sql_shipping = "SELECT s.shipping_fee 
                 FROM tbl_cart AS c 
                 JOIN tbl_shipping AS s ON c.cart_shipping = s.id_shipping 
                 WHERE c.code_cart = '$code' 
                 LIMIT 1";
$query_shipping = mysqli_query($mysqli, $sql_shipping);
$row_shipping = mysqli_fetch_array($query_shipping);
$shipping_fee = isset($row_shipping['shipping_fee']) ? (int)$row_shipping['shipping_fee'] : 0;



?>

<p class="chitietdh">Chi tiết đơn hàng</p>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Mã đơn hàng</th>
      <th>Tên sản phẩm</th>
      <th>Số lượng</th>
      <th>Đơn giá</th>
      <th>Thành tiền</th>
      <th>Số Seri</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $i = 0;
    $tongtien = 0;
    while ($row = mysqli_fetch_array($query_lietke_dh)) {
      $i++;
      $thanhtien = $row['giasp'] * $row['soluongmua'];
      $tongtien += $thanhtien;

      // Nếu là vợt cầu lông thì lấy danh sách seri
      $ds_seri = "";
      if (stripos($row['tensanpham'], "vợt cầu lông") !== false) {
        $id_sanpham = $row['id_sanpham'];
        $sql_seri = "SELECT sr.seri 
                       FROM tbl_serial_racket AS sr 
                       JOIN tbl_seri AS s ON sr.seri = s.seri 
                       WHERE sr.code_cart = '$code' 
                         AND s.id_sanpham = '$id_sanpham'";
        $query_seri = mysqli_query($mysqli, $sql_seri);
        while ($seri = mysqli_fetch_array($query_seri)) {
          $ds_seri .= $seri['seri'] . "<br>";
        }
      }
    ?>
      <tr>
        <td><?php echo $i ?></td>
        <td><?php echo $row['code_cart'] ?></td>
        <td><?php echo $row['tensanpham'] ?></td>
        <td><?php echo $row['soluongmua'] ?></td>
        <td><?php echo number_format($row['giasp'], 0, ',', '.') . 'đ' ?></td>
        <td><?php echo number_format($thanhtien, 0, ',', '.') . 'đ' ?></td>
        <td><?php echo $ds_seri ?: '—'; ?></td>
      </tr>
    <?php }
    // Cộng phí vận chuyển vào tổng tiền
    $tongtien += $shipping_fee;
    ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="6" style="text-align:right; font-weight:bold;">Phí vận chuyển:</td>
      <td><?php echo number_format($shipping_fee, 0, ',', '.') . 'đ'; ?></td>
    </tr>
    <tr>
      <td colspan="6" style="text-align:right; font-weight:bold;">Tổng cộng:</td>
      <td><strong><?php echo number_format($tongtien, 0, ',', '.') . 'đ'; ?></strong></td>
    </tr>
    <tr>
      <td colspan="6">
        <form method="POST" action="/admincf/modules/quanlydathang/xuly.php" class="update-form">
          <input type="hidden" name="code_cart" value="<?php echo $_GET['code']; ?>">
          <label for="tinhtrangdonhang"><strong>Cập nhật tình trạng đơn hàng:</strong></label>
          <select name="tinhtrangdonhang" id="tinhtrangdonhang" class="form-control">
            <option value="1">Chờ xác nhận</option>
            <option value="2">Đang giao hàng</option>
            <option value="3">Đã giao hàng thành công</option>
          </select>
          <input type="submit" name="update_cart" value="Cập nhật " class="btn-update">
        </form>
      </td>
    </tr>
  </tfoot>
</table>

<style>
  .chitietdh {
    font-size: 24px;
    font-weight: bold;
    text-align: center;
    color: #2c3e50;
    margin: 30px 0 20px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
  }

  thead th {
    background: linear-gradient(to right, #3498db, #2980b9);
    color: white;
    padding: 14px;
    text-transform: uppercase;
    font-size: 14px;
    border-bottom: 2px solid #ddd;
  }

  tbody td,
  tfoot td {
    padding: 12px 14px;
    text-align: center;
    border-bottom: 1px solid #eee;
    font-size: 14px;
    color: #333;
  }

  tfoot tr:last-child td {
    border-bottom: none;
  }

  .update-form {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    align-items: center;
    padding: 10px 0;
  }

  .update-form select {
    padding: 6px 10px;
    font-size: 14px;
    border-radius: 5px;
    border: 1px solid #ccc;
    min-width: 150px;
  }

  .btn-update {
    background-color: #164863;
    color: white;
    padding: 8px 14px;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .btn-update:hover {
    background-color: #219150;
    transform: translateY(-2px);
  }

  /* cột seri */
  td:nth-child(7) {
    font-size: 13px;
    color: #1a5276;
    line-height: 1.4;
  }
</style>