# HỒ SƠ ĐẶC TẢ YÊU CẦU PHẦN MỀM (SRS)
## DỰ ÁN: GEOQUADRILATERAL - HỆ THỐNG TRỰC QUAN HÓA & HỌC TẬP HÌNH HỌC TỨ GIÁC
### MÔN HỌC: CƠ SỞ DỮ LIỆU NOSQL (HỌC PHẦN NEO4J GRAPH DATABASE)
**Đơn vị thực hiện:** Nhóm 9  
**Năm học:** 2026  

---

## 1. GIỚI THIỆU TỔNG QUAN

### 1.1. Mục tiêu dự án
Dự án nhằm xây dựng một ứng dụng web học tập trực quan sinh động dành cho học sinh tiểu học và trung học cơ sở (trẻ em từ 8 đến 14 tuổi), giúp các em dễ dàng tiếp thu kiến thức về các hình học phẳng thuộc họ **Tứ giác** (Hình thang, Hình bình hành, Hình chữ nhật, Hình thoi, Hình vuông, Hình diều...).

Khác với các hệ quản trị CSDL quan hệ truyền thống (RDBMS), dự án ứng dụng **Cơ sở dữ liệu đồ thị Neo4j (Graph Database)** để mô hình hóa mối quan hệ kế thừa và chuyển hóa giữa các hình học dựa trên các điều kiện và đặc tính toán học.

### 1.2. Đối tượng phục vụ
- **Học sinh:** Dễ dàng tra cứu hình, nghe đọc định nghĩa (Text-to-Speech), xem hình vẽ co giãn trực quan, tính toán chu vi / diện tích và tham gia mini-game đố vui có tính điểm và huy hiệu.
- **Giáo viên & Phụ huynh:** Công cụ trực quan giảng dạy tính chất hình học và mối liên hệ phả hệ giữa các hình.
- **Tiêu chí tiếp cận:** Web cơ bản, **không yêu cầu đăng nhập/tạo tài khoản** nhằm tối ưu sự thuận tiện cho trẻ nhỏ.

---

## 2. THIẾT KẾ CƠ SỞ DỮ LIỆU ĐỒ THỊ NEO4J (GRAPH DATA MODEL)

### 2.1. Các Loại Node (Labels & Properties)
1. **Node `:Shape` (Hình học):**
   - `id` (String, Unique): Khóa định danh (ví dụ: `hinh_vuong`, `hinh_binh_hanh`).
   - `name` (String): Tên tiếng Việt của hình.
   - `name_en` (String): Tên tiếng Anh (Square, Parallelogram...).
   - `alias` (String): Tên gọi khác / Biệt danh.
   - `definition` (String): Định nghĩa chuẩn toán học.
   - `kid_friendly` (String): Định nghĩa ngắn gọn, hóm hỉnh cho trẻ em.
   - `fun_fact` (String): Bí mật hình học thú vị.
   - `real_life` (String): Ví dụ ứng dụng trong đời sống.
   - `color` (String): Mã màu giao diện.
   - `badge` (String): Phân cấp độ hình học.

2. **Node `:Property` (Đặc tính / Dấu hiệu nhận biết):**
   - `id` (String, Unique): Mã đặc tính (ví dụ: `prop_4_goc_vuong`, `prop_4_canh_bang`).
   - `code` (String): Mã hiển thị (P01, P02...).
   - `name` (String): Mô tả đặc tính (ví dụ: "Có 4 góc vuông 90°").
   - `category` (String): Phân loại (Cạnh, Góc, Đường chéo...).

3. **Node `:Formula` (Công thức toán học):**
   - `id` (String, Unique): Mã công thức.
   - `shape_id` (String): Mã hình liên kết.
   - `perimeter_formula` (String): Biểu thức tính chu vi ($P = 4a$).
   - `area_formula` (String): Biểu thức tính diện tích ($S = a^2$).
   - `perimeter_desc` & `area_desc`: Lời giải thích và câu vè tính nhẩm.

4. **Node `:Category` (Danh mục gốc):**
   - Đại diện cho `Hình học phẳng` làm gốc của đồ thị.

### 2.2. Các Loại Quan Hệ (Relationships)
1. **`(:Shape)-[:EVOLVES_TO {condition: "...", detail: "..."}]->(:Shape)`**:
   - Thể hiện con đường tiến hóa từ hình cấp thấp lên hình cấp cao khi bổ sung điều kiện hình học.
   - Ví dụ: `(Hình bình hành)-[:EVOLVES_TO {condition: 'Có 1 góc vuông'}]->(Hình chữ nhật)`.
2. **`(:Shape)-[:HAS_PROPERTY]->(:Property)`**:
   - Gán đặc tính và dấu hiệu nhận biết cho từng hình học.
3. **`(:Shape)-[:HAS_FORMULA]->(:Formula)`**:
   - Gán bộ công thức chu vi và diện tích cho hình.
4. **`(:Shape)-[:SPECIAL_CASE_OF]->(:Shape)`**:
   - Xác định quan hệ hình này là trường hợp đặc biệt của hình khác.

---

## 3. ĐẶC TẢ YÊU CẦU CHỨC NĂNG (FUNCTIONAL REQUIREMENTS)

| Mã UC | Tên Chức Năng | Mô Tả Chi Tiết |
|---|---|---|
| **UC-01** | **Trực quan hóa Cây phả hệ Neo4j** | Hiển thị đồ thị tương tác đa tầng bằng Vis-network theo đúng mô hình của giảng viên; hỗ trợ zoom, kéo thả, click node để xem sidebar tóm tắt. |
| **UC-02** | **Kho tàng Tứ giác (Chi tiết từng hình)** | Hiển thị danh mục 9 loại tứ giác với hình vẽ SVG chuẩn xác, định nghĩa, thực tế, công thức và tích hợp phát âm tiếng Việt (Text-to-Speech). |
| **UC-03** | **Cỗ máy biến hình (Shape Transformer)** | Sử dụng thuật toán `shortestPath` của Neo4j tìm con đường ngắn nhất giữa 2 hình và phân rã các bước bổ sung điều kiện hình học. |
| **UC-04** | **Máy tính Chu vi & Diện tích trực quan** | Cho phép học sinh nhập thông số cạnh/chiều cao/đường chéo; hình vẽ SVG tự động co giãn kích thước thời gian thực và xuất lời giải từng bước. |
| **UC-05** | **So sánh 2 hình tứ giác** | Đặt 2 hình cạnh nhau để so sánh điểm giống nhau (quan hệ chung trên đồ thị) và điểm khác nhau cần ghi nhớ. |
| **UC-06** | **Đấu trường Đố vui (Math Kids Quiz)** | Trò chơi trắc nghiệm 4 lựa chọn nhận diện hình qua đặc điểm, câu vè công thức; tích hợp âm thanh synthesizer, pháo hoa và bảng huy hiệu tổng kết. |
| **UC-07** | **Bảng điều khiển & Quản trị Neo4j** | Kiểm tra trạng thái máy chủ Neo4j, nạp kịch bản Cypher 1-click (`seed data`), thực thi Cypher tùy biến trực tiếp trong giao diện. |

---

## 4. ĐẶC TẢ YÊU CẦU PHI CHỨC NĂNG (NON-FUNCTIONAL REQUIREMENTS)

1. **Giao diện & Trải nghiệm (UI/UX Edu Kids):**
   - Màu sắc tươi sáng, rực rỡ, bo tròn thân thiện với trẻ em.
   - Font chữ rõ ràng (`Nunito`, `Quicksand`), hỗ trợ giọng đọc Text-to-Speech cho trẻ nhỏ.
2. **Độ ổn định & Chế độ Fallback thông minh:**
   - Website có khả năng tự động nhận diện nếu máy chủ Neo4j đang chạy thì truy vấn trực tiếp; nếu chưa bật máy chủ thì chuyển sang dữ liệu đồ thị cache nội bộ mà không làm phát sinh lỗi màn hình trắng.
3. **Hiệu năng & Khả năng tương thích:**
   - Tốc độ tải trang < 1.2 giây; tương thích hoàn toàn trên máy tính bảng (iPad, Android Tablet) và máy tính để bàn (Chrome, Edge, Firefox, Safari).
   - Không yêu cầu thư viện mở rộng phức tạp (hoạt động tốt trên PHP 8 chuẩn).

---

## 5. KIẾN TRÚC HỆ THỐNG
```
[Trình Duyệt Khách (Client)]
      │
      │ HTTP Request / JSON AJAX
      ▼
[Máy Chủ Web PHP 8 (Backend)]
      ├── Routing & Presentation (index.php, shapes.php, calculator.php...)
      ├── Business Logic Layer (classes/GeometryModel.php)
      └── Database Access Layer (classes/Neo4jService.php)
             │
             ├── (1) Giao thức HTTP REST / Cypher API ➔ [Cơ sở dữ liệu Neo4j (Port 7474)]
             └── (2) Dự phòng thông minh ➔ [Fallback In-Memory Graph Cache]
```
