CREATE DATABASE IF NOT EXISTS devandria_losias
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE devandria_losias;

CREATE TABLE IF NOT EXISTS admin_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    display_name VARCHAR(120) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS site_content (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    content_key VARCHAR(120) NOT NULL UNIQUE,
    content_value TEXT NOT NULL,
    content_type ENUM('text', 'textarea', 'image', 'url') NOT NULL DEFAULT 'text',
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS skills (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    icon_class VARCHAR(120) NOT NULL,
    category VARCHAR(40) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS careers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(160) NOT NULL,
    company VARCHAR(160) NOT NULL,
    location VARCHAR(160) NOT NULL,
    start_date VARCHAR(30) NOT NULL,
    end_date VARCHAR(30) NOT NULL,
    badge VARCHAR(50) NOT NULL,
    logo_path VARCHAR(255) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS education (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    institution VARCHAR(180) NOT NULL,
    degree VARCHAR(160) NOT NULL,
    location VARCHAR(160) NOT NULL,
    start_date VARCHAR(30) NOT NULL,
    end_date VARCHAR(30) NOT NULL,
    logo_path VARCHAR(255) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS achievements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    certificate_code VARCHAR(80) NOT NULL,
    title VARCHAR(180) NOT NULL,
    issuer VARCHAR(160) NOT NULL,
    category VARCHAR(80) NOT NULL,
    tags VARCHAR(255) NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    published_label VARCHAR(80) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS projects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    description TEXT NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    technologies VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contact_links (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    platform VARCHAR(60) NOT NULL,
    title VARCHAR(160) NOT NULL,
    description TEXT NOT NULL,
    url VARCHAR(255) NOT NULL,
    icon_class VARCHAR(120) NOT NULL,
    card_class VARCHAR(60) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO admin_users (username, password_hash, display_name)
VALUES ('admin', '$2y$10$3NIXJRn3h2QfiHUA3MxPxu.s8jfFkQGWGspsZeKQ7GDYTaKQO3Q4W', 'Administrator')
ON DUPLICATE KEY UPDATE username = VALUES(username);

INSERT INTO site_content (content_key, content_value, content_type) VALUES
('profile_name', 'Ade Fikri A.A', 'text'),
('profile_username', '@ficckryy', 'text'),
('profile_photo', 'assets/img/profile.jpg', 'image'),
('profile_location', 'Berbasis di Ciamis, Indonesia', 'text'),
('home_title', 'Ade Fikri | Fullstack Web Developer Indonesia', 'text'),
('home_intro_1', 'Saya seorang Fullstack Web Developer yang berfokus pada pengembangan aplikasi web berkinerja tinggi, skalabel, dan terstruktur dengan baik.', 'textarea'),
('home_intro_2', 'Saya percaya bahwa perangkat lunak yang baik bukan sekadar yang berjalan dengan benar, melainkan yang dapat dipelihara, dioptimalkan, dan berkembang seiring kebutuhan bisnis.', 'textarea'),
('home_intro_3', 'Selain membangun solusi digital, saya juga membagikan aktivitas coding saya secara terbuka.', 'textarea'),
('about_description', 'Saya Ade Fikri Amirudin Amsyar, seorang Fullstack Web Developer dari Indonesia dengan latar belakang di bidang Rekayasa Perangkat Lunak.', 'textarea'),
('about_signature', 'Ade Fikri', 'text')
ON DUPLICATE KEY UPDATE content_key = VALUES(content_key);

INSERT INTO skills (name, icon_class, category, sort_order) VALUES
('Prisma', 'fa-solid fa-database', 'database', 1),
('Npm', 'fa-brands fa-npm', 'tools', 2),
('PostgreSQL', 'fa-solid fa-database', 'database', 3),
('Material UI', 'fa-solid fa-wind', 'frontend', 4),
('Php', 'fa-brands fa-php', 'backend', 5),
('Framer Motion', 'fa-solid fa-layer-group', 'frontend', 6),
('Supabase', 'fa-solid fa-bolt', 'backend', 7),
('NextJs', 'fa-solid fa-n', 'frontend', 8),
('Mysql', 'fa-solid fa-database', 'database', 9),
('Vercel', 'fa-solid fa-triangle-exclamation', 'tools', 10),
('Github', 'fa-brands fa-github', 'tools', 11),
('ReactJs', 'fa-brands fa-react', 'frontend', 12);

INSERT INTO careers (title, company, location, start_date, end_date, badge, logo_path, sort_order)
VALUES ('Praktik Kerja Lapangan (PKL)', 'PT Inovindo Digital Media', 'Bandung, Indonesia', 'Jan 2025', 'Apr 2025', 'PKL', 'assets/img/profile.jpg', 1);

INSERT INTO education (institution, degree, location, start_date, end_date, logo_path, description, sort_order)
VALUES ('SMK Negeri 1 Ciamis', 'Rekayasa Perangkat Lunak', 'Ciamis, Indonesia', '2022', '2025', 'assets/img/profile.jpg', 'Pendidikan kejuruan dengan fokus pada pengembangan perangkat lunak.', 1);

INSERT INTO achievements (certificate_code, title, issuer, category, tags, image_path, published_label, sort_order) VALUES
('3LQMZV2Q8XK1', 'Juara Vibe Coding Perticipan', 'Google Developer Groups', 'Course', 'Course,AI', 'assets/img/cert1.jpg', 'DITERBITKAN MAY 2026', 1),
('DVCHJE8KHV4J', 'Intro to Software Engineering', 'RevoU', 'Course', 'Course,Frontend', 'assets/img/cert2.jpg', 'DITERBITKAN JUNI 2026', 2),
('E7WG5NTE5UI5', 'Continuous Improvement dalam HR', 'HR MASTER', 'Course', 'Course,Frontend', 'assets/img/cert3.jpg', 'DITERBITKAN JUNI 2026', 3),
('YRHZHTJEI05', 'Personal Branding HR di Era Digital', 'HR MASTER', 'Course', 'Course', 'assets/img/cert1.jpg', 'DITERBITKAN JUNI 2026', 4),
('5YMLWR9CD2PA', 'Learn Coding Basics in JavaScript', 'Learning Platform', 'Course', 'Course,JavaScript', 'assets/img/cert2.jpg', 'DITERBITKAN 2026', 5),
('7BNZ9YXASKQ2', 'Belajar Dasar Pemrograman JavaScript', 'Dicoding', 'Course', 'Course,JavaScript', 'assets/img/cert3.jpg', 'DITERBITKAN 2026', 6);

INSERT INTO projects (title, description, image_path, technologies, sort_order) VALUES
('Indibiz Datel Sumedang', 'Sebuah landing page modern yang dirancang khusus untuk mengoptimalkan konversi penjualan produk dan layanan digital.', 'assets/img/project1.jpg', 'N,TS,PG,TW', 1),
('CareerLens AI', 'Platform analisis karier berbasis kecerdasan buatan yang dirancang untuk membantu pengguna memahami potensi dan perkembangan karier.', 'assets/img/project2.jpg', 'N,PG,TS,TW,R', 2),
('Portfolio', 'Personal website untuk menampilkan profil, pengalaman, prestasi, proyek dan keahlian.', 'assets/img/project3.jpg', 'N,PG,TS,TW,R', 3),
('E-Agenda K-ONE', 'Website agenda dan presensi siswa yang mendukung administrasi pembelajaran yang lebih efisien dan terstruktur.', 'assets/img/project4.jpg', 'PHP,MySQL,JS', 4);

INSERT INTO contact_links (platform, title, description, url, icon_class, card_class, sort_order) VALUES
('email', 'Kolaborasi & Profesional', 'Hubungi saya untuk kerja sama proyek, freelance, atau peluang profesional.', 'mailto:emailkamu@gmail.com', 'fa-regular fa-envelope', 'email', 1),
('instagram', 'Aktivitas & Insight', 'Konten, aktivitas, dan perjalanan pengembangan saya.', 'https://instagram.com/', 'fa-brands fa-instagram', 'instagram', 2),
('whatsapp', 'WhatsApp', 'Hubungi saya secara langsung untuk diskusi dan kebutuhan kolaborasi.', 'https://wa.me/6280000000000', 'fa-brands fa-whatsapp', 'whatsapp', 3),
('linkedin', 'Terhubung Secara Profesional', 'Bangun koneksi dan jaringan profesional bersama saya.', 'https://linkedin.com/', 'fa-brands fa-linkedin-in', 'linkedin', 4),
('github', 'Portofolio Kode', 'Jelajahi repositori dan proyek yang saya kembangkan.', 'https://github.com/', 'fa-brands fa-github', 'github', 5);
