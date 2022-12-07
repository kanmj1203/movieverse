-- --------------------------------------------------------
-- 호스트:                          127.0.0.1
-- 서버 버전:                        10.4.17-MariaDB - mariadb.org binary distribution
-- 서버 OS:                        Win64
-- HeidiSQL 버전:                  11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- movieverse 데이터베이스 구조 내보내기
CREATE DATABASE IF NOT EXISTS `movieverse` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `movieverse`;

-- 테이블 movieverse.age 구조 내보내기
CREATE TABLE IF NOT EXISTS `age` (
  `age` int(11) NOT NULL AUTO_INCREMENT,
  `about` varchar(50) DEFAULT NULL,
  `age_imglink` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`age`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 테이블 데이터 movieverse.age:~0 rows (대략적) 내보내기
DELETE FROM `age`;
/*!40000 ALTER TABLE `age` DISABLE KEYS */;
/*!40000 ALTER TABLE `age` ENABLE KEYS */;

-- 테이블 movieverse.bookmark 구조 내보내기
CREATE TABLE IF NOT EXISTS `bookmark` (
  `bookmark_num` int(11) NOT NULL AUTO_INCREMENT,
  `member_num` int(11) DEFAULT NULL,
  `choice_num` int(11) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `choice` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`bookmark_num`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

-- 테이블 데이터 movieverse.bookmark:~0 rows (대략적) 내보내기
DELETE FROM `bookmark`;
/*!40000 ALTER TABLE `bookmark` DISABLE KEYS */;
/*!40000 ALTER TABLE `bookmark` ENABLE KEYS */;

-- 테이블 movieverse.genres 구조 내보내기
CREATE TABLE IF NOT EXISTS `genres` (
  `genre_id` int(11) NOT NULL AUTO_INCREMENT,
  `genres_name` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`genre_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 테이블 데이터 movieverse.genres:~0 rows (대략적) 내보내기
DELETE FROM `genres`;
/*!40000 ALTER TABLE `genres` DISABLE KEYS */;
/*!40000 ALTER TABLE `genres` ENABLE KEYS */;

-- 테이블 movieverse.movie 구조 내보내기
CREATE TABLE IF NOT EXISTS `movie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `original_title` varchar(200) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `poster_path` varchar(500) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `director` varchar(500) DEFAULT NULL,
  `cast` varchar(500) DEFAULT NULL,
  `overview` varchar(500) DEFAULT NULL,
  `original_language` varchar(50) DEFAULT NULL,
  `popularity` float DEFAULT NULL,
  `vote_average` float DEFAULT NULL,
  `vote_count` float DEFAULT NULL,
  `site_path` varchar(500) DEFAULT NULL,
  `backdrop_path` varchar(500) DEFAULT NULL,
  `video` tinyint(4) DEFAULT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 테이블 데이터 movieverse.movie:~0 rows (대략적) 내보내기
DELETE FROM `movie`;
/*!40000 ALTER TABLE `movie` DISABLE KEYS */;
/*!40000 ALTER TABLE `movie` ENABLE KEYS */;

-- 테이블 movieverse.review 구조 내보내기
CREATE TABLE IF NOT EXISTS `review` (
  `review_num` int(11) NOT NULL AUTO_INCREMENT,
  `review_date` date DEFAULT NULL,
  `content_id` int(11) DEFAULT NULL,
  `choice_content` varchar(50) DEFAULT NULL,
  `member_num` int(11) DEFAULT NULL,
  `review_content` varchar(50) DEFAULT NULL,
  `star_rating` float DEFAULT NULL,
  `like_num` int(11) DEFAULT NULL,
  PRIMARY KEY (`review_num`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- 테이블 데이터 movieverse.review:~0 rows (대략적) 내보내기
DELETE FROM `review`;
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
/*!40000 ALTER TABLE `review` ENABLE KEYS */;

-- 테이블 movieverse.review_like 구조 내보내기
CREATE TABLE IF NOT EXISTS `review_like` (
  `like_num` int(11) NOT NULL AUTO_INCREMENT,
  `like_user_num` int(11) DEFAULT NULL,
  `like_review_num` int(11) DEFAULT NULL,
  PRIMARY KEY (`like_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 테이블 데이터 movieverse.review_like:~0 rows (대략적) 내보내기
DELETE FROM `review_like`;
/*!40000 ALTER TABLE `review_like` DISABLE KEYS */;
/*!40000 ALTER TABLE `review_like` ENABLE KEYS */;

-- 테이블 movieverse.streaming 구조 내보내기
CREATE TABLE IF NOT EXISTS `streaming` (
  `provider_id` int(11) NOT NULL AUTO_INCREMENT,
  `display_priority` varchar(50) DEFAULT NULL,
  `logo_path` varchar(50) DEFAULT NULL,
  `provider_name` varchar(50) DEFAULT NULL,
  `provider_link` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`provider_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 테이블 데이터 movieverse.streaming:~0 rows (대략적) 내보내기
DELETE FROM `streaming`;
/*!40000 ALTER TABLE `streaming` DISABLE KEYS */;
/*!40000 ALTER TABLE `streaming` ENABLE KEYS */;

-- 테이블 movieverse.tv 구조 내보내기
CREATE TABLE IF NOT EXISTS `tv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `original_title` varchar(200) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `poster_path` varchar(500) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `director` varchar(500) DEFAULT NULL,
  `cast` varchar(500) DEFAULT NULL,
  `overview` varchar(500) DEFAULT NULL,
  `original_language` varchar(50) DEFAULT NULL,
  `popularity` float DEFAULT NULL,
  `vote_average` float DEFAULT NULL,
  `vote_count` float DEFAULT NULL,
  `site_path` varchar(500) DEFAULT NULL,
  `backdrop_path` varchar(500) DEFAULT NULL,
  `video` tinyint(4) DEFAULT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=94998 DEFAULT CHARSET=utf8mb4;

-- 테이블 데이터 movieverse.tv:~0 rows (대략적) 내보내기
DELETE FROM `tv`;
/*!40000 ALTER TABLE `tv` DISABLE KEYS */;
INSERT INTO `tv` (`id`, `original_title`, `title`, `poster_path`, `release_date`, `director`, `cast`, `overview`, `original_language`, `popularity`, `vote_average`, `vote_count`, `site_path`, `backdrop_path`, `video`, `genre_id`, `provider_id`, `age`) VALUES
	(94997, 'House of the Dragon', '하우스 오브 드래곤', 'https://www.themoviedb.org/t/p/w600_and_h900_bestv2/lUCsnWP5RHgA0lV0lfme6grpxIR.jpg', '2022-08-22', 'George R. R. Martin', 'Paddy Considine / Ma', '대륙을 지배하는 타르가르옌 가문의 왕 비세리스 1세의 어린 딸 라에니라와, 왕의 난폭한 동생 다에몬이 왕위 계승을 노리면서 음모와 배신, 피와 죽음으로 얼룩지는 길고 긴 싸움이 시작된다. 아무도 넘볼 수 없는 절대 권력, 왕위 계승을 둘러싼 가문의 내전이 벌어진다.\r\n\r\n2022년 8월 HBO에서 방영을 시작한, 조지 R.R. 마틴의 <얼음과 불의 노래> 시리즈의 외전 <불과 피>를 원작으로 하는 드라마.', 'en', 3.8, 1, 1, 'https://click.justwatch.com/a?cx=eyJzY2hlbWEiOiJpZ2x1OmNvbS5zbm93cGxvd2FuYWx5dGljcy5zbm93cGxvdy9jb250ZXh0cy9qc29uc2NoZW1hLzEtMC0wIiwiZGF0YSI6W3sic2NoZW1hIjoiaWdsdTpjb20uanVzdHdhdGNoL2NsaWNrb3V0X2NvbnRleHQvanNvbnNjaGVtYS8xLTItMCIsImRhdGEiOnsicHJvdmlkZXIiOiJ3YXZ2ZSIsIm1vbmV0aXphdGlvblR5cGUiOiJmbGF0cmF0ZSIsInByZXNlbnRhdGlvblR5cGUiOiJoZCIsImN1cnJlbmN5IjoiS1JXIiwicHJpY2UiOjAsIm9yaWdpbmFsUHJpY2UiOjAsImF1ZGlvTGFuZ3VhZ2UiOiIiLCJzdWJ0aXRsZUxhbmd1YWdlIjoiIiwiY2luZW1hSWQiOjAsInNob3d0aW1lIjoiIiwiaXNGYXZvcml', 'https://www.themoviedb.org/t/p/original/etj8E2o0Bud0HkONVQPjyCkIvpv.jpg', NULL, 18, 1, 15);
/*!40000 ALTER TABLE `tv` ENABLE KEYS */;

-- 테이블 movieverse.user 구조 내보내기
CREATE TABLE IF NOT EXISTS `user` (
  `member_num` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL,
  `bookmark_num` int(11) DEFAULT NULL,
  `passwd` varchar(50) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `img_link` varchar(500) DEFAULT NULL,
  `identification` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`member_num`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- 테이블 데이터 movieverse.user:~2 rows (대략적) 내보내기
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`member_num`, `email`, `bookmark_num`, `passwd`, `nickname`, `join_date`, `img_link`, `identification`) VALUES
	(1, 'aaaa', NULL, '1234', 'aaaa', '2022-10-13', '148131695_1_1635595485_w360.jpg', NULL),
	(2, 'admin', NULL, 'admin', 'admin', '2022-10-13', 'sample.jpg', NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
