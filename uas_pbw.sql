-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Jun 2025 pada 16.30
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uas_pbw`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `assignments`
--

CREATE TABLE `assignments` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `subject_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deadline` datetime NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `assignments`
--

INSERT INTO `assignments` (`id`, `title`, `description`, `subject_id`, `teacher_id`, `class_id`, `created_at`, `deadline`, `is_active`) VALUES
(1, 'Kerjakan Soal Aljabar', 'Kerjakan soal aljabar halaman 45-50', 1, 1, 4, '2025-05-17 10:44:40', '2025-05-18 17:44:40', 1),
(2, 'Latihan Trigonometri', 'Soal trigonometri bab 3', 1, 1, 4, '2025-05-17 10:44:40', '2025-05-19 17:44:40', 1),
(3, 'Essay tentang Lingkungan', 'Tulis essay tentang lingkungan hidup', 4, 3, 4, '2025-05-17 10:44:40', '2025-05-18 17:44:40', 1),
(4, 'Latihan Grammar', 'Kerjakan latihan grammar bab 5', 4, 3, 4, '2025-05-17 10:44:40', '2025-05-19 17:44:40', 1),
(5, 'Laporan Praktikum Fotosintesis', 'Buat laporan hasil praktikum fotosintesis', 7, 2, 4, '2025-05-17 10:44:40', '2025-05-20 17:44:40', 1),
(6, 'Presentasi Ekosistem', 'Siapkan presentasi tentang ekosistem laut', 7, 2, 4, '2025-05-17 10:44:40', '2025-05-21 17:44:40', 1),
(7, 'Tugas Kimia Dasar', 'Kerjakan soal kimia dasar halaman 30-35', 2, 2, 4, '2025-05-17 10:44:40', '2025-05-18 17:44:40', 1),
(8, 'Praktikum Asam Basa', 'Siapkan laporan praktikum asam basa', 2, 2, 4, '2025-05-17 10:44:40', '2025-05-19 17:44:40', 1),
(9, 'Tugas Gelombang', 'Soal tentang gelombang bunyi', 6, 1, 4, '2025-05-17 10:44:40', '2025-05-18 17:44:40', 1),
(10, 'Praktikum Hukum Newton', 'Laporan praktikum hukum Newton', 6, 1, 4, '2025-05-17 10:44:40', '2025-05-19 17:44:40', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Hadir','Izin','Sakit','Alfa') NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `level` enum('X','XI','XII') NOT NULL,
  `specialization` varchar(50) NOT NULL,
  `classroom` varchar(20) DEFAULT NULL,
  `academic_year` varchar(9) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `classes`
--

INSERT INTO `classes` (`id`, `name`, `level`, `specialization`, `classroom`, `academic_year`, `created_at`, `updated_at`) VALUES
(1, '', 'X', 'MIPA 1', 'Ruang 101', '2023/2024', '2025-05-11 01:24:10', '2025-05-20 01:10:35'),
(2, '', 'X', 'MIPA 2', 'Ruang 102', '2023/2024', '2025-05-11 01:24:10', '2025-05-19 08:57:57'),
(3, '', 'X', 'IPS 1', 'Ruang 103', '2023/2024', '2025-05-11 01:24:10', '2025-05-19 08:58:20'),
(4, '', 'XI', 'MIPA 1', 'Ruang 201', '2023/2024', '2025-05-11 01:24:10', '2025-05-20 01:10:40'),
(5, '', 'XI', 'MIPA 2', 'Ruang 202', '2023/2024', '2025-05-11 01:24:10', '2025-05-19 08:58:39'),
(6, '', 'XI', 'IPS 1', 'Ruang 203', '2023/2024', '2025-05-11 01:24:10', '2025-05-19 08:58:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `category`, `is_published`, `created_at`, `updated_at`) VALUES
(1, 'Bagaimana Cara Melihat Jadwal?', 'Klik menu \"Jadwal Mata Pelajaran\" di sidebar untuk melihat jadwal pelajaran Anda.', 'Jadwal', 1, '2025-05-11 01:24:10', '2025-05-11 01:24:10'),
(2, 'Bagaimana Cara Melihat Nilai?', 'Klik menu \"Akademik\" di sidebar untuk melihat semua nilai akademik Anda.', 'Nilai', 1, '2025-05-11 01:24:10', '2025-05-20 01:47:51'),
(3, 'Cara Mengajukan Izin Absen?', 'Silahkan menghubungi wali kelas melalui fitur chat untuk mengajukan izin absen.', 'Absensi', 1, '2025-05-11 01:24:10', '2025-05-11 01:24:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `academic_year` varchar(9) NOT NULL,
  `semester` int(11) NOT NULL,
  `assignment_score` decimal(5,2) DEFAULT NULL,
  `midterm_score` decimal(5,2) DEFAULT NULL,
  `final_score` decimal(5,2) DEFAULT NULL,
  `total_score` decimal(5,2) DEFAULT NULL,
  `letter_grade` varchar(2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `grades`
--

INSERT INTO `grades` (`id`, `student_id`, `subject_id`, `teacher_id`, `academic_year`, `semester`, `assignment_score`, `midterm_score`, `final_score`, `total_score`, `letter_grade`, `created_at`, `updated_at`) VALUES
(32, 4, 5, 2, '2025/2026', 1, 99.00, 97.00, 98.00, 98.00, 'A', '2025-05-19 09:00:48', '2025-05-19 09:00:48'),
(34, 4, 4, 2, '2025/2026', 1, 98.00, 98.00, 97.00, 97.60, 'A', '2025-05-19 09:01:44', '2025-05-19 09:01:44'),
(35, 4, 7, 2, '2025/2026', 1, 97.00, 99.00, 98.00, 98.00, 'A', '2025-05-19 09:02:04', '2025-05-19 09:02:04'),
(36, 4, 6, 2, '2025/2026', 1, 97.00, 97.00, 98.00, 97.40, 'A', '2025-05-19 09:02:24', '2025-05-19 09:02:24'),
(37, 4, 2, 2, '2025/2026', 1, 98.00, 98.00, 99.00, 98.40, 'A', '2025-05-19 09:02:36', '2025-05-19 09:02:36'),
(38, 4, 3, 2, '2025/2026', 1, 96.00, 97.00, 96.00, 96.30, 'A', '2025-05-19 09:02:55', '2025-05-19 09:02:55'),
(39, 4, 1, 2, '2025/2026', 1, 99.00, 99.00, 99.00, 99.00, 'A', '2025-05-19 09:03:04', '2025-05-19 09:03:04'),
(40, 4, 5, 2, '2025/2026', 2, 98.00, 98.00, 98.00, 98.00, 'A', '2025-05-19 09:36:46', '2025-05-19 09:36:46'),
(41, 4, 4, 2, '2025/2026', 2, 97.00, 97.00, 97.00, 97.00, 'A', '2025-05-19 09:36:56', '2025-05-19 09:36:56'),
(42, 4, 7, 2, '2025/2026', 2, 96.00, 96.00, 96.00, 96.00, 'A', '2025-05-19 09:37:08', '2025-05-19 09:37:08'),
(43, 4, 6, 2, '2025/2026', 2, 98.00, 99.00, 97.00, 97.90, 'A', '2025-05-19 09:37:20', '2025-05-19 09:37:20'),
(44, 4, 2, 2, '2025/2026', 2, 96.00, 97.00, 99.00, 97.50, 'A', '2025-05-19 09:37:30', '2025-05-19 09:37:30'),
(45, 4, 3, 2, '2025/2026', 2, 96.00, 96.00, 96.00, 96.00, 'A', '2025-05-19 09:37:47', '2025-05-19 09:37:47'),
(46, 4, 1, 2, '2025/2026', 2, 97.00, 97.00, 99.00, 97.80, 'A', '2025-05-19 09:37:57', '2025-05-19 09:37:57'),
(47, 5, 5, 2, '2025/2026', 1, 90.00, 91.00, 94.00, 91.90, 'A', '2025-05-19 09:38:38', '2025-05-19 09:38:38'),
(48, 5, 4, 2, '2025/2026', 1, 92.00, 91.00, 93.00, 92.10, 'A', '2025-05-19 09:38:51', '2025-05-19 09:38:51'),
(49, 5, 7, 2, '2025/2026', 1, 94.00, 93.00, 92.00, 92.90, 'A', '2025-05-19 09:39:06', '2025-05-19 09:39:06'),
(50, 5, 6, 2, '2025/2026', 1, 91.00, 93.00, 94.00, 92.80, 'A', '2025-05-19 09:39:22', '2025-05-19 09:39:22'),
(51, 5, 2, 2, '2025/2026', 1, 92.00, 93.00, 95.00, 93.50, 'A', '2025-05-19 09:39:36', '2025-05-19 09:39:36'),
(52, 5, 3, 2, '2025/2026', 1, 91.00, 93.00, 94.00, 92.80, 'A', '2025-05-19 09:39:49', '2025-05-19 09:39:49'),
(53, 5, 1, 2, '2025/2026', 1, 92.00, 94.00, 95.00, 93.80, 'A', '2025-05-19 09:39:59', '2025-05-19 09:39:59'),
(54, 5, 5, 2, '2025/2026', 2, 91.00, 91.00, 90.00, 90.60, 'A', '2025-05-19 09:40:11', '2025-05-19 09:40:11'),
(55, 5, 4, 2, '2025/2026', 2, 92.00, 93.00, 93.00, 92.70, 'A', '2025-05-19 09:40:24', '2025-05-19 09:40:24'),
(56, 5, 7, 2, '2025/2026', 2, 92.00, 91.00, 90.00, 90.90, 'A', '2025-05-19 09:40:33', '2025-05-19 09:40:33'),
(57, 5, 6, 2, '2025/2026', 2, 92.00, 91.00, 93.00, 92.10, 'A', '2025-05-19 09:40:44', '2025-05-19 09:40:44'),
(58, 5, 2, 2, '2025/2026', 2, 92.00, 93.00, 92.00, 92.30, 'A', '2025-05-19 09:40:55', '2025-05-19 09:40:55'),
(59, 5, 3, 2, '2025/2026', 2, 91.00, 90.00, 93.00, 91.50, 'A', '2025-05-19 09:41:04', '2025-05-19 09:41:04'),
(60, 5, 1, 2, '2025/2026', 2, 90.00, 90.00, 85.00, 88.00, 'A-', '2025-05-19 09:41:18', '2025-05-19 09:41:18'),
(61, 7, 5, 2, '2025/2026', 1, 90.00, 92.00, 93.00, 91.80, 'A', '2025-05-20 07:52:29', '2025-05-20 07:52:29'),
(76, 25, 5, 4, '2025/2026', 1, 90.00, 90.00, 90.00, 90.00, 'A', '2025-06-01 14:06:59', '2025-06-01 14:06:59'),
(77, 6, 4, 4, '2025/2026', 1, 89.00, 87.00, 85.00, 86.80, 'A-', '2025-06-01 14:10:39', '2025-06-01 14:10:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `contentfull` varchar(3000) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 1,
  `published_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `contentfull`, `image`, `author_id`, `is_published`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Siswa SMA Negeri 1 Meraih Juara 1 Olimpiade Sains Provinsi', 'Salah satu siswa kelas XI IPA, Andini Pramudita, berhasil meraih Juara 1 Olimpiade Sains tingkat Provinsi dalam bidang Matematika. Lomba ini diikuti oleh lebih dari 100 peserta dari berbagai sekolah. Pihak sekolah menyampaikan apresiasi dan akan memberikan pembinaan untuk persiapan ke tingkat nasional.', 'Prestasi membanggakan kembali diraih oleh SMA Negeri 1 setelah salah satu siswinya, Andini Pramudita dari kelas XI IPA, berhasil meraih Juara 1 dalam ajang Olimpiade Sains tingkat Provinsi pada bidang Matematika. Kompetisi bergengsi ini diselenggarakan pada tanggal 17 Mei 2025 dan diikuti oleh lebih dari 100 peserta dari berbagai SMA unggulan di seluruh provinsi. Kemenangan ini menunjukkan kemampuan akademik yang luar biasa serta dedikasi Andini dalam mengembangkan potensi di bidang sains, khususnya matematika.\r\n\r\nAndini diketahui telah mempersiapkan diri selama berbulan-bulan bersama guru pembimbingnya melalui berbagai latihan soal dan simulasi lomba. Tidak hanya unggul secara teori, Andini juga menunjukkan kemampuan berpikir logis dan analitis yang kuat, yang menjadi kunci utama dalam mengerjakan soal-soal olimpiade yang kompleks. Keberhasilannya ini menjadi inspirasi bagi teman-teman sekelas dan memotivasi siswa lain untuk terus berprestasi di bidang akademik.\r\n\r\nPihak sekolah memberikan apresiasi tinggi atas pencapaian Andini, serta menyampaikan bahwa mereka akan memberikan pembinaan dan dukungan penuh untuk persiapan menghadapi Olimpiade Sains tingkat Nasional. Kepala sekolah juga menyampaikan harapan besar agar prestasi ini menjadi langkah awal untuk membawa nama baik sekolah di kancah yang lebih tinggi. Selain itu, sekolah berkomitmen untuk terus menciptakan lingkungan belajar yang mendorong siswa berkembang dan bersaing secara sehat di berbagai ajang akademik.', 'juara.jpg', 1, 1, '2025-05-11 01:24:10', '2025-05-11 01:24:10', '2025-05-18 12:36:07'),
(2, 'SMA Negeri 1 Menyelenggarakan Workshop ', 'SMA Negeri 1 menyelenggarakan workshop bertema “Teknologi Kecerdasan Buatan (AI) dalam Dunia Pendidikan” yang diikuti oleh siswa kelas XI dan XII. Acara ini menghadirkan pembicara dari universitas ternama dan memberikan wawasan mengenai pemanfaatan teknologi dalam belajar dan karier masa depan.\r\n\r\n', 'Pada tanggal 14 Mei 2025, SMA Negeri 1 menggelar sebuah workshop inspiratif bertema “Teknologi Kecerdasan Buatan (AI) dalam Dunia Pendidikan” yang berhasil menarik antusiasme tinggi dari para siswa kelas XI dan XII. Acara ini merupakan bagian dari program literasi digital sekolah yang bertujuan memperkenalkan siswa pada perkembangan teknologi mutakhir yang berperan besar dalam dunia pendidikan dan profesi masa depan. Dengan suasana interaktif dan penuh semangat, para peserta menunjukkan minat besar untuk memahami bagaimana AI mulai diintegrasikan dalam berbagai aspek kehidupan, termasuk dalam sistem pembelajaran.\r\n\r\nWorkshop ini menghadirkan pembicara dari salah satu universitas ternama di Indonesia yang memiliki latar belakang riset di bidang kecerdasan buatan. Dalam paparannya, pembicara menjelaskan konsep dasar AI, penerapannya dalam pendidikan seperti sistem pembelajaran adaptif dan asisten virtual, serta potensi karier di bidang teknologi. Para siswa juga diberi kesempatan untuk berdiskusi dan mengajukan pertanyaan seputar AI, menjadikan workshop ini tidak hanya sebagai sesi ceramah, tetapi juga sebagai forum pembelajaran aktif yang membangkitkan rasa ingin tahu.\r\n\r\nMelalui kegiatan ini, pihak sekolah berharap para siswa dapat lebih siap menghadapi tantangan zaman yang semakin digital. Selain itu, SMA Negeri 1 berkomitmen untuk terus menghadirkan kegiatan edukatif serupa yang mendorong siswa berpikir kritis dan adaptif terhadap kemajuan teknologi. Workshop ini juga menjadi langkah awal dalam mengintegrasikan topik-topik teknologi masa depan ke dalam kurikulum, sehingga para lulusan nantinya memiliki keunggulan dalam bersaing di dunia pendidikan tinggi maupun dunia kerja.', 'workshop.jpg', 2, 1, '2025-05-11 01:24:10', '2025-05-11 01:24:10', '2025-05-18 12:36:35'),
(3, ' Program Literasi Digital Diterapkan Mulai Semester Ini', 'Sebagai upaya peningkatan literasi digital siswa, sekolah resmi meluncurkan program “Satu Siswa Satu Artikel”, di mana setiap siswa diminta menulis artikel ilmiah ringan setiap bulan. Program ini bertujuan meningkatkan kemampuan menulis, berpikir kritis, dan pemanfaatan teknologi secara bijak.', 'Mulai semester ini, SMA Negeri 1 resmi meluncurkan program inovatif bertajuk “Satu Siswa Satu Artikel” sebagai bagian dari upaya peningkatan literasi digital di kalangan pelajar. Program ini mewajibkan setiap siswa untuk menulis satu artikel ilmiah ringan setiap bulan dengan tema yang beragam, mulai dari isu sosial, sains populer, hingga teknologi digital. Inisiatif ini diharapkan dapat mendorong siswa untuk lebih aktif dalam mencari informasi, mengolah data, serta menyampaikan gagasan secara tertulis dengan bahasa yang baik dan benar.\r\n\r\nMelalui kegiatan menulis rutin ini, siswa dilatih untuk berpikir kritis dan membiasakan diri menyampaikan opini berbasis fakta. Selain itu, proses penulisan juga melibatkan pemanfaatan sumber digital yang kredibel, pengolahan informasi dengan perangkat lunak penulisan, serta pengenalan pada etika digital seperti penghindaran plagiarisme. Dengan demikian, program ini tidak hanya mengasah keterampilan akademik, tetapi juga menanamkan sikap bijak dalam menggunakan teknologi dan media digital.\r\n\r\nPihak sekolah menyatakan bahwa artikel-artikel terbaik akan dipublikasikan di buletin sekolah maupun media sosial resmi, sebagai bentuk apresiasi dan motivasi bagi siswa. Selain itu, guru-guru akan membimbing proses penulisan setiap bulan untuk memastikan kualitas tulisan dan perkembangan kemampuan siswa. Program ini diharapkan menjadi langkah konkret dalam membentuk generasi pelajar yang tidak hanya cakap teknologi, tetapi juga memiliki kemampuan komunikasi ilmiah yang mumpuni dan bertanggung jawab.\r\n\r\n', 'literasi.jpg', 3, 1, '2025-05-11 01:24:10', '2025-05-11 01:24:10', '2025-05-18 12:37:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `day_of_week` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `room` varchar(50) DEFAULT NULL,
  `academic_year` varchar(9) NOT NULL,
  `semester` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `schedules`
--

INSERT INTO `schedules` (`id`, `class_id`, `subject_id`, `teacher_id`, `day_of_week`, `start_time`, `end_time`, `room`, `academic_year`, `semester`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 1, 'Senin', '07:00:00', '12:00:00', 'Ruang 5 MIPA 3', '2023/2024', 1, '2025-05-11 01:24:10', '2025-05-11 01:24:10'),
(2, 4, 2, 2, 'Selasa', '08:00:00', '09:30:00', 'Ruang 5 MIPA 3', '2023/2024', 1, '2025-05-11 01:24:10', '2025-05-11 01:24:10'),
(3, 4, 1, 1, 'Rabu', '07:00:00', '12:00:00', 'Ruang 5 MIPA 3', '2023/2024', 1, '2025-05-11 01:24:10', '2025-05-11 01:24:10'),
(4, 4, 2, 2, 'Kamis', '08:00:00', '09:30:00', 'Ruang 5 MIPA 3', '2023/2024', 1, '2025-05-11 01:24:10', '2025-05-11 01:24:10'),
(5, 4, 1, 1, 'Jumat', '07:00:00', '12:00:00', 'Ruang 5 MIPA 3', '2023/2024', 1, '2025-05-11 01:24:10', '2025-05-11 01:24:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Laki-laki','Perempuan') NOT NULL,
  `birth_place` varchar(100) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL COMMENT 'Menyimpan path/nama file gambar profil siswa',
  `parent_name` varchar(100) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `year_enrolled` varchar(4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `students`
--

INSERT INTO `students` (`id`, `nis`, `name`, `email`, `password`, `gender`, `birth_place`, `birth_date`, `religion`, `address`, `phone_number`, `image`, `parent_name`, `class_id`, `year_enrolled`, `created_at`, `updated_at`) VALUES
(4, '2310631250024', 'Muhammad Rakha Syamputra', 'Muhammadrakhasyamputra@gmail.com', 'Rakha123', 'Laki-laki', 'Jakarta', '2005-05-20', 'Islam', 'Perum Graha Bagasasi Blok J1/24', '087871310560', 'img/Rakha.jpg', 'Syam', 4, '2024', '2025-05-17 10:48:37', '2025-05-20 00:46:25'),
(5, '2310631250055', 'Farih Rahmatullah', 'farihrahmatullah453@gmail.com', 'Admin321', 'Laki-laki', 'Boyolali', '2005-05-17', 'Islam', 'Kp Cijambe Desa Sukadami', '085772244264', 'img/Farih.jpg', 'Goku', 4, '2024', '2025-05-17 10:48:37', '2025-05-20 00:46:36'),
(6, '2310631250082', 'Adinda Khaerunnisa', 'khaerunnisaadinda22@gmail.com', 'Dinda123', 'Perempuan', 'Jakarta', '2005-05-01', 'Islam', 'Jln Teluk Nimbung, Blok D.5 No.12', '089504506935', 'img/Dinda.jpg', 'prem', 4, '2024', '2025-05-17 10:48:37', '2025-05-20 00:46:49'),
(7, '2310631250051', 'Fadilah Nur Lutfah', 'fadilahnurlutfah@gmail.com', '1234', 'Perempuan', 'Jakarta', '2005-06-03', 'Islam', 'Perum Mahkota Blok D1/01', '081294130781', 'img/Fadillah.jpg', 'yoi', 4, '2024', '2025-05-17 10:48:37', '2025-05-20 00:47:02'),
(8, '2310631250052', 'Adriano', 'Adriano@gmail.com', 'Adriano123', 'Laki-laki', 'Jakarta', '2005-06-03', 'Islam', 'Perumnas Blok Z1/10', '089655626373', '', 'Asep', 4, '2024', '2025-05-17 10:48:37', '2025-05-20 00:48:18'),
(9, '2310631250100', 'Rachman Darmawan', 'rachmandarmawan@gmail.com', 'Rachman123', 'Laki-laki', 'Jakarta', '2005-04-12', 'Islam', 'Jl. Kebon Jeruk No. 10, Jakarta Barat', '081245678901', '', 'Darmawan Susilo', 1, '2023', '2025-05-20 01:19:53', '2025-05-20 01:26:08'),
(10, '2310631250101', 'Anisa Putri', 'anisaputri@gmail.com', 'Anisa123', 'Perempuan', 'Bogor', '2005-07-23', 'Islam', 'Perum Vila Dago Blok C8/15, Bogor', '085712345678', '', 'Handoko Putri', 1, '2023', '2025-05-20 01:19:53', '2025-05-20 01:26:08'),
(11, '2310631250102', 'Firman Wijaya', 'firmanwijaya@gmail.com', 'Firman123', 'Laki-laki', 'Tangerang', '2005-02-18', 'Islam', 'Jl. Flamboyan No. 7, Tangerang', '089834567812', '', 'Wijaya Kusuma', 1, '2023', '2025-05-20 01:19:53', '2025-05-20 01:26:08'),
(12, '2310631250103', 'Dinda Permata', 'dindapermata@gmail.com', 'Dinda123', 'Perempuan', 'Bandung', '2005-09-05', 'Islam', 'Jl. Sukajadi No. 45, Bandung', '081356789023', '', 'Permata Hidayat', 2, '2023', '2025-05-20 01:19:53', '2025-05-20 01:26:08'),
(13, '2310631250104', 'Rizky Pratama', 'rizkypratama@gmail.com', 'Rizky123', 'Laki-laki', 'Cirebon', '2005-11-14', 'Islam', 'Perum Harapan Indah Blok D2/10, Cirebon', '087823456789', '', 'Pratama Wijaya', 2, '2023', '2025-05-20 01:19:53', '2025-05-20 01:26:08'),
(14, '2310631250105', 'Siti Rahayu', 'sitirahayu@gmail.com', 'Siti123', 'Perempuan', 'Surabaya', '2005-06-30', 'Islam', 'Jl. Pemuda No. 123, Surabaya', '082145678901', '', 'Rahayu Santoso', 2, '2023', '2025-05-20 01:19:53', '2025-05-20 01:26:08'),
(15, '2310631250106', 'Ahmad Fauzan', 'ahmadfauzan@gmail.com', 'Ahmad123', 'Laki-laki', 'Malang', '2005-03-17', 'Islam', 'Jl. Soekarno Hatta No. 78, Malang', '081567890123', '', 'Fauzan Akbar', 3, '2023', '2025-05-20 01:19:53', '2025-05-20 01:26:08'),
(16, '2310631250107', 'Putri Ananda', 'putriananda@gmail.com', 'Putri123', 'Perempuan', 'Yogyakarta', '2005-05-25', 'Islam', 'Perum Candi Gebang Blok A4/5, Yogyakarta', '089876543210', '', 'Ananda Purnama', 3, '2023', '2025-05-20 01:19:53', '2025-05-20 01:26:08'),
(17, '2310631250108', 'Budi Setiawan', 'budisetiawan@gmail.com', 'Budi123', 'Laki-laki', 'Solo', '2005-01-10', '', 'Jl. Pahlawan No. 15, Solo', '087712345678', '', 'Setiawan Hadi', 3, '2023', '2025-05-20 01:19:53', '2025-05-20 01:28:15'),
(18, '2310631250109', 'Maya Indah', 'mayaindah@gmail.com', 'Maya123', 'Perempuan', 'Semarang', '2005-08-20', 'Islam', 'Jl. Diponegoro No. 56, Semarang', '081234567890', '', 'Indah Permata', 5, '2024', '2025-05-20 01:19:53', '2025-05-20 01:28:15'),
(19, '2310631250110', 'Farhan Pratama', 'farhanpratama@gmail.com', 'Farhan123', 'Laki-laki', 'Bekasi', '2005-10-15', 'Islam', 'Perum Grand Wisata Blok F7/9, Bekasi', '085678901234', '', 'Pratama Surya', 5, '2024', '2025-05-20 01:19:53', '2025-05-20 01:28:15'),
(20, '2310631250111', 'Devi Anggraini', 'devianggraini@gmail.com', 'Devi123', 'Perempuan', 'Depok', '2005-04-28', 'Islam', 'Jl. Margonda Raya No. 100, Depok', '089612345678', '', 'Anggraini Dewi', 5, '2024', '2025-05-20 01:19:53', '2025-05-20 01:28:15'),
(21, '2310631250112', 'Reza Mahendra', 'rezamahendra@gmail.com', 'Reza123', 'Laki-laki', 'Medan', '2005-07-19', 'Islam', 'Jl. Gatot Subroto No. 35, Medan', '081345678912', '', 'Mahendra Putra', 6, '2024', '2025-05-20 01:19:53', '2025-05-20 01:28:15'),
(22, '2310631250113', 'Nadia Safitri', 'nadiasafitri@gmail.com', 'Nadia123', 'Perempuan', 'Palembang', '2005-12-05', 'Islam', 'Perum Bukit Sejahtera Blok B3/17, Palembang', '087834567890', '', 'Safitri Wulandari', 6, '2024', '2025-05-20 01:19:53', '2025-05-20 01:28:15'),
(23, '2310631250114', 'Galih Purnomo', 'galihpurnomo@gmail.com', 'Galih123', 'Laki-laki', 'Makassar', '2005-02-22', 'Islam', 'Jl. Veteran No. 88, Makassar', '082156789012', '', 'Purnomo Santoso', 6, '2024', '2025-05-20 01:19:53', '2025-05-20 01:28:15'),
(24, '2310631250115', 'Dewi Kartika', 'dewikartika@gmail.com', 'Dewi123', 'Perempuan', 'Jakarta', '2005-09-27', 'Islam', 'Jl. Menteng Raya No. 50, Jakarta Pusat', '081590876543', '', 'Kartika Sari', 1, '2023', '2025-05-20 01:19:53', '2025-05-20 01:28:15'),
(25, '2310631250116', 'Adi Nugroho', 'adinugroho@gmail.com', 'Adi123', 'Laki-laki', 'Bekasi', '2005-05-31', 'Islam', '', '087843219876', '', 'Nugroho Santoso', 1, '2023', '2025-05-20 01:19:53', '2025-05-20 01:28:55'),
(26, '2310631250117', 'Linda Wati', 'lindawati@gmail.com', 'Linda123', 'Perempuan', 'Bandung', '2005-04-14', 'Islam', 'Jl. Dago No. 75, Bandung', '089832165478', '', 'Wati Susanto', 2, '2023', '2025-05-20 01:19:53', '2025-05-20 01:28:55'),
(27, '2310631250118', 'Hadi Purnama', 'hadipurnama@gmail.com', 'Hadi123', 'Laki-laki', 'Garut', '2005-08-09', 'Islam', 'Jl. Ahmad Yani No. 30, Garut', '081276543210', '', 'Purnama Jaya', 2, '2023', '2025-05-20 01:19:53', '2025-05-20 01:28:55'),
(28, '2310631250119', 'Indah Permatasari', 'indahpermatasari@gmail.com', 'Indah123', 'Perempuan', 'Semarang', '2005-10-22', 'Islam', 'Jl. Pemuda No. 45, Semarang', '085732109876', '', 'Permatasari Dewi', 3, '2023', '2025-05-20 01:19:53', '2025-05-20 01:23:35'),
(29, '2310631250120', 'Dani Setiawan', 'danisetiawan@gmail.com', 'Dani123', 'Laki-laki', 'Surabaya', '2005-03-08', 'Islam', 'Perum Taman Pondok Indah Blok C5/11, Surabaya', '082187654321', 'img/Dani.jpg', 'Setiawan Putra', 3, '2023', '2025-05-20 01:19:53', '2025-05-20 01:19:53'),
(30, '2310631250121', 'Nova Fitriani', 'novafitriani@gmail.com', 'Nova123', 'Perempuan', 'Bogor', '2005-01-15', 'Islam', 'Jl. Pajajaran No. 20, Bogor', '087865432109', 'img/Nova.jpg', 'Fitriani Dewi', 5, '2024', '2025-05-20 01:19:53', '2025-05-20 01:19:53'),
(31, '2310631250122', 'Andri Wibowo', 'andriwibowo@gmail.com', 'Andri123', 'Laki-laki', 'Tangerang', '2005-06-18', 'Islam', 'Perum Citra Raya Blok F2/8, Tangerang', '081298765432', 'img/Andri.jpg', 'Wibowo Santoso', 5, '2024', '2025-05-20 01:19:53', '2025-05-20 01:19:53'),
(32, '2310631250123', 'Rina Wahyuni', 'rinawahyuni@gmail.com', 'Rina123', 'Perempuan', 'Padang', '2005-11-12', 'Islam', 'Jl. Sudirman No. 65, Padang', '089845678921', 'img/Rina.jpg', 'Wahyuni Putri', 6, '2024', '2025-05-20 01:19:53', '2025-05-20 01:19:53'),
(33, '2310631250124', 'Tono Sudarso', 'tonosudarso@gmail.com', 'Tono123', 'Laki-laki', 'Manado', '2005-07-05', 'Kristen', 'Jl. Sam Ratulangi No. 40, Manado', '081387654321', 'img/Tono.jpg', 'Sudarso Wijaya', 6, '2024', '2025-05-20 01:19:53', '2025-05-20 01:19:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `curriculum` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `subjects`
--

INSERT INTO `subjects` (`id`, `code`, `name`, `curriculum`, `created_at`, `updated_at`) VALUES
(1, 'MTK14827', 'Matematika', 'Kurikulum Merdeka', '2025-05-11 01:24:10', '2025-05-11 01:24:10'),
(2, 'IPA18327', 'Ilmu Pengetahuan Alam', 'Kurikulum Merdeka', '2025-05-11 01:24:10', '2025-05-11 01:24:10'),
(3, 'IPS12427', 'Ilmu Pengetahuan Sosial', 'Kurikulum Merdeka', '2025-05-11 01:24:10', '2025-05-11 01:24:10'),
(4, 'BIG11227', 'Bahasa Inggris', 'Kurikulum Merdeka', '2025-05-11 01:24:10', '2025-05-11 01:24:10'),
(5, 'BIN10127', 'Bahasa Indonesia', 'Kurikulum Merdeka', '2025-05-11 01:24:10', '2025-05-11 01:24:10'),
(6, 'FIS13527', 'Fisika', 'Kurikulum Merdeka', '2025-05-11 01:24:10', '2025-05-11 01:24:10'),
(7, 'BIO13627', 'Biologi', 'Kurikulum Merdeka', '2025-05-11 01:24:10', '2025-05-11 01:24:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Laki-laki','Perempuan') NOT NULL,
  `birth_place` varchar(100) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `teachers`
--

INSERT INTO `teachers` (`id`, `nip`, `name`, `email`, `password`, `gender`, `birth_place`, `birth_date`, `religion`, `address`, `phone_number`, `status`, `created_at`, `updated_at`) VALUES
(1, '198501152010011001', 'Muhammad Basho Syampurno', 'basho@gmail.com', 'admin', 'Laki-laki', 'Jakarta', '1985-01-15', 'Islam', 'Jl. Anggrek No. 123, Jakarta', '081234567890', 'PNS', '2025-05-11 01:24:10', '2025-05-18 14:05:48'),
(2, '198705242011012001', 'Sri Wahyuni', 'sri@gmail.com', 'admin', 'Perempuan', 'Bandung', '1987-05-24', 'Islam', NULL, '08234567890', 'PNS', '2025-05-11 01:24:10', '2025-05-18 14:06:05'),
(3, '199003122012011001', 'Dodi Pratama', 'dodi@gmail.com', 'admin', 'Laki-laki', 'Surabaya', '1990-03-12', 'Islam', NULL, '08345678901', 'Honorer', '2025-05-11 01:24:10', '2025-05-18 14:06:20'),
(4, '199505222025011001', 'Muhammad Rakha Syamputra', 'rakha@gmail.com', 'admin', 'Laki-laki', 'Jakarta', '2005-05-20', 'Islam', 'Jl. Merdeka No. 45, Jakarta', '087871310560', 'PNS', '2025-05-22 15:02:42', '2025-05-22 15:03:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `teacher_subjects`
--

CREATE TABLE `teacher_subjects` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `academic_year` varchar(9) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `teacher_subjects`
--

INSERT INTO `teacher_subjects` (`id`, `teacher_id`, `subject_id`, `academic_year`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2023/2024', '2025-05-11 01:24:10', '2025-05-11 01:24:10'),
(2, 2, 2, '2023/2024', '2025-05-11 01:24:10', '2025-05-11 01:24:10'),
(3, 3, 4, '2023/2024', '2025-05-11 01:24:10', '2025-05-11 01:24:10');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indeks untuk tabel `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `schedule_id` (`schedule_id`);

--
-- Indeks untuk tabel `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `idx_grades_student` (`student_id`);

--
-- Indeks untuk tabel `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indeks untuk tabel `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `idx_schedules_class` (`class_id`);

--
-- Indeks untuk tabel `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_student_email` (`email`),
  ADD KEY `idx_student_class` (`class_id`);

--
-- Indeks untuk tabel `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indeks untuk tabel `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT untuk tabel `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignments_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignments_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedules_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedules_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`);

--
-- Ketidakleluasaan untuk tabel `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  ADD CONSTRAINT `teacher_subjects_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
