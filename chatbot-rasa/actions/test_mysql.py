import mysql.connector

try:
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",  # sửa nếu bạn dùng mật khẩu
        database="quanlycoffee"
    )
    cursor = conn.cursor()
    cursor.execute("SELECT tensanpham, giasp FROM tbl_sanpham ORDER BY giasp ASC LIMIT 1")
    result = cursor.fetchone()
    print("✅ Kết nối thành công, dữ liệu:", result)
except Exception as e:
    print("❌ Lỗi:", e)
finally:
    if conn.is_connected():
        cursor.close()
        conn.close()
