<!-- ===== PHẦN XỬ LÝ PHP ===== -->
<?php

include("admincf/config/config.php");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

// ========== XỬ LÝ GỬI BÌNH LUẬN ==========
if (isset($_POST['guibinhluan'])) {
    if (isset($_SESSION['id_khachhang'])) {
        $id_dangky = $_SESSION['id_khachhang'];
        $id_sanpham = $_POST['id_sanpham'];
        $ten = $_SESSION['dangky'];
        $noidung = trim($_POST['noidung']);
        $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;

        if ($noidung === '') {
            echo '<p style="color:red;">⚠️ Nội dung bình luận không được để trống!</p>';
        } else {
            // ====== GỌI API PHÂN TÍCH CẢM XÚC ======
            $api_url = 'http://127.0.0.1:5000/api/sentiment';
            $data = ['comment' => $noidung];

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $api_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                CURLOPT_POSTFIELDS => json_encode($data)
            ]);

            $response = curl_exec($curl);
            $curl_error = curl_error($curl);
            curl_close($curl);

            if ($response === false) {
                // echo "<p style='color:red;'>❌ Lỗi khi gọi API: $curl_error</p>";
            } else {
                $result = json_decode($response, true);
                $sentiment = $result['sentiment'] ?? 'unknown';

                // echo "<pre>🔍 Response: $response\n🧪 Sentiment: $sentiment</pre>";
            }
            // echo "<pre>🧪 Phân tích AI: {$sentiment}</pre>";

            $can_save = true;
             if ($sentiment === 'positive' && $rating <= 2) {
            echo "<script>alert('⚠️ Bạn đang bình luận tích cực nhưng chọn số sao thấp. Hãy kiểm tra lại.');</script>";
            $can_save = false;
        } elseif ($sentiment === 'negative' && $rating >= 4) {
            echo "<script>alert('⚠️ Bạn đang bình luận tiêu cực nhưng lại chọn số sao cao. Hãy kiểm tra lại.');</script>";
            $can_save = false;
        }

            // ====== LƯU BÌNH LUẬN ======
            if ($can_save) {
                $sql_binhluan = "INSERT INTO tbl_binhluan (id_sanpham, ten, id_dangky, noidung, ngaybinhluan, rating, camxuc) 
                                VALUES ('$id_sanpham', '$ten', '$id_dangky', '$noidung', NOW(), '$rating', '$sentiment')";
                if (mysqli_query($mysqli, $sql_binhluan)) {
                    echo '<p style="color:green;">✅ Bình luận đã được gửi!</p>';
                } else {
                    echo '<p style="color:red;">❌ Lỗi: ' . mysqli_error($mysqli) . '</p>';
                }
            }
        }
    } else {
        echo '<p style="color:red;">⚠️ Bạn cần đăng nhập để bình luận.</p>';
    }
}

// Bình luận sản phẩm

// if (isset($_POST['guibinhluan'])) {
//     if (isset($_SESSION['id_khachhang'])) {
//         $id_dangky = $_SESSION['id_khachhang'];
//         $id_sanpham = $_POST['id_sanpham'];
//         $ten = $_SESSION['dangky'];
//         $noidung = trim($_POST['noidung']);
//         $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;

//         // Kiểm tra nội dung đánh giá 
//         $dg_tot = ['đẹp', 'tốt', 'rất tốt', 'hài lòng', 'xứng đáng giá tiền', 'tuyệt vời', 'xuất sắc', 'ưng ý',];
//         $dg_te  = ['tệ', 'không giống ảnh', 'kém chất lượng', 'thất vọng', 'không tốt', 'xấu', 'không hài lòng', 'rất tệ'];


//         $dg_noidung = mb_strtolower($noidung, 'UTF-8');
//         $co_dg_tot = false;
//         $co_dg_te = false;

// //         //Kiểm tra bình luận tốt
// //         foreach ($dg_tot as $tu) {
// //             if (mb_strpos($dg_noidung, $tu) !== false) {
// //                 $co_dg_tot = true;
// //                 break;
// //             }
// //         }

// //         // Kiểm tra bình luận tệ
// //         foreach ($dg_te as $tu) {
// //             if (mb_strpos($dg_noidung, $tu) !== false) {
// //                 $co_dg_te = true;
// //                 break;
// //             }
// //         }


// //         if ($co_dg_tot && $rating < 4) {
// //             echo "<script> alert('Bạn đang đánh giá sản phẩm tốt nhưng lại chọn số sao không phù hợp. Vui lòng đánh giá lại.');</script>";
// //         } else if ($co_dg_te && $rating == 5) {
// //             echo "<script> alert('Bạn đang đánh giá 5 sao nhưng lại có nội dụng bình luận sản phẩm tệ. Vui lòng đánh giá lại.'); </script>";
// //         } else if ($noidung === '') {
// //             echo '<p style="color:red;">Nội dung bình luận không được để trống!</p>';
// //         } else {
// //             $sql_binhluan = "INSERT INTO tbl_binhluan (id_sanpham, ten, id_dangky, noidung, ngaybinhluan, rating) 
// //                              VALUES ('$id_sanpham', '$ten', '$id_dangky', '$noidung', NOW(), '$rating')";
// //             if (mysqli_query($mysqli, $sql_binhluan)) {
// //                 echo '<p style="color:green;">Bình luận đã được gửi!</p>';
// //             } else {
// //                 echo '<p style="color:red;">Lỗi: ' . mysqli_error($mysqli) . '</p>';
// //             }
// //         }
// //     } else {
// //         echo '<p style="color:red;">Bạn cần đăng nhập để bình luận.</p>';

// // Gửi nội dung bình luận đến AI Flask API để phân loại cảm xúc
// $api_url = 'http://127.0.0.1:5000/api/sentiment';
// $data = ['text' => $noidung];

// $curl = curl_init();
// curl_setopt_array($curl, [
//     CURLOPT_URL => $api_url,
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_POST => true,
//     CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
//     CURLOPT_POSTFIELDS => json_encode($data)
// ]);

// $response = curl_exec($curl);
// $curl_error = curl_error($curl);  // Bắt lỗi nếu có
// curl_close($curl);

// // Debug: in phản hồi API
// echo "<pre>🧪 Phản hồi từ AI: \n$response\n</pre>";
// echo "<pre>❌ CURL Error: $curl_error</pre>";

// $result = json_decode($response, true);
// $sentiment = $result['sentiment'] ?? 'unknown';

// // Kiểm tra cảm xúc và đánh giá
// $can_save = true; // Mặc định được lưu

// if ($sentiment === 'positive' && $rating < 4) {
//     echo "<script>alert('⚠️ Bạn đang bình luận tích cực nhưng chọn sao thấp. Vui lòng kiểm tra lại.');</script>";
//     $can_save = false;
// } elseif ($sentiment === 'negative' && $rating >= 4) {
//     echo "<script>alert('⚠️ Bạn đang bình luận tiêu cực nhưng chọn sao cao. Vui lòng kiểm tra lại.');</script>";
//     $can_save = false;
// }


// // Nếu hợp lệ thì lưu
// if ($can_save) {
//     $sql_binhluan = "INSERT INTO tbl_binhluan (id_sanpham, ten, id_dangky, noidung, ngaybinhluan, rating) 
//                      VALUES ('$id_sanpham', '$ten', '$id_dangky', '$noidung', NOW(), '$rating')";
//     if (mysqli_query($mysqli, $sql_binhluan)) {
//         echo '<p style="color:green;">Bình luận đã được gửi!</p>';
//     } else {
//         echo '<p style="color:red;">Lỗi khi lưu bình luận: ' . mysqli_error($mysqli) . '</p>';
//     }
// }


//    }

// }



$sql_chitiet = "SELECT * FROM tbl_sanpham 
                JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
                JOIN tbl_thuonghieu ON tbl_sanpham.id_thuonghieu = tbl_thuonghieu.id_thuonghieu
                WHERE tbl_sanpham.id_sanpham = '$_GET[id]' LIMIT 1";

$query_chitiet = mysqli_query($mysqli, $sql_chitiet);
while ($row_chitiet = mysqli_fetch_array($query_chitiet)) {
    $productType = $row_chitiet['tendanhmuc']; // Giả sử 'ten_danhmuc' là tên cột chứa loại sản phẩm

    // Lấy kích thước sản phẩm từ bảng tbl_kichthuoc
    $id_sanpham = $row_chitiet['id_sanpham'];
    $sql_sizes = "SELECT * FROM tbl_kichthuoc WHERE id_sanpham='$id_sanpham'";
    $query_sizes = mysqli_query($mysqli, $sql_sizes);
    $sizes = [];
    while ($row_size = mysqli_fetch_array($query_sizes)) {
        $sizes[] = $row_size; // Lưu kích thước vào mảng
    }
    // Xác định kích thước dựa trên loại sản phẩm
    // $sizes = [];
    // if ($productType === 'Giày Cầu Lông') {
    //     $sizes = range(38, 42); // Kích thước cho giày
    // } elseif ($productType === 'Áo Cầu Lông' || $productType === 'Quần Cầu Lông' || $productType === 'Váy Cầu Lông') {
    //     $sizes = ['S', 'M', 'L', 'XL']; // Kích thước cho quần áo
    // }// Lấy khuyến mãi áp dụng nếu có
    $khuyenmai = null;
    $today = date('Y-m-d');
    $sql_km = "SELECT * FROM tbl_khuyenmai 
           WHERE id_sanpham = '$id_sanpham' 
           AND '$today' BETWEEN ngaybatdau AND ngayketthuc 
           LIMIT 1";
    $query_km = mysqli_query($mysqli, $sql_km);
    if (mysqli_num_rows($query_km) > 0) {
        $khuyenmai = mysqli_fetch_array($query_km);
    }

?>




    <!-- ======= PHẦN GIAO DIỆN ======== -->

    <body class="bg-gray-100">
        <div class="container mx-auto p-4">
            <form method="POST" action="pages/main/themgiohang.php?idsanpham=<?php echo $row_chitiet['id_sanpham'] ?>">
                <!-- Product Section -->
                <div class="bg-white p-6 rounded-lg shadow-md flex flex-col lg:flex-row">
                    <!-- Product Image and Thumbnails -->
                    <div class="lg:w-1/2 flex flex-col items-center">
                        <img width="90%"
                            src="<?php echo 'admincf/modules/quanlysp/uploads/' . htmlspecialchars($row_chitiet['hinhanh']); ?>">
                    </div>
                    <!-- Product Details -->
                    <div class="lg:w-1/2 lg:pl-6">
                        <h1 class="text-2xl font-bold mb-2">
                            <?php echo $row_chitiet['tensanpham'] ?>
                        </h1><!--?php
                                $product_id = $row_chitiet['id_sanpham'];
                                $sql_start = "SELECT AVG(rating) AS avg_start FROM tbl_danhgiasao WHERE product_id='$product_id'";
                                $query_start = mysqli_query($mysqli, $sql_start);
                                $result_start = mysqli_fetch_array($query_start);
                                echo $start = round($result_start['avg_start']);
                                ?><i class="fa-solid fa-star" style="color: #FFD43B;"></!--i-->
                        <p class="text-gray-600 mb-2">
                            MSP: <?php echo $row_chitiet['masp'] ?> | Thương hiệu:
                            <?php echo $row_chitiet['tenthuonghieu']; ?>
                        </p>

                        <p class="text-gray-600 mb-2">
                            Kho: <?php echo $row_chitiet['soluong'] ?>
                        </p>
                        <?php if ($khuyenmai):
                            $giasp = $row_chitiet['giasp'];
                            $phantram = $khuyenmai['phantramgiam'];
                            $giagiam = $giasp * (1 - $phantram / 100);
                        ?>
                            <p class="text-gray-500 text-lg line-through mb-1">
                                <?php echo number_format($giasp, 0, ',', '.') . ' vnd'; ?>
                            </p>
                            <p class="text-red-500 text-2xl font-bold mb-2">
                                <?php echo number_format($giagiam, 0, ',', '.') . ' vnd'; ?>
                            </p>
                            <p class="text-green-600 text-sm mb-4">
                                🔥 <?php echo htmlspecialchars($khuyenmai['tenkm']); ?> (<?php echo $phantram; ?>% giảm đến
                                <?php echo date('d/m/Y', strtotime($khuyenmai['ngayketthuc'])); ?>)
                            </p>
                        <?php else: ?>
                            <p class="text-red-500 text-2xl font-bold mb-4">
                                <?php echo number_format($row_chitiet['giasp'], 0, ',', '.') . ' vnd' ?>
                            </p>
                        <?php endif; ?>


                        <div class="flex items-center mb-4">
                            <i class="fas fa-gift text-blue-500 mr-2">
                            </i>
                            <span class="text-sm text-gray-600">
                                Giảm đến 50K khi thanh toán qua Momo.
                                <a class="text-blue-500 hover:underline" href="#">
                                    Xem thêm
                                </a>
                            </span>
                        </div>
                        <!-- Kích thước -->
                        <div class="mb-4">
                            <span>Kích thước:</span>
                            <div class="flex space-x-2 mt-2" id="sizeOptions">
                                <?php foreach ($sizes as $size): ?>
                                    <button type="button" class="w-10 h-10 border rounded size-button"
                                        data-size="<?php echo $size['size']; ?>">
                                        <?php echo $size['size']; ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            <input type="hidden" id="selectedSize" name="selected_size" value="">
                        </div>
                        <div class="flex items-center mb-4">

                            <a href=""><i class="fas fa-plus-circle"></i></a>

                            <input class="w-12 text-center border rounded mx-2" type="text" value="1" />

                            <a href=""><i class="fas fa-minus-circle"></i></a>

                        </div>
                        <div class="flex space-x-4 mb-4">



                            <?php
                            if ($row_chitiet['soluong'] > 1) {
                            ?>
                                <div class="nut">
                                    <input type="hidden" name="id_sanpham" value="<?php echo $row_chitiet['id_sanpham'] ?>">
                                    <p><input class="themgiohang " name="themgiohang" type="submit" value="Thêm giỏ hàng"></p>

                                </div>
                            <?php
                            } else {
                            ?>
                                <span style="background-color:bisque;" class="text text-danger">Đã bán hết!!!</span>
                            <?php
                            }
                            ?>
                            <button class="bg-blue-500 text-white px-4 py-2 rounded">
                                Tư vấn qua Zalo
                            </button>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">
                            Sản phẩm chỉ còn số lượng ít! Quý khách vui lòng liên hệ chi nhánh gần nhất trước khi đến cửa
                            hàng. DC xin cảm ơn!
                        </p>
                        <!-- Rating Stars Box
                        <section class='rating-widget'>
                            <style>
                                .rating-stars ul {
                                    list-style-type: none;
                                    padding: 0;
                                }

                                .rating-stars ul>li.star {
                                    display: inline-block;
                                }

                                .rating-stars ul>li.star>i.fa {
                                    font-size: 2.5em;
                                    color: #ccc;
                                }

                                .rating-stars ul>li.star.hover>i.fa {
                                    color: #FFCC36;
                                }

                                .rating-stars ul>li.star.selected>i.fa {
                                    color: #FF912C;
                                }
                            </style>
                            <div class='rating-stars text-center'>
                                <h2 class="text-lg font-bold mb-2">Đánh giá</h2>
                                <ul id='stars'>
                                    <li class='star' title='Poor' data-value='1' data-product_id="<!?php echo $row_chitiet['id_sanpham'] ?>">
                                        <i class='fa fa-star fa-fw'></i>
                                    </li>
                                    <li class='star' title='Fair' data-value='2' data-product_id="<!?php echo $row_chitiet['id_sanpham'] ?>">
                                        <i class='fa fa-star fa-fw'></i>
                                    </li>
                                    <li class='star' title='Good' data-value='3' data-product_id="<!?php echo $row_chitiet['id_sanpham'] ?>">
                                        <i class='fa fa-star fa-fw'></i>
                                    </li>
                                    <li class='star' title='Excellent' data-value='4' data-product_id="<!?php echo $row_chitiet['id_sanpham'] ?>">
                                        <i class='fa fa-star fa-fw'></i>
                                    </li>
                                    <li class='star' title='WOW!!!' data-value='5' data-product_id="<!?php echo $row_chitiet['id_sanpham'] ?>">
                                        <i class='fa fa-star fa-fw'></i>
                                    </li>
                                </ul>
                            </div>
                        </section> -->
                    </div>
                </div>
            </form>
            <!-- Seller Information -->
            <div class="bg-green-100 p-4 rounded-lg shadow-md mt-6">
                <h2 class="text-lg font-bold mb-2">
                    Cam kết bán hàng
                </h2>
                <ul class="text-sm text-gray-600">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-green-500 mr-2">
                        </i>
                        Bảo hành chính hãng theo nhà sản xuất (Trừ các lỗi do người dùng, va đập, vào nước, v.v.)
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-green-500 mr-2">
                        </i>
                        Miễn phí giao hàng trong 2 giờ (Áp dụng cho nội thành TP Cần Thơ. Trừ giờ cao điểm từ 11h-13h,
                        17h-19h)
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-green-500 mr-2">
                        </i>
                        Trả góp 0% lãi suất trong 7 ngày (Nếu có lỗi từ nhà sản xuất)
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-green-500 mr-2">
                        </i>
                        Hỗ trợ giao hàng toàn quốc từ TP.HCM (Khách hàng chịu phí ship của Ahamove, Grab)
                    </li>
                </ul>
            </div>
            <!-- mới -->
            <!-- Nút chuyển tab -->
            <div class="mt-8 mb-4 flex space-x-4 justify-start">
                <button onclick="showTab('description')" id="tab-description-btn"
                    class="tab-button px-5 py-2 rounded-full font-semibold flex items-center gap-2 bg-blue-500 text-white transition-all duration-300">
                    <i class="fas fa-info-circle"></i> Mô tả
                </button>
                <button onclick="showTab('comments')" id="tab-comments-btn"
                    class="tab-button px-5 py-2 rounded-full font-semibold flex items-center gap-2 bg-gray-200 text-gray-700 hover:bg-blue-100 transition-all duration-300">
                    <i class="fas fa-comments"></i> Bình luận
                </button>
            </div>

            <!-- Container chính -->
            <div class="relative w-full overflow-hidden bg-white rounded-xl shadow-md">
                <div id="tab-slider" class="tab-slider">
                    <!-- Mô tả -->
                    <div id="tab-description" class="tab-panel">
                        <h3 class="text-lg font-bold mb-4">Mô tả sản phẩm</h3>
                        <div class="product-description text-gray-600 leading-relaxed">
                            <?php echo $row_chitiet['mota']; ?>
                        </div>
                    </div>

                    <!-- Bình luận -->
                    <div id="tab-comments" class="tab-panel">
                        <!-- TOÀN BỘ phần bình luận bạn đã có -->
                        <!-- Trung bình đánh giá -->
                        <?php
                        $sql_avg_rating = "SELECT AVG(rating) as trungbinh, COUNT(*) as tong FROM tbl_binhluan WHERE id_sanpham = '$id_sanpham' AND trangthai != 'an'";
                        $result_avg = mysqli_query($mysqli, $sql_avg_rating);
                        $row_avg = mysqli_fetch_assoc($result_avg);
                        $avg_rating = round($row_avg['trungbinh'], 1);
                        $tong_rating = $row_avg['tong'];

                        // Hàm timeAgo
                        function timeAgo($datetime)
                        {
                            $time_ago = strtotime($datetime);
                            if (!$time_ago) return "Không rõ thời gian";
                            $current_time = time();
                            $time_difference = $current_time - $time_ago;
                            $seconds = $time_difference;
                            $minutes = round($seconds / 60);
                            $hours = round($seconds / 3600);
                            $days = round($seconds / 86400);
                            $months = round($seconds / 2592000);
                            $years = round($seconds / 31536000);
                            if ($seconds <= 60) return "Vừa xong";
                            elseif ($minutes <= 60) return "$minutes phút trước";
                            elseif ($hours <= 24) return "$hours giờ trước";
                            elseif ($days <= 30) return "$days ngày trước";
                            elseif ($months <= 12) return "$months tháng trước";
                            else return "$years năm trước";
                        }
                        ?>

                        <!-- Hiển thị đánh giá trung bình -->
                        <div class="mb-5">
                            <h4 class="text-lg font-semibold text-yellow-600">
                                ⭐ Đánh giá trung bình: <?php echo $avg_rating; ?>/5 từ <?php echo $tong_rating; ?> lượt đánh
                                giá
                            </h4>
                            <div class="flex text-yellow-500 mt-1 text-xl">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    echo '<i class="fa fa-star' . ($i <= round($avg_rating) ? '' : ' text-gray-300') . '"></i>';
                                }
                                ?>
                            </div>
                        </div>

                        <!-- Hiển thị từng bình luận -->
                        <?php
                        $sql_show = "SELECT * FROM tbl_binhluan WHERE id_sanpham = '$id_sanpham' AND trangthai != 'an' ORDER BY ngaybinhluan DESC";
                        $result_show = mysqli_query($mysqli, $sql_show);

                        if (mysqli_num_rows($result_show) > 0) {
                            while ($row_bl = mysqli_fetch_array($result_show)) {
                                $id_binhluan = $row_bl['id_binhluan'];
                                echo '<div class="comment" style="position: relative; padding-right: 80px;">'; // thêm padding để tránh che icon

                                // Tên người bình luận (trái)
                                echo '<strong><i class="fa fa-user" style="margin-right: 6px; color: #555;"></i>' . htmlspecialchars($row_bl['ten']) . '</strong>';

                                // Thời gian bình luận (góc phải)
                                echo '<span style="position: absolute; top: 0; right: 40px; color: #888; font-size: 13px;">' . timeAgo($row_bl['ngaybinhluan']) . '</span>';

                                // Nút 3 gạch (góc phải nhất)
                                if (isset($_SESSION['id_khachhang'])) {
                                    $menu_id = 'menu-' . $id_binhluan;
                                    echo '<div style="position: absolute; top: 0; right: 0;">';
                                    echo '<button type="button" class="report-button" onclick="toggleMenu(\'' . $menu_id . '\')" 
            style="background: none; border: none; font-size: 18px; cursor: pointer; color: #555;">
            <i class="fas fa-ellipsis-v"></i>
          </button>';

                                    // Dropdown form
                                    echo '<div id="' . $menu_id . '" class="report-menu hidden"
            style="position: absolute; top: 100%; right: 0; background: #fff; border: 1px solid #ccc; border-radius: 5px; z-index: 10; padding: 10px; width: 220px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
                                    echo '<form method="POST" action="pages/main/xulybaocaobinhluan.php">';
                                    echo '<input type="hidden" name="id_binhluan" value="' . $id_binhluan . '">';
                                    echo '<label style="font-size: 14px;">Báo cáo:</label>';
                                    echo '<select name="lydo" required style="width: 100%; margin: 6px 0; padding: 5px; font-size: 14px;">
            <option value="">-- Lý do --</option>
            <option value="Spam">Spam</option>
            <option value="Ngôn từ không phù hợp">Ngôn từ không phù hợp</option>
            <option value="Không liên quan sản phẩm">Không liên quan sản phẩm</option>
            <option value="Thông tin sai lệch">Thông tin sai lệch</option>
            <option value="Khác">Khác</option>
        </select>';
                                    echo '<button type="submit" name="guibaocao"
            style="background: crimson; color: white; border: none; padding: 6px 12px; border-radius: 4px; font-size: 14px; cursor: pointer;">
            Gửi
        </button>';
                                    echo '</form>';
                                    echo '</div>';
                                    echo '</div>';
                                }



                                // Số sao
                                if (!empty($row_bl['rating'])) {
                                    echo '<div class="text-yellow-500 mt-1" style="margin-top: 4px;">';
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo '<i class="fa fa-star' . ($i <= $row_bl['rating'] ? '' : ' text-gray-300') . '"></i>';
                                    }
                                    echo '</div>';
                                }

                                // Nội dung
                                echo '<p style="margin-top: 5px;">' . htmlspecialchars($row_bl['noidung']) . '</p>';

                                // Lý do đã bị báo cáo
                                $sql_baocao = "SELECT COUNT(*) as tong, GROUP_CONCAT(lydo SEPARATOR '<br>') as lydobaocao 
                       FROM tbl_baocao_binhluan WHERE id_binhluan = '$id_binhluan'";
                                $result_baocao = mysqli_query($mysqli, $sql_baocao);
                                $row_baocao = mysqli_fetch_assoc($result_baocao);
                                $tong_baocao = $row_baocao['tong'];
                                $lydo_text = $row_baocao['lydobaocao'];

                                if ($tong_baocao > 0) {
                                    echo "<div style='color: red; font-size: 14px; margin-top: 6px;'>";
                                    echo "<strong>Đã có $tong_baocao lượt báo cáo:</strong>";
                                    echo "<button onclick=\"toggleReason(this)\" style='background-color: #c2c9cdff; color: white; padding: 5px 5px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; font-size: 13px; border-radius: 60px ; margin-left: 3px;'>
                                             <i class='fas fa-eye'></i></button>";
                                    echo "<div class='reason-box hidden' style='margin-top: 8px; background-color: #f3f3f3; padding: 8px; border: 1px solid #ddd; font-size: 13px;'>$lydo_text</div>";
                                    echo "</div>";
                                }

                                echo '</div>';
                            }
                        } else {
                            echo '<p>Chưa có bình luận nào.</p>';
                        }
                        ?>



                        <!-- Form điền bình luận -->
                        <!-- Chỉ hiển thị form nếu đã đăng nhập -->
                        <?php if (!empty($_SESSION['id_khachhang'])): ?>
                            <form class="comment-form" method="POST" action="" style="margin-top: 20px; max-width: 600px;">
                                <textarea name="noidung" id="comment" rows="4" placeholder="Nhập bình luận của bạn..." required
                                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px;"></textarea>

                                <input type="hidden" name="id_sanpham" value="<?php echo $row_chitiet['id_sanpham']; ?>">

                                <div class="rating-group" style="margin: 12px 0;">
                                    <label style="font-weight: 500; display: block; margin-bottom: 5px;">Đánh giá:</label>
                                    <div class="rating-stars" style="display: flex; gap: 8px;">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <label style="cursor: pointer;">
                                                <input type="radio" name="rating" value="<?php echo $i; ?>" style="display: none;" required>
                                                <i class="fa fa-star" style="font-size: 24px; color: #ccc; transition: 0.2s;"></i>
                                            </label>
                                        <?php endfor; ?>
                                    </div>
                                </div>

                                <button type="submit" name="guibinhluan"
                                    style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-size: 16px; cursor: pointer;">
                                    Gửi bình luận
                                </button>
                            </form>
                        <?php else: ?>
                            <p style="margin-top: 20px;">Vui lòng <a href="index.php?quanly=dangnhap" style="color: #007bff; font-weight: 500;">đăng nhập</a> để bình luận.</p>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>


        <!-- Chọn số sao -->
        <script>
            const stars = document.querySelectorAll('.rating-stars label');
            stars.forEach((label, index) => {
                label.addEventListener('mouseover', () => {
                    for (let i = 0; i <= index; i++) {
                        stars[i].querySelector('i').style.color = '#FFD43B';
                    }
                    for (let i = index + 1; i < stars.length; i++) {
                        stars[i].querySelector('i').style.color = '#ccc';
                    }
                });

                label.addEventListener('click', () => {
                    for (let i = 0; i < stars.length; i++) {
                        const input = stars[i].querySelector('input');
                        stars[i].querySelector('i').style.color = i <= index ? '#FFD43B' : '#ccc';
                        input.checked = i === index;
                    }
                });

                label.addEventListener('mouseout', () => {
                    let selectedIndex = -1;
                    stars.forEach((lbl, idx) => {
                        if (lbl.querySelector('input').checked) {
                            selectedIndex = idx;
                        }
                    });
                    for (let i = 0; i < stars.length; i++) {
                        stars[i].querySelector('i').style.color = i <= selectedIndex ? '#FFD43B' : '#ccc';
                    }
                });
            });
        </script>

        </div>
    </body>
<?php
}
?>



<!--======= PHẦN SCRIPT XỬ LÝ ====== -->
<script>
    // JavaScript để xử lý sự kiện chọn kích thước
    document.querySelectorAll('.size-button').forEach(button => {
        button.addEventListener('click', function() {
            // Xóa sự chọn trước đó
            document.querySelectorAll('.size-button').forEach(btn => btn.classList.remove('selected'));
            // Đánh dấu kích thước đã chọn
            this.classList.add('selected');
            // Cập nhật input ẩn với kích thước đã chọn
            document.getElementById('selectedSize').value = this.getAttribute('data-size');
        });
    });


    //Tab bình luận và mô tả sản phẩm
    function showTab(tab) {
        const slider = document.getElementById('tab-slider');
        const btnDesc = document.getElementById('tab-description-btn');
        const btnCmt = document.getElementById('tab-comments-btn');

        // Slide chuyển
        if (tab === 'description') {
            slider.style.transform = 'translateX(0%)';
        } else if (tab === 'comments') {
            slider.style.transform = 'translateX(-100%)';
        }

        // Cập nhật button active
        btnDesc.classList.remove('bg-blue-500', 'text-white');
        btnDesc.classList.add('bg-gray-200', 'text-gray-700');

        btnCmt.classList.remove('bg-blue-500', 'text-white');
        btnCmt.classList.add('bg-gray-200', 'text-gray-700');

        if (tab === 'description') {
            btnDesc.classList.add('bg-blue-500', 'text-white');
            btnDesc.classList.remove('bg-gray-200', 'text-gray-700');
        } else {
            btnCmt.classList.add('bg-blue-500', 'text-white');
            btnCmt.classList.remove('bg-gray-200', 'text-gray-700');
        }
    }

    //Report bình luận 
    function toggleMenu(id) {
        const menus = document.querySelectorAll('.report-menu');
        menus.forEach(menu => {
            if (menu.id !== id) menu.classList.add('hidden');
        });

        const target = document.getElementById(id);
        if (target) target.classList.toggle('hidden');
    }

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.report-menu') && !event.target.closest('.report-button')) {
            document.querySelectorAll('.report-menu').forEach(menu => menu.classList.add('hidden'));
        }
    });

    //Hiện lý do report
    function toggleReason(button) {
        const reasonBox = button.nextElementSibling;
        if (reasonBox.classList.contains("hidden")) {
            reasonBox.classList.remove("hidden");
            button.innerHTML = "<i class='fas fa-eye-slash'></i>";
        } else {
            reasonBox.classList.add("hidden");
            button.innerHTML = "<i class='fas fa-eye'></i>";
        }
    }
</script>

<style>
    .size-button.selected {
        background-color: #3b82f6;
        color: white;
    }

    .comment .fa-star {
        margin-right: 2px;
    }

    /* Bình luận tổng thể */
    .comment-section {
        margin-top: 30px;
        padding: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        background-color: #f9f9f9;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    /* Tiêu đề */
    .comment-section h3 {
        font-size: 20px;
        margin-bottom: 15px;
        color: #333;
    }

    /* Hiển thị từng bình luận */
    .comment {
        padding: 12px 0;
        border-bottom: 1px solid #ddd;
    }

    .comment:last-child {
        border-bottom: none;
    }

    .comment strong {
        color: #1a73e8;
        font-weight: 600;
    }

    .comment p {
        margin: 5px 0 0;
        color: #444;
    }

    /* Form viết bình luận */
    .comment-form {
        margin-top: 20px;
    }

    .comment-form textarea {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        resize: vertical;
        font-size: 14px;
    }

    .comment-form button {
        margin-top: 10px;
        padding: 10px 20px;
        border: none;
        background-color: #1a73e8;
        color: white;
        font-weight: bold;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .comment-form button:hover {
        background-color: #1665c1;
    }

    /* Thông báo */
    .comment-section .message {
        margin-top: 10px;
        font-weight: bold;
    }

    .comment-section .message.success {
        color: green;
    }

    .comment-section .message.error {
        color: red;
    }


    .tab-content {
        padding: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .hidden {
        display: none;
    }

    /* Slide wrapper */
    .tab-slider {
        display: flex;
        width: 100%;
        transition: transform 0.5s ease-in-out;
    }

    /* Mỗi tab chiếm nửa chiều rộng */
    .tab-panel {
        width: 100%;
        padding: 24px;
        flex-shrink: 0;
        box-sizing: border-box;
        background-color: #ffffff;
    }

    /* Nút tab */
    .tab-button {
        transition: 0.3s ease;
        font-size: 16px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .tab-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.08);
    }

    .tab-button.active,
    .tab-button:focus {
        background-color: #3b82f6;
        color: white;
    }

    /* Phần mô tả */
    .product-description {
        font-size: 16px;
        line-height: 1.6;
    }

    .product-description p {
        margin-bottom: 12px;
    }

    .product-description h1,
    .product-description h2,
    .product-description h3 {
        margin: 16px 0 10px;
        font-weight: bold;
        color: #1f2937;
    }

    .product-description ul {
        padding-left: 20px;
        list-style: disc;
    }

    .product-description ol {
        padding-left: 20px;
        list-style: decimal;
    }

    .product-description strong {
        font-weight: bold;
        color: #111827;
    }

    .product-description em {
        font-style: italic;
    }

    /* report */
    .hidden {
        display: none;
    }
</style>