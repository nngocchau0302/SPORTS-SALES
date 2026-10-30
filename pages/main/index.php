<!--?php
if(isset($_GET['trang'])){
  $page = $_GET['trang'];
}
else{
  $page = '';
}
if($page == '' || $page == 1){
  $begin = 0;
}else{
  $begin = ($page*10)-10;
}
$sql_pro = "SELECT * FROM tbl_sanpham, tbl_danhmuc WHERE tbl_sanpham.id_danhmuc=tbl_danhmuc.id_danhmuc ORDER BY tbl_sanpham.id_sanpham DESC LIMIT $begin,10";
$query_pro = mysqli_query($mysqli, $sql_pro);

?-->
<?php
// Xử lý phân trang
if (isset($_GET['trang'])) {
  $page = $_GET['trang'];
} else {
  $page = 1;
}
$limit = 15;
$begin = ($page - 1) * $limit;

// Xử lý lọc giá
$min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 1000000000;

// Lấy dữ liệu sản phẩm theo điều kiện
$sql_pro = "SELECT * FROM tbl_sanpham 
            JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc 
            WHERE tbl_sanpham.giasp BETWEEN $min_price AND $max_price 
            ORDER BY tbl_sanpham.id_sanpham DESC 
            LIMIT $begin, $limit";
$query_pro = mysqli_query($mysqli, $sql_pro);
?>



<div class="row">
  <div class="flex justify-around py-4 bg-white shadow-md" style="margin-bottom: 20px;">
    <div class="text-center">
      <i class="fas fa-truck text-2xl text-red-500">
      </i>
      <p class="text-sm">
        Vận chuyển TOÀN QUỐC
      </p>
      <p class="text-xs">
        Thanh toán khi nhận hàng
      </p>
    </div>
    <div class="text-center">
      <i class="fas fa-shield-alt text-2xl text-red-500">
      </i>
      <p class="text-sm">
        Bảo đảm chất lượng
      </p>
      <p class="text-xs">
        Sản phẩm bảo đảm chất lượng
      </p>
    </div>
    <div class="text-center">
      <i class="fas fa-credit-card text-2xl text-red-500">
      </i>
      <p class="text-sm">
        Tiến hành THANH TOÁN
      </p>
      <p class="text-xs">
        Với nhiều PHƯƠNG THỨC
      </p>
    </div>
    <div class="text-center">
      <i class="fas fa-exchange-alt text-2xl text-red-500">
      </i>
      <p class="text-sm">
        Đổi sản phẩm mới
      </p>
      <p class="text-xs">
        nếu sản phẩm lỗi
      </p>
    </div>
  </div>
  <h1 class="text-center text-orange-500 text-3xl font-semibold mb-4">
    Tất cả sản phẩm
  </h1>
  <!--  Form lọc giá -->
  <form method="GET" action="index.php" class="max-w-sm mx-auto bg-white p-1 rounded-lg shadow-md flex items-end space-x-3 mb-8">
    <input type="hidden" name="quanly" value="tatcasanpham" />
    <div class="flex flex-col w-28">
      <label for="min_price" class="text-gray-600 text-xs font-semibold mb-1">Giá từ</label>
      <input
        id="min_price"
        type="number"
        name="min_price"
        min="0"
        step="100000"
        value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : '' ?>"
        placeholder="0"
        class="w-full border border-gray-300 rounded-md py-2 px-2 text-right text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" />
    </div>
    <span class="text-gray-500 font-semibold select-none self-center">-</span>
    <div class="flex flex-col w-28">
      <label for="max_price" class="text-gray-600 text-xs font-semibold mb-1">Đến</label>
      <input
        id="max_price"
        type="number"
        name="max_price"
        min="0"
        step="100000"
        value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '' ?>"
        placeholder="0"
        class="w-full border border-gray-300 rounded-md py-2 px-2 text-right text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" />
    </div>
    <button
      type="submit"
      class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-5 rounded-md transition-colors duration-200 shadow-md"
      aria-label="Lọc giá">
      Lọc
    </button>
  </form>


  <div class="list_product1">

    <?php
    while ($row = mysqli_fetch_array($query_pro)) {

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


              <a class="like_product1"><button onclick="yeuthich(<?php echo $row['id_sanpham'] ?>)" class='bx bx-sm bx-heart-circle'></button></a>
            </div>
            <a href="index.php?quanly=sanpham&id=<?php echo $row['id_sanpham']; ?>">
              <div class="img_item1" style="position: relative;">
                <?php if ($has_discount): ?>
                  <span class="sale-badge"><?php echo '-' . $phantramgiam . '%'; ?></span>
                <?php endif; ?>
                <img src="<?php echo 'admincf/modules/quanlysp/uploads/' . htmlspecialchars($row['hinhanh']); ?>">
              </div>

            </a>
            <a class="buynow1"><i class='bx bx-cart-add'></i> Thêm vào giỏ hàng</a>
            <!-- <a class="buynow1" ><button onclick="xemnhanh" data-toggle="modal" data-target="#xemnhanh" class="fa-regular fa-eye fa-xs" style="color: #74C0FC;"></button>Xem nhanh </a>  -->
          </div>
          <div class="infor1">
            <div class="name_product1">
              <p>

                <?php echo htmlspecialchars($row['tensanpham']); ?>

              </p>
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
    <?php
    }
    ?>

  </div>



  <!-- Phân trang -->
  <?php
  // Đếm tổng sản phẩm theo điều kiện lọc
  $sql_trang = mysqli_query($mysqli, "SELECT * FROM tbl_sanpham WHERE giasp BETWEEN $min_price AND $max_price");
  $row_count = mysqli_num_rows($sql_trang);
  $trang = ceil($row_count / $limit);
  ?>

  <div style="clear:both;">
    <ul class="list_trang">
      <?php for ($i = 1; $i <= $trang; $i++) { ?>
        <li <?php if ($i == $page) echo 'style="background:#9BBEC8"'; ?>>
          <a href="index.php?trang=<?php echo $i ?>
                    <?php
                    if (isset($_GET['min_price'])) echo '&min_price=' . $_GET['min_price'];
                    if (isset($_GET['max_price'])) echo '&max_price=' . $_GET['max_price'];
                    ?>
                    ">
            <?php echo $i ?>
          </a>
        </li>
      <?php } ?>
    </ul>
  </div>




  <div class="container mx-auto px-4 py-8">
    <h1 class="text-center text-orange-500 text-3xl font-semibold mb-4">
      Tin tức mới
    </h1>
    <div class="flex justify-center mb-8">
      <div class="w-16 h-1 bg-gray-300 rounded-full">
      </div>
      <div class="w-16 h-1 bg-orange-500 rounded-full mx-2">
      </div>
      <div class="w-16 h-1 bg-gray-300 rounded-full">
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <a href="https://shopvnb.com/khai-truong-cua-hang-the-thao-vnb-tan-uyen.html">
          <img alt="Image of a sports store with a cashier counter and sports equipment" class="w-full h-48 object-cover" src="../../images/Giày/khai-truong-cua-hang.webp" />
          <div class="p-4">
            <h2 class="text-lg font-semibold mb-2">
              Tưng Bừng Khai Trương Cửa Hàng Thể Thao VNB Cần Thơ - Với...
            </h2>
            <div class="text-orange-500 text-sm mb-2">
              18-03-2025 17:12
            </div>
            <p class="text-gray-600">
              Nhằm mang đến những sản phẩm và dịch vụ chất lượng nhất cho cộng đồng người yêu thể thao tại TP....
            </p>
          </div>
        </a>
      </div>
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <a href="https://shopvnb.com/san-cau-long-hoang-vy.html">
          <img alt="Image of an indoor badminton court with people playing" class="w-full h-48 object-cover" src="../../images/Giày/san-cau-long-hoang-vy-5-1742240020.webp" />
          <div class="p-4">
            <h2 class="text-lg font-semibold mb-2">
              Khám Phá Sân Cầu Lông Hoàng Vy Chất Lượng Đáng Để Trải Nghi...
            </h2>
            <div class="text-orange-500 text-sm mb-2">
              18-03-2025 09:33
            </div>
            <p class="text-gray-600">
              Sân cầu lông Hoàng Vy với mặt sân đẹp, các dịch vụ tiện ích đầy đủ và được biết đến là một trong những...
            </p>
          </div>
        </a>
      </div>
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <a href="https://shopvnb.com/cac-san-cau-long-can-tho.html">
          <img alt="Image of a green indoor badminton court" class="w-full h-48 object-cover" src="../../images/Giày/review-san-cau-long-quan-12-.webp" />
          <div class="p-4">
            <h2 class="text-lg font-semibold mb-2">
              Danh Sách Các Sân Cầu Lông Cần Thơ Chất Lượng Dành Cho Cá...
            </h2>
            <div class="text-orange-500 text-sm mb-2">
              18-03-2025 09:33
            </div>
            <p class="text-gray-600">
              Khu vực quận 8 phát triển rất mạnh về cầu lông, tại đây các giải đấu phong trào luôn diễn ra liên tụ...
            </p>
          </div>
        </a>
      </div>
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <a href="https://shopvnb.com/shop-cau-long-can-tho-ndash-cua-hang-cau-long-vnb-can-tho-dia-chi-ban-vot-cau-long-uy-tin-tai-can-tho-.html">
          <img alt="Image of a sports store with a cashier counter and sports equipment" class="w-full h-48 object-cover" src="../../images/Giày/shop-cau-long-can-tho-ndash-cua-hang-cau-long-vnb-can-tho-dia-chi-ban-vot-cau-long-uy-tin-tai-can-tho--1-1717616404.webp" />
          <div class="p-4">
            <h2 class="text-lg font-semibold mb-2">
              Tưng Bừng Khai Trương Cửa Hàng Thể Thao VNB An Hòa Ninh Kiều - Với Nhiề...
            </h2>
            <div class="text-orange-500 text-sm mb-2">
              12-03-2025 09:50
            </div>
            <p class="text-gray-600">
              VNB Sports sẽ mang đến một chi nhánh mới tại Cần Thơ với Cửa hàng thể thao VNB An Hòa . ShopVNB An Hòa ...
            </p>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>

<style>
  .price1 {
    font-size: 16px;
  }

  .line-through {
    text-decoration: line-through;
  }

  .sale-badge {
  position: absolute;
  top: 8px;
  left: 8px;
  background-color: #ff3d00;
  color: white;
  padding: 4px 8px;
  font-size: 12px;
  font-weight: bold;
  border-radius: 4px;
  z-index: 10;
  box-shadow: 0 0 4px rgba(0,0,0,0.3);
}
</style>

