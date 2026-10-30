<?php
$code = $_GET['code'];

$sql_lietke_dh = "SELECT * FROM tbl_cart_details, tbl_sanpham 
                  WHERE tbl_cart_details.id_sanpham = tbl_sanpham.id_sanpham 
                  AND tbl_cart_details.code_cart = '" . $code . "' 
                  ORDER BY tbl_cart_details.id_cart_details ASC";

$query_lietke_dh = mysqli_query($mysqli, $sql_lietke_dh);

// Kiểm tra kết nối và truy vấn
if (!$mysqli || $query_lietke_dh === false) {
    echo "Lỗi truy vấn hoặc kết nối CSDL.";
    exit();
}

if (mysqli_num_rows($query_lietke_dh) == 0) {
    echo "Không tìm thấy đơn hàng với mã: " . $code;
    exit();
}

// Kiểm tra có sản phẩm là vợt
$has_racket = false;
foreach ($query_lietke_dh as $check) {
    if ($check['id_danhmuc'] == 1) {
        $has_racket = true;
        break;
    }
}
mysqli_data_seek($query_lietke_dh, 0);
?>

<body class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-6xl bg-white p-10 rounded-lg shadow-2xl">
        <h1 class="text-2xl font-bold mb-8 text-center text-gray-800">Chi tiết đơn hàng</h1>

        <table class="w-full border-collapse" border="1">
            <thead>
                <tr class="bg-gray-100 text-gray-800">
                    <th class="border p-2 text-center">ID</th>
                    <th class="border p-2 text-center">Mã đơn</th>
                    <th class="border p-2 text-center">Tên sản phẩm</th>
                    <?php if ($has_racket): ?>
                        <th class="border p-2 text-center">Số seri</th>
                    <?php endif; ?>
                    <th class="border p-2 text-center">Số lượng</th>
                    <th class="border p-2 text-center">Đơn giá</th>
                    <th class="border p-2 text-center">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                $subtotal = 0;
                mysqli_data_seek($query_lietke_dh, 0);
                while ($row = mysqli_fetch_array($query_lietke_dh)) {
                    $i++;
                    $thanhtien = $row['giasp'] * $row['soluongmua'];
                    $subtotal += $thanhtien;
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border p-2 text-center"><?php echo $i ?></td>
                        <td class="border p-2 text-center"><?php echo $row['code_cart'] ?></td>
                        <td class="border p-2 text-center"><?php echo $row['tensanpham'] ?></td>
                        <?php if ($has_racket): ?>
                            <?php if ($row['id_danhmuc'] == 1): ?>
                                <?php
                                $seri_result = mysqli_query($mysqli, "SELECT seri FROM tbl_serial_racket 
                                    WHERE code_cart = '{$row['code_cart']}' AND id_sanpham = {$row['id_sanpham']}");
                                $seri_arr = [];
                                while ($s = mysqli_fetch_assoc($seri_result)) {
                                    $seri_arr[] = $s['seri'];
                                }
                                ?>
                                <td class="border p-2 text-center"><?php echo implode(', ', $seri_arr); ?></td>
                            <?php else: ?>
                                <td class="border p-2 text-center">-</td>
                            <?php endif; ?>
                        <?php endif; ?>
                        <td class="border p-2 text-center"><?php echo $row['soluongmua'] ?></td>
                        <td class="border p-2 text-center"><?php echo number_format($row['giasp'], 0, ',', '.') ?>đ</td>
                        <td class="border p-2 text-center"><?php echo number_format($thanhtien, 0, ',', '.') ?>đ</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php
        // Lấy phí vận chuyển
        $sql_fee = "SELECT s.shipping_fee 
                    FROM tbl_cart AS c 
                    JOIN tbl_shipping AS s ON c.cart_shipping = s.id_shipping 
                    WHERE c.code_cart = '$code' LIMIT 1";
        $result_fee = mysqli_query($mysqli, $sql_fee);
        $shipping_fee = 0;
        if ($result_fee && mysqli_num_rows($result_fee)) {
            $shipping_row = mysqli_fetch_assoc($result_fee);
            $shipping_fee = (int)$shipping_row['shipping_fee'];
        }

        $total = $subtotal + $shipping_fee;
        ?>

        <div class="mt-5 text-right p-4 bg-gray-100 rounded-md space-y-1">
            <div><strong>Tạm tính:</strong> <?php echo number_format($subtotal, 0, ',', '.') ?>đ</div>
            <div><strong>Phí vận chuyển:</strong> <?php echo number_format($shipping_fee, 0, ',', '.') ?>đ</div>
            <div class="text-lg font-bold"><strong>Tổng thanh toán:</strong> <?php echo number_format($total, 0, ',', '.') ?>đ</div>
        </div>

        <!-- ========== BẢO HÀNH ========== -->
        <?php
        $sql_baohanh = "
        SELECT sp.id_sanpham, sp.tensanpham, sp.masp, sp.baohanh, c.cart_date, cd.soluongmua, 
               c.code_cart, dm.tendanhmuc, dm.id_danhmuc
        FROM tbl_cart_details AS cd
        JOIN tbl_cart AS c ON cd.code_cart = c.code_cart
        JOIN tbl_sanpham AS sp ON cd.id_sanpham = sp.id_sanpham
        JOIN tbl_danhmuc AS dm ON sp.id_danhmuc = dm.id_danhmuc
        WHERE cd.code_cart = '$code'
        ";
        $query_baohanh = mysqli_query($mysqli, $sql_baohanh);

        $rows_baohanh = [];
        $has_racket = false;
        while ($row = mysqli_fetch_assoc($query_baohanh)) {
            $rows_baohanh[] = $row;
            if ($row['id_danhmuc'] == 1) $has_racket = true;
        }

        if ($has_racket):
        ?>
            <h2 class="text-2xl font-bold mt-10 mb-4 text-center text-gray-800">Tình trạng bảo hành sản phẩm</h2>
            <table class="w-full border border-collapse text-center text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border p-2">Tên sản phẩm</th>
                        <th class="border p-2">Danh mục</th>
                        <th class="border p-2">Số Seri</th>
                        <th class="border p-2">Ngày mua</th>
                        <th class="border p-2">Thời gian BH</th>
                        <th class="border p-2">Ngày hết BH</th>
                        <th class="border p-2">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows_baohanh as $row):
                        $ngay_mua = date_create($row['cart_date']);
                        $ngay_het_bh = clone $ngay_mua;
                        date_add($ngay_het_bh, date_interval_create_from_date_string("{$row['baohanh']} months"));
                        $status = (new DateTime() <= $ngay_het_bh) 
                            ? "<span class='text-green-600 font-medium'>Còn bảo hành</span>" 
                            : "<span class='text-red-600 font-medium'>Hết bảo hành</span>";

                        $seri_arr = [];
                        if ($row['id_danhmuc'] == 1) {
                            $seri_sql = "SELECT sr.seri 
                                         FROM tbl_serial_racket AS sr 
                                         JOIN tbl_seri AS s ON sr.seri = s.seri 
                                         WHERE sr.code_cart = '{$row['code_cart']}' 
                                         AND s.id_sanpham = {$row['id_sanpham']}";
                            $seri_query = mysqli_query($mysqli, $seri_sql);
                            while ($s = mysqli_fetch_assoc($seri_query)) {
                                $seri_arr[] = $s['seri'];
                            }
                        }
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="border p-2"><?php echo $row['tensanpham'] ?></td>
                            <td class="border p-2"><?php echo $row['tendanhmuc'] ?></td>
                            <td class="border p-2"><?php echo implode(', ', $seri_arr) ?></td>
                            <td class="border p-2"><?php echo date_format($ngay_mua, 'd/m/Y') ?></td>
                            <td class="border p-2"><?php echo $row['baohanh'] ?> tháng</td>
                            <td class="border p-2"><?php echo date_format($ngay_het_bh, 'd/m/Y') ?></td>
                            <td class="border p-2"><?php echo $status ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
