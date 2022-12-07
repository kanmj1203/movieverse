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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- 테이블 데이터 movieverse.review:~2 rows (대략적) 내보내기
DELETE FROM `review`;
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
INSERT INTO `review` (`review_num`, `review_date`, `content_id`, `choice_content`, `member_num`, `review_content`, `star_rating`, `like_num`) VALUES
	(4, '2022-12-06', 90462, 'tv', 2, '234', 5.25, 4),
	(5, '2022-12-07', 436270, 'movie', 2, '433', 3, 5);
/*!40000 ALTER TABLE `review` ENABLE KEYS */;

-- 테이블 movieverse.review_like 구조 내보내기
CREATE TABLE IF NOT EXISTS `review_like` (
  `like_num` int(11) NOT NULL AUTO_INCREMENT,
  `like_user_num` int(11) DEFAULT NULL,
  `like_review_num` int(11) DEFAULT NULL,
  PRIMARY KEY (`like_num`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

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
