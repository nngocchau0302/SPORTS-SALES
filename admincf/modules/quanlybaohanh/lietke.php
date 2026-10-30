<?php
$search_code = isset($_POST['search_code']) ? mysqli_real_escape_string($mysqli, $_POST['search_code']) : '';
$search_date = isset($_POST['search_date']) ? mysqli_real_escape_string($mysqli, $_POST['search_date']) : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';

// Truy vấn lấy danh sách sản phẩm vợt cầu lông có bảo hành
$sql_baohanh = "SELECT 
    sp.tensanpham, 
    sp.masp,
    sp.baohanh, 
    c.cart_date, 
    cd.soluongmua, 
    c.code_cart,
    sp.id_sanpham,
    dm.tendanhmuc
FROM tbl_cart_details AS cd
JOIN tbl_cart AS c ON cd.code_cart = c.code_cart
JOIN tbl_sanpham AS sp ON cd.id_sanpham = sp.id_sanpham
JOIN tbl_danhmuc AS dm ON sp.id_danhmuc = dm.id_danhmuc
WHERE sp.baohanh > 0 AND dm.id_danhmuc = 1"; // Chỉ lấy vợt cầu lông có bảo hành

if ($search_code) {
    $sql_baohanh .= " AND c.code_cart = '$search_code'";
}
if ($search_date) {
    $sql_baohanh .= " AND DATE(c.cart_date) = '$search_date'";
}
if ($status !== '') {
    $today = date('Y-m-d');
    if ($status == '1') {
        $sql_baohanh .= " AND DATE_ADD(c.cart_date, INTERVAL sp.baohanh MONTH) >= '$today'";
    } else {
        $sql_baohanh .= " AND DATE_ADD(c.cart_date, INTERVAL sp.baohanh MONTH) < '$today'";
    }
}

$sql_baohanh .= " ORDER BY c.cart_date DESC";

$query_baohanh = mysqli_query($mysqli, $sql_baohanh);
?>

<!-- Form tìm kiếm -->
<p class="chitietdh">Quản lý bảo hành vợt cầu lông</p>

<div class="search-container">
    <form method="POST" action="" class="search-form">
        <div class="form-group">
            <input type="text" name="search_code" placeholder="Nhập mã đơn hàng" class="form-input" />
            <input type="date" name="search_date" class="form-input" />
            <select name="status" class="form-input">
                <option value="">Trạng thái</option>
                <option value="1">Còn bảo hành</option>
                <option value="0">Hết bảo hành</option>
            </select>
            <button type="submit" class="search-button">Tìm kiếm</button>
        </div>
    </form>
</div>

<!-- Bảng hiển thị -->
<table>
  <thead>
    <tr>
      <th>Mã đơn hàng</th>
      <th>Tên sản phẩm</th>
      <th>Mã sản phẩm</th>
      <th>Số seri</th>
      <th>Số lượng</th>
      <th>Ngày đặt</th>
      <th>Bảo hành</th>
      <th>Ngày hết hạn</th>
      <th>Trạng thái</th>
      
    </tr>
  </thead>
  <tbody>
    <?php
    while ($row = mysqli_fetch_array($query_baohanh)) {
        $ngaydat = new DateTime($row['cart_date']);
        $ngayhethan = clone $ngaydat;
        $ngayhethan->modify('+' . $row['baohanh'] . ' months');
        $today = new DateTime();

        $trangthai = ($today <= $ngayhethan) 
            ? '<span style="color:green;font-weight:bold;">Còn bảo hành</span>' 
            : '<span style="color:red;font-weight:bold;">Hết bảo hành</span>';

        // Truy vấn danh sách seri
        $seri_sql = "SELECT sr.seri 
                     FROM tbl_serial_racket AS sr 
                     JOIN tbl_seri AS s ON sr.seri = s.seri 
                     WHERE sr.code_cart = '{$row['code_cart']}' AND s.id_sanpham = {$row['id_sanpham']}";
        $seri_query = mysqli_query($mysqli, $seri_sql);
        $seri_list = [];
        while ($seri_row = mysqli_fetch_assoc($seri_query)) {
            $seri_list[] = $seri_row['seri'];
        }
        $seri_str = !empty($seri_list) ? implode(', ', $seri_list) : '-';
    ?>
      <tr>
        <td><?php echo $row['code_cart'] ?></td>
        <td><?php echo $row['tensanpham'] ?></td>
        <td><?php echo $row['masp'] ?></td>
        <td><?php echo $seri_str ?></td>
        <td><?php echo $row['soluongmua'] ?></td>
        <td><?php echo date('d/m/Y', strtotime($row['cart_date'])) ?></td>
        <td><?php echo $row['baohanh'] ?> tháng</td>
        <td><?php echo $ngayhethan->format('d/m/Y') ?></td>
        <td><?php echo $trangthai ?></td>
        
      </tr>
    <?php } ?>
  </tbody>
</table>


<style>
    .chitietdh {
  font-size: 28px;
  font-weight: bold;
  text-align: center;
  color: #2c3e50;
  margin: 30px 0 20px;
}
table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}
thead th, tbody td {
  padding: 12px;
  border: 1px solid #eee;
  text-align: center;
}
thead th {
  background-color: #3498db;
  color: white;
  text-transform: uppercase;
}
tr:hover {
  background-color: #f1f1f1;
}


/*  form */
.search-container {
    display: flex;
    justify-content: flex-end; /* Đặt vào phía bên phải */
    margin: 20px 0;
}

.search-form {
    display: flex;
    gap: 10px;
}

.form-input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 150px; /* Có thể điều chỉnh kích thước */
}

.search-button {
    padding: 10px 15px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.search-button:hover {
    background-color: #2980b9;
}