// ==============================================================================
// CÁC CÂU LỆNH TRUY VẤN CYPHER MẪU (DÙNG TRONG BÁO CÁO VÀ TEST TRÊN NEO4J BROWSER)
// ==============================================================================

// 1. Xem toàn bộ cây phả hệ tiến hóa của các hình tứ giác (Visual Graph)
MATCH (n:Shape)-[r:EVOLVES_TO]->(m:Shape)
RETURN n, r, m;

// 2. Tìm tất cả các hình là con cháu tiến hóa từ Tứ Giác
MATCH path = (root:Shape {id: 'tu_giac'})-[:EVOLVES_TO*]->(child:Shape)
RETURN path;

// 3. Tìm con đường biến hình ngắn nhất (Shortest Path) từ Hình bình hành -> Hình vuông
MATCH (start:Shape {id: 'hinh_binh_hanh'}), (target:Shape {id: 'hinh_vuong'})
MATCH p = shortestPath((start)-[:EVOLVES_TO*]->(target))
RETURN p;

// 4. Lấy đầy đủ thông tin chi tiết của Hình chữ nhật (Tính chất + Công thức)
MATCH (s:Shape {id: 'hinh_chu_nhat'})
OPTIONAL MATCH (s)-[:HAS_PROPERTY]->(p:Property)
OPTIONAL MATCH (s)-[:HAS_FORMULA]->(f:Formula)
RETURN s.name AS TenHinh, 
       s.definition AS DinhNghia, 
       collect(p.name) AS DanhSachDacTinh, 
       f.perimeter_formula AS ChuVi, 
       f.area_formula AS DienTich;

// 5. So sánh điểm chung và điểm khác giữa 2 hình (Ví dụ: Hình chữ nhật vs Hình thoi)
// Lấy các tính chất chung:
MATCH (s1:Shape {id: 'hinh_chu_nhat'})-[:HAS_PROPERTY]->(p:Property)<-[:HAS_PROPERTY]-(s2:Shape {id: 'hinh_thoi'})
RETURN p.name AS TinhChatChung;

// 6. Truy vấn để tạo câu hỏi trắc nghiệm (Quiz): Tìm hình có 4 góc vuông và 4 cạnh bằng nhau
MATCH (s:Shape)-[:HAS_PROPERTY]->(p:Property)
WHERE p.id IN ['prop_4_goc_vuong', 'prop_4_canh_bang']
WITH s, count(p) AS countProps
WHERE countProps = 2
RETURN s.name AS KetQua;
