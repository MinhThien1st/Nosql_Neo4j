# HƯỚNG DẪN CÀI ĐẶT VÀ SỬ DỤNG
## DỰ ÁN: ỨNG DỤNG HỖ TRỢ HỌC TOÁN HÌNH HỌC TỨ GIÁC VỚI NEO4J
**Đơn vị thực hiện:** Nhóm 9 • Học phần Cơ sở dữ liệu NoSQL  

---

## 1. YÊU CẦU HỆ THỐNG
1. **PHP:** Phiên bản 8.0 trở lên (đã có sẵn trong XAMPP, WampServer hoặc PHP CLI chuẩn).
2. **Neo4j Database:**
   - Phiên bản: Neo4j Desktop (Community hoặc Enterprise) v4.x / v5.x hoặc Docker container.
   - Cổng mặc định: HTTP `7474`, Bolt `7687`.
3. **Trình duyệt web:** Google Chrome, Microsoft Edge, Mozilla Firefox hoặc Safari.

---

## 2. HƯỚNG DẪN KHỞI CHẠY ỨNG DỤNG WEB PHP

### Cách 1: Khởi chạy nhanh bằng PHP Built-in Server (Khuyên dùng)
Mở cửa sổ dòng lệnh (Terminal / PowerShell) tại thư mục dự án `d:\Code\NoSQL\Nhom9_Neo4j_TuGiac`:
```powershell
php -S localhost:8000
```
Sau đó mở trình duyệt và truy cập: **`http://localhost:8000`**

### Cách 2: Sử dụng qua XAMPP / WampServer
- Sao chép toàn bộ thư mục dự án vào thư mục `htdocs` của XAMPP:
  `C:\xampp\htdocs\Nhom9_Neo4j_TuGiac`
- Khởi động Apache trong XAMPP Control Panel.
- Truy cập vào đường dẫn: **`http://localhost/Nhom9_Neo4j_TuGiac`**

---

## 3. HƯỚNG DẪN CÀI ĐẶT & NẠP DỮ LIỆU VÀO NEO4J DATABASE

### Cách 1: Nạp 1-Click trực tiếp qua giao diện Web (Cực kỳ tiện lợi)
1. Khởi động cơ sở dữ liệu Neo4j của bạn (bảo đảm port 7474 đang mở).
2. Mở file `config/config.php` để kiểm tra tài khoản và mật khẩu:
   ```php
   define('NEO4J_HOST', 'localhost');
   define('NEO4J_HTTP_PORT', 7474);
   define('NEO4J_USER', 'neo4j');
   define('NEO4J_PASS', 'password'); // Đổi thành mật khẩu Neo4j của bạn
   ```
3. Truy cập vào menu **"⚡ Quản Trị Neo4j"** trên thanh điều hướng (`http://localhost:8000/neo4j_console.php`).
4. Nhấn nút màu tím: **"⚡ Nạp Toàn Bộ Dữ Liệu Tứ Giác Vào Neo4j (1-Click Seed)"**.
5. Hệ thống sẽ tự động thực thi toàn bộ kịch bản và thông báo thành công 🎉!

### Cách 2: Nạp qua Neo4j Browser chính thức
1. Mở trình duyệt và truy cập Neo4j Browser: `http://localhost:7474`
2. Đăng nhập tài khoản Neo4j của bạn.
3. Mở file `database/setup_quadrilaterals.cypher` trong thư mục dự án.
4. Sao chép (Copy) toàn bộ nội dung file và dán (Paste) vào ô nhập lệnh của Neo4j Browser, sau đó nhấn nút **Run** (Ctrl + Enter).
5. Để xem kết quả trực quan trên Neo4j Browser, chạy lệnh:
   ```cypher
   MATCH (n)-[r]->(m) RETURN n, r, m;
   ```

---

## 4. HƯỚNG DẪN SỬ DỤNG CÁC TÍNH NĂNG CHÍNH

### 4.1. Sơ đồ đồ thị Neo4j cây phả hệ (`index.php`)
- **Khám phá đồ thị:** Phóng to/thu nhỏ bằng con lăn chuột, kéo thả các khối hình trên màn hình.
- **Xem chi tiết:** Nhấp chuột vào bất kỳ hình nào, cột bên phải sẽ lập tức hiển thị định nghĩa, dấu hiệu nhận biết và công thức.
- **Nghe đọc:** Nhấn nút **"🔊 Nghe đọc"** để nghe máy phát âm bằng giọng nói tiếng Việt.

### 4.2. Kho tàng các hình tứ giác (`shapes.php`)
- Hiển thị danh mục 9 loại tứ giác với hình minh họa SVG chuẩn mực (Hình thang, Hình chữ nhật, Hình vuông, Hình thoi, Hình diều...).
- Có ví dụ đời sống thực tế và công thức kèm theo.
- Bấm **"🧮 Tính chu vi & diện tích"** hoặc **"⚖️ So sánh"** từ thẻ của hình tương ứng.

### 4.3. Cỗ máy biến hình (`transformer.php`)
- Chọn **Hình Bắt Đầu** (ví dụ: *Hình bình hành*) và **Hình Đích** (ví dụ: *Hình vuông*).
- Nhấn **"✨ Khởi Động Biến Hình!"**.
- Hệ thống gọi thuật toán `shortestPath` trong Neo4j và hiển thị từng chặng biến đổi kèm điều kiện hình học cần thêm.

### 4.4. Máy tính Chu vi & Diện tích trực quan (`calculator.php`)
- Chọn loại hình tứ giác cần tính.
- Nhập số đo các cạnh hoặc chiều cao vào các ô nhập liệu.
- **Hình vẽ SVG sẽ tự co giãn kích thước theo thời gian thực**!
- Xem kết quả Chu vi ($P$), Diện tích ($S$) và từng bước giải toán chi tiết.

### 4.5. So sánh 2 hình tứ giác (`compare.php`)
- Chọn 2 hình muốn đem ra so sánh.
- Hệ thống hiển thị 2 hình vẽ đặt cạnh nhau và phân tích:
  - Các đặc điểm giống nhau (quan hệ kế thừa chung trong Neo4j).
  - Các đặc tính riêng biệt của từng hình.

### 4.6. Đấu trường Đố vui Quiz (`quiz.php`)
- Trả lời các câu hỏi nhận diện hình và câu vè công thức.
- Khi chọn đúng sẽ có âm thanh ting-ting và hiệu ứng pháo hoa chúc mừng 🎉.
- Nhận bảng điểm tổng kết và danh hiệu Huy hiệu Toán học khi hoàn thành.

### 4.7. Bảng điều khiển Cypher Console (`neo4j_console.php`)
- Dành riêng cho giảng viên và sinh viên thực nghiệm: có thể chạy các câu truy vấn Cypher tùy ý và nhận kết quả JSON cấu trúc dữ liệu đồ thị.

---

## 5. HƯỚNG DẪN ĐƯA MÃ NGUỒN LÊN GITHUB

Để nộp link GitHub theo yêu cầu mục 3 của thầy, thực hiện các lệnh sau:
```powershell
# 1. Khởi tạo kho Git
git init

# 2. Thêm toàn bộ mã nguồn
git add .

# 3. Tạo commit đầu tiên
git commit -m "Khoi tao do an Nhom 9 - Neo4j Tu Giac hoan chinh"

# 4. Đổi tên nhánh sang main
git branch -M main

# 5. Liên kết tới kho GitHub của nhóm bạn
git remote add origin https://github.com/<tai-khoan-cua-ban>/Nhom9_Neo4j_TuGiac.git

# 6. Đẩy mã nguồn lên GitHub
git push -u origin main
```
