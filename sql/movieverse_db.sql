-- --------------------------------------------------------
-- 호스트:                          127.0.0.1
-- 서버 버전:                        10.4.24-MariaDB - mariadb.org binary distribution
-- 서버 OS:                        Win64
-- HeidiSQL 버전:                  12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- movieverse 데이터베이스 구조 내보내기
CREATE DATABASE IF NOT EXISTS `movieverse` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `movieverse`;

-- 테이블 movieverse.bookmark 구조 내보내기
CREATE TABLE IF NOT EXISTS `bookmark` (
  `bookmark_num` int(11) NOT NULL AUTO_INCREMENT,
  `member_num` int(11) DEFAULT NULL,
  `choice_num` int(11) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `choice` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`bookmark_num`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- 테이블 데이터 movieverse.bookmark:~0 rows (대략적) 내보내기
DELETE FROM `bookmark`;

-- 테이블 movieverse.review 구조 내보내기
CREATE TABLE IF NOT EXISTS `review` (
  `review_num` int(11) NOT NULL AUTO_INCREMENT,
  `review_date` date DEFAULT NULL,
  `choice_content` varchar(50) DEFAULT NULL,
  `content_id` varchar(50) DEFAULT NULL,
  `member_num` int(11) DEFAULT NULL,
  `review_content` varchar(50) DEFAULT NULL,
  `star_rating` float DEFAULT NULL,
  `like_num` int(11) DEFAULT NULL,
  PRIMARY KEY (`review_num`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- 테이블 데이터 movieverse.review:~0 rows (대략적) 내보내기
DELETE FROM `review`;
INSERT INTO `review` (`review_num`, `review_date`, `choice_content`, `content_id`, `member_num`, `review_content`, `star_rating`, `like_num`) VALUES
	(2, '2022-12-05', 'movie', '436270', 2, 'dsfdsfdsfsdfdsfsd', 3.25, 0);

-- 테이블 movieverse.review_like 구조 내보내기
CREATE TABLE IF NOT EXISTS `review_like` (
  `like_num` int(11) NOT NULL AUTO_INCREMENT,
  `like_user_num` int(11) DEFAULT NULL,
  `like_review_num` int(11) DEFAULT NULL,
  PRIMARY KEY (`like_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 테이블 데이터 movieverse.review_like:~0 rows (대략적) 내보내기
DELETE FROM `review_like`;

-- 테이블 movieverse.user 구조 내보내기
CREATE TABLE IF NOT EXISTS `user` (
  `member_num` int(11) NOT NULL AUTO_INCREMENT,
  `identification` varchar(50) DEFAULT NULL,
  `passwd` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `img_link` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`member_num`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- 테이블 데이터 movieverse.user:~2 rows (대략적) 내보내기
DELETE FROM `user`;
INSERT INTO `user` (`member_num`, `identification`, `passwd`, `email`, `nickname`, `join_date`, `img_link`) VALUES
	(1, NULL, '1234', 'aaaa', 'aaaa', '2022-10-13', '148131695_1_1635595485_w360.jpg'),
	(2, NULL, 'admin', 'admin', 'admin', '2022-10-13', 'sample.jpg');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
