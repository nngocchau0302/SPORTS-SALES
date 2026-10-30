<?php
// Nội dung bình luận mẫu
$noidung = "Sản phẩm rất tốt, mình hài lòng.";

// Tạo dữ liệu gửi đi
$payload = json_encode(array("text" => $noidung));

// URL của API Flask đang chạy
$api_url = 'http://127.0.0.1:5000/api/sentiment';


// Khởi tạo cURL
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Nhận phản hồi
curl_setopt($ch, CURLOPT_POST, true);            // Gửi dạng POST
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);  // Gửi nội dung dưới dạng JSON

// Thực hiện gọi API
$response = curl_exec($ch);

// Kiểm tra lỗi
if (curl_errno($ch)) {
    echo 'Lỗi cURL: ' . curl_error($ch);
} else {
    echo "✅ Phản hồi từ API:<br><pre>" . htmlspecialchars($response) . "</pre>";
}

curl_close($ch);
?>
