<?php
$id_danhmuc = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 1000000000;

// Lấy sản phẩm theo danh mục và lọc giá
$sql_pro = "SELECT * FROM tbl_sanpham WHERE id_danhmuc = ? AND giasp BETWEEN ? AND ? ORDER BY id_sanpham DESC";
$stmt_pro = $mysqli->prepare($sql_pro);
$stmt_pro->bind_param("iii", $id_danhmuc, $min_price, $max_price);
$stmt_pro->execute();
$query_pro = $stmt_pro->get_result();

// Lấy thông tin danh mục
$sql_cate = "SELECT * FROM tbl_danhmuc WHERE id_danhmuc = ? LIMIT 1";
$stmt_cate = $mysqli->prepare($sql_cate);
$stmt_cate->bind_param("i", $id_danhmuc);
$stmt_cate->execute();
$query_cate = $stmt_cate->get_result();
$row_title = $query_cate->fetch_array();
?>

<body class="bg-gray-50">
<div class="container mx-auto px-4 py-6">

    <!-- Tiêu đề -->
    <h3 class="text-2xl font-semibold text-orange-600 mb-4">
        Danh mục: <?php echo htmlspecialchars($row_title['tendanhmuc']); ?>
    </h3>

    <!-- Form lọc -->
    <form method="GET" action="index.php" class="flex flex-wrap items-end gap-4 mb-6">
        <input type="hidden" name="quanly" value="danhmucsanpham">
        <input type="hidden" name="id" value="<?php echo $id_danhmuc; ?>">

        <div>
            <label for="min_price" class="block text-sm text-gray-600">Giá từ</label>
            <input type="number" name="min_price" id="min_price" min="0" step="1000"
                value="<?php echo $min_price; ?>"
                class="w-28 border rounded px-3 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-orange-400">
        </div>

        <div>
            <label for="max_price" class="block text-sm text-gray-600">Giá đến</label>
            <input type="number" name="max_price" id="max_price" min="0" step="1000"
                value="<?php echo $max_price < 1000000000 ? $max_price : ''; ?>"
                class="w-28 border rounded px-3 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-orange-400">
        </div>

        <div>
            <button type="submit"
                class="bg-orange-500 text-white px-4 py-1.5 rounded text-sm hover:bg-orange-600 transition">
                Lọc giá
            </button>
        </div>
    </form>
<div class="row">
    <div class="list_product1">
        
        <?php while ($row = mysqli_fetch_array($query_pro)) { 
             // Kiểm tra nếu có khuyến mãi áp dụng
      $id_sanpham = $row['id_sanpham'];
      $today = date('Y-m-d');
      $sql_km = "SELECT * FROM tbl_khuyenmai 
           WHERE id_sanpham = '$id_sanpham' 
           AND '$today' BETWEEN ngaybatdau AND ngayketthuc 
           LIMIT 1";
      $query_km = mysqli_query($mysqli, $sql_km);
      $km = mysqli_fetch_array($query_km);
      $has_discount = $km ? true : false;

      // Tính giá khuyến mãi nếu có
      if ($has_discount) {
        $phantramgiam = $km['phantramgiam'];
        $giagoc = $row['giasp'];
        $giakm = $giagoc - ($giagoc * $phantramgiam / 100);
      }
?>
            
            <div class="col-md-2"> 
                <div class="item1">
                    <div class="product_top1">
                        <div class="add_like1">
                            <a class="like_product1">
                                <button onclick="yeuthich(<?php echo $row['id_sanpham'] ?>)" class='bx bx-sm bx-heart-circle'></button>
                            </a>
                        </div>
                        <a href="index.php?quanly=sanpham&id=<?php echo $row['id_sanpham']; ?>">
                            <div class="img_item1">
                                <img src="<?php echo 'admincf/modules/quanlysp/uploads/' . htmlspecialchars($row['hinhanh']); ?>">
                            </div>
                        </a>  
                        <a class="buynow1"><i class='bx bx-cart-add'></i> Thêm vào giỏ hàng</a> 
                    </div>
                    <div class="infor1">
                        <div class="name_product1">
                            <p><?php echo htmlspecialchars($row['tensanpham']); ?></p>
                        </div>
                          <?php if ($has_discount): ?>
              <p class="price1 text-red-600 font-bold">
                <?php echo number_format($giakm, 0, ',', '.') . ' vnd'; ?>
              </p>
              <p class="text-gray-500 text-sm line-through">
                <?php echo number_format($row['giasp'], 0, ',', '.') . ' vnd'; ?>
              </p>
              <p class="text-green-600 text-xs italic">
                🔥 Giảm <?php echo $phantramgiam; ?>% - <?php echo htmlspecialchars($km['tenkm']); ?>
              </p>
            <?php else: ?>
              <p class="price1"><?php echo htmlspecialchars(number_format($row['giasp'], 0, ',', '.') . ' vnd'); ?></p>
            <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
</body>

<style>
.footer {
    border: 1px solid #060606;
    height: auto;
    width: 100%;
    margin-top: 1px;
}
</style>
