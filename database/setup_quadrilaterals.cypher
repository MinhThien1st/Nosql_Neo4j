// ==============================================================================
// ĐỒ ÁN MÔN NOSQL (NEO4J) - NHÓM 9
// ĐỀ TÀI: XÂY DỰNG CƠ SỞ DỮ LIỆU ĐỒ THỊ VÀ ỨNG DỤNG HỌC HÌNH HỌC TỨ GIÁC
// File: setup_quadrilaterals.cypher
// ==============================================================================

// 1. XÓA TOÀN BỘ DỮ LIỆU CŨ TRƯỚC KHI KHỞI TẠO (NẾU CÓ)
MATCH (n) DETACH DELETE n;

// 2. TẠO RÀNG BUỘC DUY NHẤT (CONSTRAINTS)
CREATE CONSTRAINT shape_id_unique IF NOT EXISTS FOR (s:Shape) REQUIRE s.id IS UNIQUE;
CREATE CONSTRAINT prop_id_unique IF NOT EXISTS FOR (p:Property) REQUIRE p.id IS UNIQUE;
CREATE CONSTRAINT formula_id_unique IF NOT EXISTS FOR (f:Formula) REQUIRE f.id IS UNIQUE;

// ==============================================================================
// 3. TẠO CÁC NODE DANH MỤC VÀ HÌNH HỌC (SHAPE NODES)
// ==============================================================================

// Node gốc: Hình học phẳng
CREATE (root:Category {
    id: 'hinh_hoc_phang',
    name: 'Hình học phẳng',
    name_vn: 'Hình học phẳng',
    description: 'Ngành hình học nghiên cứu các hình nằm trên một mặt phẳng 2 chiều (2D).',
    color: '#4B5563',
    icon: '📐'
});

// Node: Tứ giác
CREATE (tu_giac:Shape {
    id: 'tu_giac',
    name: 'Tứ giác',
    name_en: 'Quadrilateral',
    alias: 'Tứ giác thường / Tứ giác lồi',
    definition: 'Tứ giác là một đa giác có 4 cạnh, 4 đỉnh và 4 góc. Tổng số đo 4 góc trong một tứ giác luôn bằng 360 độ.',
    kid_friendly: 'Hình có đúng 4 góc nhọn/tù và 4 cạnh nối liền nhau khép kín như một cánh cổng bí mật!',
    color: '#0284C7',
    badge: 'Cấp độ 1 - Gốc',
    svg_type: 'tu_giac',
    fun_fact: 'Mọi tứ giác lồi bất kỳ đều có thể chia thành 2 hình tam giác bằng một đường chéo!',
    real_life: 'Cánh buồm thuyền buồm, miếng dán sticker, bàn cờ méo.'
});

// Node: Hình thang
CREATE (hinh_thang:Shape {
    id: 'hinh_thang',
    name: 'Hình thang',
    name_en: 'Trapezoid',
    alias: 'Hình thang',
    definition: 'Hình thang là tứ giác có một cặp cạnh đối diện song song với nhau (gọi là hai đáy: đáy lớn và đáy nhỏ).',
    kid_friendly: 'Giống như cái mái nhà hoặc chậu hoa úp ngược, có 2 thanh xà ngang song song với nhau!',
    color: '#0EA5E9',
    badge: 'Cấp độ 2',
    svg_type: 'hinh_thang',
    fun_fact: 'Tên tiếng Anh Trapezoid xuất phát từ tiếng Hy Lạp "trapeza" có nghĩa là cái bàn ăn nhỏ!',
    real_life: 'Mái nhà ngói, túi xách thời trang, thang leo, chậu cây cảnh.'
});

// Node: Hình thang vuông
CREATE (hinh_thang_vuong:Shape {
    id: 'hinh_thang_vuong',
    name: 'Hình thang vuông',
    name_en: 'Right Trapezoid',
    alias: 'Hình thang vuông',
    definition: 'Hình thang vuông là hình thang có ít nhất một góc vuông (khi đó cạnh bên vuông góc với 2 đáy chính là chiều cao).',
    kid_friendly: 'Một chiếc hình thang có một bên dựng đứng vuông vức 90 độ thẳng băng!',
    color: '#06B6D4',
    badge: 'Cấp độ 2.1',
    svg_type: 'hinh_thang_vuong',
    fun_fact: 'Chiều cao của hình thang vuông bằng chính độ dài của cạnh bên vuông góc!',
    real_life: 'Cầu trượt một bờ thẳng đứng, bậc cầu thang chắn gió.'
});

// Node: Hình thang cân
CREATE (hinh_thang_can:Shape {
    id: 'hinh_thang_can',
    name: 'Hình thang cân',
    name_en: 'Isosceles Trapezoid',
    alias: 'Hình thang cân',
    definition: 'Hình thang cân là hình thang có hai góc kề một đáy bằng nhau, hoặc hai cạnh bên bằng nhau và hai đường chéo bằng nhau.',
    kid_friendly: 'Chiếc hình thang siêu cân đối, gập đôi lại hai nửa khớp nhau y chang!',
    color: '#14B8A6',
    badge: 'Cấp độ 2.2',
    svg_type: 'hinh_thang_can',
    fun_fact: 'Hình thang cân có đúng 1 trục đối xứng đi qua trung điểm hai đáy!',
    real_life: 'Bát ăn cơm nhìn ngang, váy công chúa xòe, bóng đèn chụp.'
});

// Node: Hình diều
CREATE (hinh_dieu:Shape {
    id: 'hinh_dieu',
    name: 'Hình diều',
    name_en: 'Kite',
    alias: 'Hình cánh diều',
    definition: 'Hình diều là tứ giác có hai cặp cạnh kề nhau bằng nhau. Hai đường chéo vuông góc với nhau và đường chéo chính là đường trung trực của đường chéo phụ.',
    kid_friendly: 'Đúng như tên gọi, đây chính là hình dáng của cánh diều giấy bay lượn trên bầu trời tuổi thơ!',
    color: '#F97316',
    badge: 'Cấp độ 2',
    svg_type: 'hinh_dieu',
    fun_fact: 'Khung tre của con diều tạo thành 2 đường chéo vuông góc 90 độ giúp diều giữ thăng bằng trong gió!',
    real_life: 'Con diều giấy, mặt dây chuyền pha lê, viên kim cương cắt dạng diều.'
});

// Node: Hình bình hành
CREATE (hinh_binh_hanh:Shape {
    id: 'hinh_binh_hanh',
    name: 'Hình bình hành',
    name_en: 'Parallelogram',
    alias: 'Hình bình hành',
    definition: 'Hình bình hành là tứ giác có hai cặp cạnh đối song song và bằng nhau, các góc đối bằng nhau, hai đường chéo cắt nhau tại trung điểm của mỗi đường.',
    kid_friendly: 'Tựa như một chiếc hộp chữ nhật bị nghiêng đi trong gió, hai cạnh đối lúc nào cũng song song và bằng nhau tăm tắp!',
    color: '#38BDF8',
    badge: 'Cấp độ 3',
    svg_type: 'hinh_binh_hanh',
    fun_fact: 'Nếu bạn nối trung điểm của 4 cạnh của bất kỳ tứ giác nào, bạn sẽ luôn nhận được một hình bình hành (Định lý Varignon)!',
    real_life: 'Tẩy bút chì vát chéo, cánh cửa xếp, bóng đổ của tòa nhà cao tầng.'
});

// Node: Hình chữ nhật
CREATE (hinh_chu_nhat:Shape {
    id: 'hinh_chu_nhat',
    name: 'Hình chữ nhật',
    name_en: 'Rectangle',
    alias: 'Hình chữ nhật',
    definition: 'Hình chữ nhật là tứ giác có 4 góc vuông (90 độ). Nó là một trường hợp đặc biệt của hình bình hành có hai đường chéo bằng nhau.',
    kid_friendly: 'Hình quen thuộc nhất quanh bé: màn hình tivi, quyển sách giáo khoa, chiếc điện thoại hay cánh cửa lớp học!',
    color: '#10B981',
    badge: 'Cấp độ 4',
    svg_type: 'hinh_chu_nhat',
    fun_fact: 'Tất cả 4 góc của hình chữ nhật đều là góc vuông 90 độ, tổng 4 góc là 360 độ!',
    real_life: 'Màn hình iPad, mặt bàn học, cánh cửa sổ, cuốn tập vở, tờ tiền.'
});

// Node: Hình thoi
CREATE (hinh_thoi:Shape {
    id: 'hinh_thoi',
    name: 'Hình thoi',
    name_en: 'Rhombus',
    alias: 'Hình thoi / Kim cương',
    definition: 'Hình thoi là tứ giác có 4 cạnh dài bằng nhau. Hai đường chéo vuông góc với nhau tại trung điểm của mỗi đường và là các đường phân giác của các góc.',
    kid_friendly: 'Giống như con rô trong bộ bài tây hoặc viên kim cương lấp lánh có 4 cạnh bằng nhau!',
    color: '#059669',
    badge: 'Cấp độ 4',
    svg_type: 'hinh_thoi',
    fun_fact: 'Hình thoi vừa là hình bình hành đặc biệt (4 cạnh bằng nhau), vừa là hình diều đặc biệt!',
    real_life: 'Họa tiết thổ cẩm Tây Bắc, biển báo hiệu giao thông nguy hiểm, viên kim cương.'
});

// Node: Hình vuông
CREATE (hinh_vuong:Shape {
    id: 'hinh_vuong',
    name: 'Hình vuông',
    name_en: 'Square',
    alias: 'Hình vuông hoàn hảo',
    definition: 'Hình vuông là tứ giác hoàn hảo nhất: có 4 góc vuông và 4 cạnh bằng nhau. Nó vừa là hình chữ nhật đặc biệt, vừa là hình thoi đặc biệt.',
    kid_friendly: 'Vua của các hình học! 4 cạnh bằng nhau như đúc và 4 góc vuông vức 100%, cực kỳ cân đối và hoàn hảo!',
    color: '#EC4899',
    badge: 'Cấp độ 5 - Tinh hoa',
    svg_type: 'hinh_vuong',
    fun_fact: 'Hình vuông có đến 4 trục đối xứng và 1 tâm đối xứng! Trong cùng một chu vi, hình vuông có diện tích lớn nhất trong các hình chữ nhật!',
    real_life: 'Khối Rubik, ô gạch bông lát nền, mặt xúc xắc, bàn cờ vua.'
});

// ==============================================================================
// 4. TẠO CÁC NODE TÍNH CHẤT / ĐẶC TÍNH (PROPERTY NODES)
// ==============================================================================
CREATE (p1:Property {
    id: 'prop_4_canh_4_goc',
    code: 'P01',
    name: '4 cạnh, 4 đỉnh, 4 góc, tổng 4 góc = 360°',
    category: 'Cơ bản',
    description: 'Đặc trưng cơ bản nhất của mọi đa giác 4 cạnh trong mặt phẳng.'
});

CREATE (p2:Property {
    id: 'prop_1_cap_song_song',
    code: 'P02',
    name: 'Có đúng hoặc ít nhất 1 cặp cạnh đối song song',
    category: 'Cạnh',
    description: 'Dấu hiệu nhận biết cốt lõi của hình thang.'
});

CREATE (p3:Property {
    id: 'prop_2_cap_ke_bang',
    code: 'P03',
    name: 'Có 2 cặp cạnh kề nhau bằng nhau',
    category: 'Cạnh',
    description: 'Hai cạnh phía trên bằng nhau và hai cạnh phía dưới bằng nhau.'
});

CREATE (p4:Property {
    id: 'prop_2_cap_song_song',
    code: 'P04',
    name: 'Hai cặp cạnh đối song song và bằng nhau',
    category: 'Cạnh',
    description: 'Cạnh trên song song cạnh dưới, cạnh trái song song cạnh phải.'
});

CREATE (p5:Property {
    id: 'prop_goc_doi_bang',
    code: 'P05',
    name: 'Các góc đối bằng nhau',
    category: 'Góc',
    description: 'Hai góc đối diện nhau trong hình có số đo góc bằng nhau.'
});

CREATE (p6:Property {
    id: 'prop_4_goc_vuong',
    code: 'P06',
    name: 'Có 4 góc vuông (90°)',
    category: 'Góc',
    description: 'Tất cả các góc đều bằng 90 độ vuông vức.'
});

CREATE (p7:Property {
    id: 'prop_4_canh_bang',
    code: 'P07',
    name: 'Có 4 cạnh bằng nhau',
    category: 'Cạnh',
    description: 'Độ dài cả 4 cạnh đều bằng đúng một giá trị a.'
});

CREATE (p8:Property {
    id: 'prop_cheo_vuong_goc',
    code: 'P08',
    name: 'Hai đường chéo vuông góc với nhau',
    category: 'Đường chéo',
    description: 'Hai đường chéo cắt nhau tạo thành một góc đúng 90 độ.'
});

CREATE (p9:Property {
    id: 'prop_cheo_bang_nhau',
    code: 'P09',
    name: 'Hai đường chéo có độ dài bằng nhau',
    category: 'Đường chéo',
    description: 'Độ dài d1 = d2.'
});

CREATE (p10:Property {
    id: 'prop_cheo_cat_trung_diem',
    code: 'P10',
    name: 'Hai đường chéo cắt nhau tại trung điểm mỗi đường',
    category: 'Đường chéo',
    description: 'Giao điểm của 2 đường chéo chia mỗi đường thành 2 đoạn bằng nhau.'
});

CREATE (p11:Property {
    id: 'prop_1_goc_vuong',
    code: 'P11',
    name: 'Có ít nhất 1 góc vuông (90°)',
    category: 'Góc',
    description: 'Có một cạnh bên vuông góc với đáy.'
});

CREATE (p12:Property {
    id: 'prop_2_goc_ke_day_bang',
    code: 'P12',
    name: 'Hai góc kề một đáy bằng nhau',
    category: 'Góc',
    description: 'Dấu hiệu quan trọng của hình thang cân.'
});

// ==============================================================================
// 5. TẠO CÁC NODE CÔNG THỨC TOÁN HỌC (FORMULA NODES)
// ==============================================================================

CREATE (f_tu_giac:Formula {
    id: 'f_tu_giac',
    shape_id: 'tu_giac',
    perimeter_formula: 'P = a + b + c + d',
    perimeter_desc: 'Chu vi bằng tổng độ dài 4 cạnh quanh hình.',
    area_formula: 'S = S(Tam giác 1) + S(Tam giác 2)',
    area_desc: 'Chia tứ giác thành 2 tam giác bằng đường chéo rồi cộng diện tích lại.',
    params: 'a, b, c, d'
});

CREATE (f_hinh_thang:Formula {
    id: 'f_hinh_thang',
    shape_id: 'hinh_thang',
    perimeter_formula: 'P = a + b + c + d',
    perimeter_desc: 'Tổng độ dài của 2 đáy và 2 cạnh bên.',
    area_formula: 'S = ((a + b) * h) / 2',
    area_desc: 'Đáy lớn đáy nhỏ ta đem cộng vào, nhân với chiều cao, chia đôi lấy nửa thế nào cũng ra!',
    params: 'a (đáy lớn), b (đáy nhỏ), h (chiều cao), c, d (cạnh bên)'
});

CREATE (f_hinh_dieu:Formula {
    id: 'f_hinh_dieu',
    shape_id: 'hinh_dieu',
    perimeter_formula: 'P = 2 * (a + b)',
    perimeter_desc: 'Chu vi bằng 2 lần tổng hai cạnh kề khác nhau.',
    area_formula: 'S = (d1 * d2) / 2',
    area_desc: 'Diện tích bằng tích của hai đường chéo chia đôi.',
    params: 'a, b (2 cạnh kề), d1, d2 (2 đường chéo)'
});

CREATE (f_hinh_binh_hanh:Formula {
    id: 'f_hinh_binh_hanh',
    shape_id: 'hinh_binh_hanh',
    perimeter_formula: 'P = 2 * (a + b)',
    perimeter_desc: 'Chu vi bằng tổng độ dài 2 cạnh kề nhân 2.',
    area_formula: 'S = a * h',
    area_desc: 'Diện tích bằng cạnh đáy nhân với chiều cao tương ứng hạ từ đỉnh.',
    params: 'a (đáy), b (cạnh kề), h (chiều cao)'
});

CREATE (f_hinh_chu_nhat:Formula {
    id: 'f_hinh_chu_nhat',
    shape_id: 'hinh_chu_nhat',
    perimeter_formula: 'P = 2 * (a + b)',
    perimeter_desc: 'Chu vi bằng (chiều dài + chiều rộng) nhân 2.',
    area_formula: 'S = a * b',
    area_desc: 'Diện tích bằng chiều dài nhân với chiều rộng.',
    params: 'a (chiều dài), b (chiều rộng)'
});

CREATE (f_hinh_thoi:Formula {
    id: 'f_hinh_thoi',
    shape_id: 'hinh_thoi',
    perimeter_formula: 'P = 4 * a',
    perimeter_desc: 'Chu vi bằng độ dài 1 cạnh nhân 4 (vì 4 cạnh bằng nhau).',
    area_formula: 'S = (d1 * d2) / 2 = a * h',
    area_desc: 'Diện tích bằng tích hai đường chéo chia đôi (hoặc cạnh đáy nhân chiều cao).',
    params: 'a (cạnh), d1, d2 (2 đường chéo), h (chiều cao)'
});

CREATE (f_hinh_vuong:Formula {
    id: 'f_hinh_vuong',
    shape_id: 'hinh_vuong',
    perimeter_formula: 'P = 4 * a',
    perimeter_desc: 'Chu vi bằng độ dài cạnh nhân với 4.',
    area_formula: 'S = a * a = a^2',
    area_desc: 'Diện tích bằng cạnh nhân với chính nó (bình phương độ dài cạnh).',
    params: 'a (cạnh hình vuông)'
});

// ==============================================================================
// 6. TẠO CÁC MỐI QUAN HỆ TIẾN HÓA / KẾ THỪA (RELATIONSHIPS EVOLVES_TO)
// Theo đúng sơ đồ chuẩn của giảng viên giao:
// ==============================================================================

// Hình học phẳng -> Tứ giác
CREATE (root)-[:INCLUDES {
    condition: 'Có 4 cạnh, 4 đỉnh, 4 góc; tổng 4 góc bằng 360°'
}]->(tu_giac);

// Tứ giác -> Hình thang
CREATE (tu_giac)-[:EVOLVES_TO {
    condition: 'Có một cặp cạnh đối song song',
    detail: 'Khi một tứ giác xuất hiện 1 cặp cạnh đối diện song song, nó trở thành Hình thang.',
    code: 'TG_TO_HT'
}]->(hinh_thang);

// Tứ giác -> Hình diều
CREATE (tu_giac)-[:EVOLVES_TO {
    condition: 'Có 2 cặp cạnh kề nhau bằng nhau',
    detail: 'Khi một tứ giác có 2 cặp cạnh kề bằng nhau từng đôi một, nó trở thành Hình diều.',
    code: 'TG_TO_HD'
}]->(hinh_dieu);

// Tứ giác -> Hình bình hành
CREATE (tu_giac)-[:EVOLVES_TO {
    condition: 'Hai cặp cạnh đối song song; cạnh đối bằng nhau; góc đối bằng nhau',
    detail: 'Tứ giác có các cạnh đối song song hoặc bằng nhau thì biến thành Hình bình hành.',
    code: 'TG_TO_HBH'
}]->(hinh_binh_hanh);

// Hình thang -> Hình thang vuông (nhánh mở rộng chuẩn toán học)
CREATE (hinh_thang)-[:EVOLVES_TO {
    condition: 'Có 1 góc vuông',
    detail: 'Hình thang có thêm 1 góc vuông trở thành Hình thang vuông.',
    code: 'HT_TO_HTV'
}]->(hinh_thang_vuong);

// Hình thang -> Hình thang cân (nhánh mở rộng chuẩn toán học)
CREATE (hinh_thang)-[:EVOLVES_TO {
    condition: 'Hai góc kề một đáy bằng nhau (hoặc 2 đường chéo bằng nhau)',
    detail: 'Hình thang có 2 góc kề 1 đáy bằng nhau hoặc 2 đường chéo bằng nhau thành Hình thang cân.',
    code: 'HT_TO_HTC'
}]->(hinh_thang_can);

// Hình bình hành -> Hình chữ nhật
CREATE (hinh_binh_hanh)-[:EVOLVES_TO {
    condition: 'Có 4 góc vuông (hoặc 1 góc vuông; 2 đường chéo bằng nhau)',
    detail: 'Hình bình hành chỉ cần có thêm 1 góc vuông hoặc 2 đường chéo bằng nhau sẽ trở thành Hình chữ nhật.',
    code: 'HBH_TO_HCN'
}]->(hinh_chu_nhat);

// Hình bình hành -> Hình thoi
CREATE (hinh_binh_hanh)-[:EVOLVES_TO {
    condition: '4 cạnh bằng nhau; hai đường chéo vuông góc',
    detail: 'Hình bình hành có 2 cạnh kề bằng nhau hoặc 2 đường chéo vuông góc sẽ biến thành Hình thoi.',
    code: 'HBH_TO_HTHOI'
}]->(hinh_thoi);

// Hình chữ nhật -> Hình vuông
CREATE (hinh_chu_nhat)-[:EVOLVES_TO {
    condition: '4 cạnh bằng nhau; hai đường chéo vuông góc (hoặc 2 cạnh kề bằng nhau)',
    detail: 'Hình chữ nhật có thêm 2 cạnh kề bằng nhau hoặc hai đường chéo vuông góc sẽ trở thành Hình vuông.',
    code: 'HCN_TO_HV'
}]->(hinh_vuong);

// Hình thoi -> Hình vuông
CREATE (hinh_thoi)-[:EVOLVES_TO {
    condition: 'Có 4 góc vuông (hoặc 1 góc vuông; hai đường chéo bằng nhau)',
    detail: 'Hình thoi có thêm 1 góc vuông hoặc hai đường chéo bằng nhau sẽ trở thành Hình vuông.',
    code: 'HTHOI_TO_HV'
}]->(hinh_vuong);

// Hình diều -> Hình thoi
CREATE (hinh_dieu)-[:EVOLVES_TO {
    condition: 'Có 4 cạnh bằng nhau',
    detail: 'Hình diều khi cả 4 cạnh đều bằng nhau sẽ trở thành Hình thoi.',
    code: 'HD_TO_HTHOI'
}]->(hinh_thoi);

// ==============================================================================
// 7. GÁN TÍNH CHẤT CHO TỪNG HÌNH (RELATIONSHIPS HAS_PROPERTY)
// ==============================================================================

// Tứ giác
CREATE (tu_giac)-[:HAS_PROPERTY]->(p1);

// Hình thang
CREATE (hinh_thang)-[:HAS_PROPERTY]->(p1);
CREATE (hinh_thang)-[:HAS_PROPERTY]->(p2);

// Hình thang vuông
CREATE (hinh_thang_vuong)-[:HAS_PROPERTY]->(p1);
CREATE (hinh_thang_vuong)-[:HAS_PROPERTY]->(p2);
CREATE (hinh_thang_vuong)-[:HAS_PROPERTY]->(p11);

// Hình thang cân
CREATE (hinh_thang_can)-[:HAS_PROPERTY]->(p1);
CREATE (hinh_thang_can)-[:HAS_PROPERTY]->(p2);
CREATE (hinh_thang_can)-[:HAS_PROPERTY]->(p9);
CREATE (hinh_thang_can)-[:HAS_PROPERTY]->(p12);

// Hình diều
CREATE (hinh_dieu)-[:HAS_PROPERTY]->(p1);
CREATE (hinh_dieu)-[:HAS_PROPERTY]->(p3);
CREATE (hinh_dieu)-[:HAS_PROPERTY]->(p8);

// Hình bình hành
CREATE (hinh_binh_hanh)-[:HAS_PROPERTY]->(p1);
CREATE (hinh_binh_hanh)-[:HAS_PROPERTY]->(p4);
CREATE (hinh_binh_hanh)-[:HAS_PROPERTY]->(p5);
CREATE (hinh_binh_hanh)-[:HAS_PROPERTY]->(p10);

// Hình chữ nhật
CREATE (hinh_chu_nhat)-[:HAS_PROPERTY]->(p1);
CREATE (hinh_chu_nhat)-[:HAS_PROPERTY]->(p4);
CREATE (hinh_chu_nhat)-[:HAS_PROPERTY]->(p6);
CREATE (hinh_chu_nhat)-[:HAS_PROPERTY]->(p9);
CREATE (hinh_chu_nhat)-[:HAS_PROPERTY]->(p10);

// Hình thoi
CREATE (hinh_thoi)-[:HAS_PROPERTY]->(p1);
CREATE (hinh_thoi)-[:HAS_PROPERTY]->(p4);
CREATE (hinh_thoi)-[:HAS_PROPERTY]->(p7);
CREATE (hinh_thoi)-[:HAS_PROPERTY]->(p8);
CREATE (hinh_thoi)-[:HAS_PROPERTY]->(p10);

// Hình vuông (Kế thừa đầy đủ các đặc tính cao cấp nhất)
CREATE (hinh_vuong)-[:HAS_PROPERTY]->(p1);
CREATE (hinh_vuong)-[:HAS_PROPERTY]->(p4);
CREATE (hinh_vuong)-[:HAS_PROPERTY]->(p6);
CREATE (hinh_vuong)-[:HAS_PROPERTY]->(p7);
CREATE (hinh_vuong)-[:HAS_PROPERTY]->(p8);
CREATE (hinh_vuong)-[:HAS_PROPERTY]->(p9);
CREATE (hinh_vuong)-[:HAS_PROPERTY]->(p10);

// ==============================================================================
// 8. GÁN CÔNG THỨC CHO TỪNG HÌNH (RELATIONSHIPS HAS_FORMULA)
// ==============================================================================
CREATE (tu_giac)-[:HAS_FORMULA]->(f_tu_giac);
CREATE (hinh_thang)-[:HAS_FORMULA]->(f_hinh_thang);
CREATE (hinh_thang_vuong)-[:HAS_FORMULA]->(f_hinh_thang);
CREATE (hinh_thang_can)-[:HAS_FORMULA]->(f_hinh_thang);
CREATE (hinh_dieu)-[:HAS_FORMULA]->(f_hinh_dieu);
CREATE (hinh_binh_hanh)-[:HAS_FORMULA]->(f_hinh_binh_hanh);
CREATE (hinh_chu_nhat)-[:HAS_FORMULA]->(f_hinh_chu_nhat);
CREATE (hinh_thoi)-[:HAS_FORMULA]->(f_hinh_thoi);
CREATE (hinh_vuong)-[:HAS_FORMULA]->(f_hinh_vuong);

// ==============================================================================
// 9. QUAN HỆ ĐẶC BIỆT (SPECIAL_CASE_OF)
// ==============================================================================
CREATE (hinh_vuong)-[:SPECIAL_CASE_OF]->(hinh_chu_nhat);
CREATE (hinh_vuong)-[:SPECIAL_CASE_OF]->(hinh_thoi);
CREATE (hinh_chu_nhat)-[:SPECIAL_CASE_OF]->(hinh_binh_hanh);
CREATE (hinh_thoi)-[:SPECIAL_CASE_OF]->(hinh_binh_hanh);
CREATE (hinh_binh_hanh)-[:SPECIAL_CASE_OF]->(hinh_thang);
CREATE (hinh_thang_vuong)-[:SPECIAL_CASE_OF]->(hinh_thang);
CREATE (hinh_thang_can)-[:SPECIAL_CASE_OF]->(hinh_thang);
CREATE (hinh_thoi)-[:SPECIAL_CASE_OF]->(hinh_dieu);

// ==============================================================================
// 10. KIỂM TRA DỮ LIỆU ĐÃ TẠO THÀNH CÔNG
// ==============================================================================
RETURN 'Đã khởi tạo thành công Graph CSDL Neo4j Tứ Giác cho Nhóm 9!' AS ThongBao;
