<?php

// Xử lý lọc theo ngày
$type = $_POST['filter_type'] ?? 'day';
$start = $_POST['start_date'] ?? '';
$end = $_POST['end_date'] ?? '';

$where = '';
$group_by = '';
$date_format = '';

if ($start && $end) {
    $where = "WHERE DATE(cart_date) BETWEEN '$start' AND '$end' AND tbl_cart.cart_status = 1";
}

switch ($type) {
    case 'month':
        $date_format = "%Y-%m";
        $group_by = "GROUP BY DATE_FORMAT(cart_date, '$date_format')";
        break;
    case 'year':
        $date_format = "%Y";
        $group_by = "GROUP BY DATE_FORMAT(cart_date, '$date_format')";
        break;
    default:
        $date_format = "%Y-%m-%d";
        $group_by = "GROUP BY DATE_FORMAT(cart_date, '$date_format')";
        break;
}

$sql = "
SELECT 
    DATE_FORMAT(cart_date, '$date_format') AS thoigian,
    SUM(tbl_cart_details.soluongmua) AS tongsoluong,
    SUM(tbl_cart_details.soluongmua * tbl_sanpham.giasp) AS tongdoanhthu
FROM tbl_cart_details
JOIN tbl_sanpham ON tbl_cart_details.id_sanpham = tbl_sanpham.id_sanpham
JOIN tbl_cart ON tbl_cart_details.code_cart = tbl_cart.code_cart
$where
$group_by
ORDER BY thoigian ASC
";

$result = $mysqli->query($sql);

// Tạo mảng dữ liệu
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'thoigian' => $row['thoigian'],
        'tongsoluong' => (int)$row['tongsoluong'],
        'tongdoanhthu' => (int)$row['tongdoanhthu']
    ];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê doanh thu</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap');

body {
    font-family: 'Lora', serif;
    background-color: #f4f6f9;
    margin: 0;
    padding: 20px;
}
        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            text-align: center;
            margin-bottom: 30px;
        }
        select, input[type="date"], input[type="submit"] {
            padding: 8px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
          input[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }
        canvas {
            max-width: 100%;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Thống kê doanh thu</h2>
        <form method="POST" action="/admincf/modules/quanlythongke/inbaocao.php" target="_blank">
    <input type="hidden" name="filter_type" value="<?= $type ?>">
    <input type="hidden" name="start_date" value="<?= $start ?>">
    <input type="hidden" name="end_date" value="<?= $end ?>">
    <input type="submit" value="In báo cáo PDF" style="background-color: #2ecc71; color: white; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer;">
</form>

    <form method="POST">
        Lọc theo:
        <select name="filter_type">
            <option value="day" <?= $type == 'day' ? 'selected' : '' ?>>Ngày</option>
            <option value="month" <?= $type == 'month' ? 'selected' : '' ?>>Tháng</option>
            <option value="year" <?= $type == 'year' ? 'selected' : '' ?>>Năm</option>
        </select>
        Từ ngày: <input type="date" name="start_date" value="<?= $start ?>">
        Đến ngày: <input type="date" name="end_date" value="<?= $end ?>">
        <input type="submit" value="Lọc">
    </form>

    <canvas id="revenueChart" height="120"></canvas>
</div>

<script>
    const chartData = <?= json_encode($data) ?>;

    const labels = chartData.map(item => item.thoigian);
    const quantities = chartData.map(item => item.tongsoluong);
    const revenues = chartData.map(item => item.tongdoanhthu);

    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Số lượng bán',
                    data: quantities,
                    backgroundColor: '#3498db',
                    yAxisID: 'y'
                },
                {
                    label: 'Doanh thu (VNĐ)',
                    data: revenues,
                    backgroundColor: '#2ecc71',
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            stacked: false,
            scales: {
                y: {
                    type: 'linear',
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Số lượng'
                    }
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Doanh thu (VNĐ)'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
</script>
</body>
</html>
