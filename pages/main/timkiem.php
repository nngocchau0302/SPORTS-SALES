<?php
$found = false;
$tukhoa_safe = '';
$query_pro = null;
$expanded_keywords = [];

// ======= Các HÀM hỗ trợ =======
// Bỏ dấu tiếng Việt
function convert_name($str)
{
    $unicode = [
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd' => 'đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
        'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'D' => 'Đ',
        'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
        'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
    ];
    foreach ($unicode as $nonUnicode => $uni) {
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    }
    return strtolower($str);
}

// Từ đồng nghĩa
$synonyms = [
    'vợt' => ['vot', 'dụng cụ cầu lông', 'vợt ', 'vot '],
    // 'giày' => ['giay', 'giày thể thao', 'giày'],
    'áo' => ['ao', 'áo thun', 'áo thể thao', 'áo cầu lông', 'ao cau long', 'ao cl'],
    'thấm' => ['thấm mồ hôi', 'thoáng khí', 'thấm mồ hôi tốt'],
    'bền' => ['độ bền cao', 'chắc chắn'],
    'áo cầu lông ' => ['áo'],
    'thấm mồ hôi tốt' => ['thấm', 'thoáng khí', 'hút mồ hôi', 'khô thoáng', 'hút ẩm'],
    'thoáng khí' => ['mát', 'thông thoáng', 'dễ chịu', 'thấm mồ hôi']
];

// Mở rộng từ khóa với từ đồng nghĩa
function expand_keywords($keyword, $synonyms)
{
    $result = [$keyword];
    $keyword_no_sign = convert_name($keyword);
    foreach ($synonyms as $key => $syns) {
        if (strpos($keyword_no_sign, convert_name($key)) !== false) {
            $result = array_merge($result, $syns);
        } else {
            foreach ($syns as $syn) {
                if (strpos($keyword_no_sign, convert_name($syn)) !== false) {
                    $result[] = $key;
                }
            }
        }
    }
    return array_unique($result);
}

// ============ XỬ LÝ TỪ KHÓA ============
if (isset($_POST['timkiem'])) {
    $tukhoa = trim($_POST['tukhoa']);
    $tukhoa_safe = htmlspecialchars($tukhoa);
    $tukhoa_no_sign = convert_name($tukhoa);
    $expanded_keywords = expand_keywords($tukhoa, $synonyms);

    $sql = "SELECT * FROM tbl_sanpham 
            INNER JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc";
    $query = mysqli_query($mysqli, $sql);
    $result_filtered = [];

   
    $contains_ao = false;
    foreach ($expanded_keywords as $kw) {
        if (strpos(convert_name($kw), 'ao') !== false || strpos(convert_name($kw), 'áo') !== false) {
            $contains_ao = true;
            break;
        }
    }

    while ($row = mysqli_fetch_array($query)) {
        $tensp = convert_name($row['tensanpham']);
        $mota = convert_name($row['mota']);

        $match_name = false;
        $match_desc = false;

        foreach ($expanded_keywords as $kw) {
            $kw_no_sign = convert_name($kw);

            if (strpos($tensp, $kw_no_sign) !== false) {
                $match_name = true;
            }

            if (strpos($mota, $kw_no_sign) !== false) {
                $match_desc = true;
            }
        }

        // Nếu từ khóa chứa "áo" thì lọc theo sản phẩm tên chứa "áo"
        if ($contains_ao) {
            if (strpos($tensp, 'ao') !== false || strpos($tensp, 'ao cau long') !== false) {
                $row['match_name'] = $match_name;
                $row['match_desc'] = $match_desc;
                $result_filtered[] = $row;
            }
        } else {
            // Nếu không chứa "áo", cho phép lọc theo tên hoặc mô tả
            if ($match_name || $match_desc) {
                $row['match_name'] = $match_name;
                $row['match_desc'] = $match_desc;
                $result_filtered[] = $row;
            }
        }
    }

    // Sắp xếp ưu tiên tên khớp trước
    usort($result_filtered, function ($a, $b) {
        return $b['match_name'] <=> $a['match_name'];
    });
} else {
    $tukhoa_safe = '';
    $result_filtered = [];
}
?>

<!-- GIAO DIỆN HIỂN THỊ KẾT QUẢ -->
<h2 class="text-2xl font-bold mb-4" style="margin-bottom:20px;">
    Từ khóa tìm kiếm : <?php echo $tukhoa_safe; ?>
</h2>
<div class="row">
    <div class="list_product1">

        <?php
        if (!empty($result_filtered)) {
            foreach ($result_filtered as $row) {
                // ==== BẮT ĐẦU: TÍNH TOÁN KHUYẾN MÃI ====
                $id_sanpham = $row['id_sanpham'];
                $today = date('Y-m-d');
                $sql_km = "SELECT * FROM tbl_khuyenmai 
                   WHERE id_sanpham = '$id_sanpham' 
                   AND '$today' BETWEEN ngaybatdau AND ngayketthuc 
                   LIMIT 1";
                $query_km = mysqli_query($mysqli, $sql_km);
                $km = mysqli_fetch_array($query_km);
                $has_discount = $km ? true : false;

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
                                <a class="like_product1"><button class='bx bx-sm bx-heart-circle'></button></a>
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
                                <p class="price1">
                                    <?php echo htmlspecialchars(number_format($row['giasp'], 0, ',', '.') . ' vnd'); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p>Không tìm thấy sản phẩm nào phù hợp.</p>";
        }
        ?>
    </div>
</div>