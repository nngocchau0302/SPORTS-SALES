<?php


// Xử lý lọc theo ngày
if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $where = "WHERE DATE(tbl_cart.cart_date) BETWEEN '$start' AND '$end'";
} else {
    $start = '';
    $end = '';
    $where = '';
}

// Truy vấn thống kê
$sql = "
SELECT tbl_sanpham.tensanpham, SUM(tbl_cart_details.soluongmua) AS soluongban
FROM tbl_cart_details
JOIN tbl_sanpham ON tbl_cart_details.id_sanpham = tbl_sanpham.id_sanpham
JOIN tbl_cart ON tbl_cart_details.code_cart = tbl_cart.code_cart
$where
GROUP BY tbl_cart_details.id_sanpham
ORDER BY soluongban DESC
LIMIT 10
";

$result = mysqli_query($mysqli, $sql);
if (!$result) {
    die("Lỗi truy vấn: " . mysqli_error($mysqli));
}

$labels = [];
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['tensanpham'];
    $data[] = $row['soluongban'];
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thống kê sản phẩm bán chạy</title>
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
            max-width: 960px;
            margin: auto;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        input[type="date"],
        input[type="submit"] {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
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
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Thống kê theo sản phẩm </h2>

        <form method="POST" action="/admincf/modules/quanlythongke/inbaocaosp.php" target="_blank">
            <input type="hidden" name="start_date" value="<?= $start ?>">
            <input type="hidden" name="end_date" value="<?= $end ?>">
            <input type="submit" value="In báo cáo PDF" style="background:#2ecc71; color:white; padding:8px 12px; border:none; border-radius:6px; cursor:pointer;">
        </form>

        <form method="POST">
            <label>
                Từ ngày:
                <input type="date" name="start_date" value="<?php echo $start; ?>">
            </label>
            <label>
                Đến ngày:
                <input type="date" name="end_date" value="<?php echo $end; ?>">
            </label>
            <input type="submit" value="Lọc">
        </form>

        <canvas id="barChart"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Số lượng bán',
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: '#3498db',
                    borderColor: '#2980b9',
                    borderWidth: 1,
                    hoverBackgroundColor: '#5dade2'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Biểu đồ cột - Top 10 sản phẩm bán chạy',
                        font: {
                            size: 18
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        title: {
                            display: true,
                            text: 'Số lượng bán'
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 60,
                            minRotation: 30
                        },
                        title: {
                            display: true,
                            text: 'Tên sản phẩm'
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>