# PRODUCT BACKLOG (AGILE / SCRUM)
## DỰ ÁN: GEOQUADRILATERAL - HỆ THỐNG TRỰC QUAN HÓA & HỌC TẬP HÌNH HỌC TỨ GIÁC (NEO4J)
**Đơn vị thực hiện:** Nhóm 9 • Học phần NoSQL (Neo4j)  
**Phương pháp quản lý:** Agile Scrum • Thời lượng: 4 Sprint  

---

## 1. TỔNG HỢP DANH MỤC PRODUCT BACKLOG

| Story ID | Epic | User Story (Là ai? Muốn gì? Để làm gì?) | Tiêu Chí Nghiệm Thu (Acceptance Criteria) | Điểm (SP) | Ưu Tiên | Trạng Thái |
|---|---|---|---|:---:|:---:|:---:|
| **US-01** | Thiết kế CSDL Đồ thị | Là thành viên nhóm, tôi muốn thiết kế lược đồ Graph Neo4j cho các hình tứ giác, để mô tả đúng cây quan hệ theo yêu cầu của thầy. | - Đầy đủ 9 loại hình tứ giác.<br>- Node Property, Formula, Category.<br>- Quan hệ `EVOLVES_TO`, `HAS_PROPERTY`, `HAS_FORMULA`. | 8 | Cao (Must) | Hoàn thành |
| **US-02** | Xây dựng Kịch bản Cypher | Là quản trị viên, tôi muốn có file `.cypher` tự động xóa, gán constraint và tạo toàn bộ dữ liệu chỉ trong 1 lần chạy. | - Script `setup_quadrilaterals.cypher` chạy không lỗi.<br>- Có câu lệnh kiểm tra kết quả trả về. | 5 | Cao (Must) | Hoàn thành |
| **US-03** | Kết nối PHP & Neo4j | Là lập trình viên backend, tôi muốn xây dựng lớp `Neo4jService.php` giao tiếp với Neo4j qua HTTP Transactional API. | - Kết nối thành công tới port 7474.<br>- Có cơ chế Fallback Cache nếu server Neo4j tạm dừng. | 8 | Cao (Must) | Hoàn thành |
| **US-04** | Trực quan hóa Sơ đồ Đồ thị | Là học sinh, tôi muốn xem sơ đồ cây phả hệ các hình học dạng đồ thị tương tác đa tầng trên trang chủ. | - Sử dụng Vis-network vẽ đúng cấu trúc phân tầng.<br>- Click vào node thì hiển thị tóm tắt đặc tính ở sidebar. | 8 | Cao (Must) | Hoàn thành |
| **US-05** | Kho Tàng Hình Tứ Giác | Là học sinh, tôi muốn vào xem chi tiết từng hình học, công thức và hình vẽ SVG sắc nét không cần tạo tài khoản. | - 9 thẻ hình trực quan với hình minh họa SVG chuẩn.<br>- Đầy đủ định nghĩa, công thức chu vi & diện tích. | 5 | Cao (Must) | Hoàn thành |
| **US-06** | Giọng Đọc Text-to-Speech | Là học sinh nhỏ tuổi, tôi muốn bấm nút loa để nghe đọc định nghĩa bài giảng bằng giọng nói tiếng Việt. | - Tích hợp Web Speech API tiếng Việt rõ ràng, chậm rãi. | 3 | Trung bình | Hoàn thành |
| **US-07** | Máy Tính Chu Vi & Diện Tích | Là học sinh, tôi muốn click vào hình để nhập số đo và xem ngay kết quả kèm lời giải từng bước. | - Hỗ trợ đủ tất cả các loại hình tứ giác.<br>- Hình vẽ SVG co giãn kích thước thời gian thực.<br>- Xuất từng bước giải chi tiết. | 8 | Cao (Must) | Hoàn thành |
| **US-08** | Cỗ Máy Biến Hình (Shortest Path) | Là học sinh/giáo viên, tôi muốn chọn hình gốc và hình đích để xem các bước chuyển hóa hình học ngắn nhất. | - Sử dụng thuật toán `shortestPath` trong Cypher.<br>- Hiển thị timeline các bước và điều kiện cần thêm. | 8 | Cao (Must) | Hoàn thành |
| **US-09** | So Sánh 2 Hình Tứ Giác | Là học sinh, tôi muốn đặt 2 hình cạnh nhau để thấy rõ điểm giống nhau và điểm khác nhau. | - Đặt 2 hình song song kèm SVG.<br>- Liệt kê tính chất chung và tính chất riêng. | 5 | Trung bình | Hoàn thành |
| **US-10** | Đấu Trường Đố Vui (Quiz) | Là học sinh, tôi muốn tham gia trò chơi đố vui công thức và nhận diện hình có tính điểm để ôn bài. | - 6 câu đố phong phú, có âm thanh và pháo hoa.<br>- Chấm điểm và trao tặng danh hiệu Huy hiệu. | 5 | Cao (Must) | Hoàn thành |
| **US-11** | Quản Trị & Thực Thi Cypher | Là giảng viên/sinh viên báo cáo, tôi muốn có giao diện kiểm tra kết nối và chạy câu lệnh Cypher trực tiếp. | - Nạp dữ liệu Cypher 1-click.<br>- Cypher Console tương tác và xuất kết quả JSON. | 5 | Cao (Must) | Hoàn thành |
| **US-12** | Soạn Thảo Bộ Hồ Sơ Báo Cáo | Là nhóm sinh viên, chúng tôi cần hoàn thiện đầy đủ 4 tài liệu theo yêu cầu để nộp bài cho thầy. | - 01_HO_SO_DAC_TA_YEU_CAU.md<br>- 02_PRODUCT_BACKLOG.md<br>- 03_HUONG_DAN_CAI_DAT_VA_SU_DUNG.md<br>- README.md | 5 | Cao (Must) | Hoàn thành |

---

## 2. KẾ HOẠCH THEO TỪNG SPRINT (SPRINT BREAKDOWN)

### 🏃 Sprint 1: Thiết Kế & Xây Dựng Graph Database Neo4j
- **Mục tiêu:** Thiết lập cấu trúc Graph, các nhãn Node, Relationship và hoàn thiện file Cypher script.
- **Story triển khai:** US-01, US-02.
- **Tổng Story Points:** 13 SP.
- **Kết quả:** File `database/setup_quadrilaterals.cypher` và `database/queries_sample.cypher`.

### 🏃 Sprint 2: Phát Triển Nền Tảng PHP & Trực Quan Hóa Đồ Thị
- **Mục tiêu:** Xây dựng backend PHP, kết nối Neo4j REST API, thiết kế layout CSS Edu Kids và tích hợp Vis-network.
- **Story triển khai:** US-03, US-04, US-05, US-06.
- **Tổng Story Points:** 24 SP.
- **Kết quả:** `index.php`, `shapes.php`, `assets/js/graph-view.js`, `assets/css/style.css`.

### 🏃 Sprint 3: Cỗ Máy Biến Hình (Neo4j Shortest Path) & Máy Tính Hình Học
- **Mục tiêu:** Hiện thực hóa các tính năng nâng cao ứng dụng đồ thị và máy tính tương tác SVG.
- **Story triển khai:** US-07, US-08.
- **Tổng Story Points:** 16 SP.
- **Kết quả:** `transformer.php`, `calculator.php`, `assets/js/calculator.js`, `assets/js/transformer.js`.

### 🏃 Sprint 4: So Sánh Hình, Minigame Quiz & Bộ Tài Liệu Bàn Giao
- **Mục tiêu:** Hoàn thiện tính năng so sánh, trò chơi đố vui, bảng điều khiển Cypher console và tài liệu nộp bài.
- **Story triển khai:** US-09, US-10, US-11, US-12.
- **Tổng Story Points:** 20 SP.
- **Kết quả:** `compare.php`, `quiz.php`, `neo4j_console.php` và thư mục `docs/`.
