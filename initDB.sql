CREATE DATABASE `manga` CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci';

SET NAMES utf8mb4;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `manga`;
CREATE TABLE `manga`  (
  `id_manga` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(180) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `mangaka` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `id_genre` tinyint(1) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id_manga`) USING BTREE,
  UNIQUE INDEX `unique_manga`(`name`, `mangaka`) USING BTREE COMMENT 'Unique manga name of mangaka',
  INDEX `fk_type_delete_setnull`(`id_genre`) USING BTREE,
  CONSTRAINT `fk_type_delete_setnull` FOREIGN KEY (`id_genre`) REFERENCES `ref_genre` (`id_genre`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

DROP TABLE IF EXISTS `manga_categorie`;
CREATE TABLE `manga_categorie`  (
  `id_manga_genre` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_manga` smallint(5) UNSIGNED NOT NULL,
  `id_categorie` smallint(3) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_manga_genre`) USING BTREE,
  UNIQUE INDEX `unique_genre_manga`(`id_manga`, `id_categorie`) USING BTREE COMMENT 'Unique genre apply for each manga (but more than 1 genre can be add for same)',
  INDEX `fk_refgenre_delete_categorie`(`id_categorie`) USING BTREE,
  CONSTRAINT `fk_refgenre_delete_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `ref_categorie` (`id_categorie`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

DROP TABLE IF EXISTS `manga_tome`;
CREATE TABLE `manga_tome`  (
  `id_tome` int(7) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_manga` smallint(5) UNSIGNED NOT NULL,
  `number` smallint(3) UNSIGNED NOT NULL,
  `image` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_tome`) USING BTREE,
  UNIQUE INDEX `unique_tome`(`id_manga`, `number`) USING BTREE COMMENT 'only 1 tome of each number for each manga',
  UNIQUE INDEX `unique_cover`(`image`) USING BTREE COMMENT 'Cant have same image for more than 1 tome',
  CONSTRAINT `fk_manga_delete_tome` FOREIGN KEY (`id_manga`) REFERENCES `manga` (`id_manga`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

DROP TABLE IF EXISTS `ref_categorie`;
CREATE TABLE `ref_categorie`  (
  `id_categorie` smallint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `label` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_categorie`) USING BTREE,
  UNIQUE INDEX `unique_genre_label`(`label`) USING BTREE COMMENT 'no duplique genre'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

DROP TABLE IF EXISTS `ref_genre`;
CREATE TABLE `ref_genre`  (
  `id_genre` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `label` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_genre`) USING BTREE,
  UNIQUE INDEX `unique_type_label`(`label`) USING BTREE COMMENT 'no duplique type'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

DROP TABLE IF EXISTS `ref_tome_status`;
CREATE TABLE `ref_tome_status`  (
  `id_tome_status` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `label` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_tome_status`) USING BTREE,
  UNIQUE INDEX `unique_status_label`(`label`) USING BTREE COMMENT 'no duplique state'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id_user` int(7) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0: disabled, 1: activ',
  PRIMARY KEY (`id_user`) USING BTREE,
  UNIQUE INDEX `unique_user`(`pseudo`) USING BTREE COMMENT 'Unique user name'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

DROP TABLE IF EXISTS `user_manga`;
CREATE TABLE `user_manga`  (
  `id_user_manga` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_user` int(7) UNSIGNED NOT NULL,
  `id_manga` smallint(5) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_user_manga`) USING BTREE,
  UNIQUE INDEX `unique_user_manga`(`id_user`, `id_manga`) USING BTREE COMMENT 'Unique manga for each user',
  INDEX `fk_manga_delete_user_manga`(`id_manga`) USING BTREE,
  CONSTRAINT `fk_manga_delete_user_manga` FOREIGN KEY (`id_manga`) REFERENCES `manga` (`id_manga`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_user_delete_user_manga` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

DROP TABLE IF EXISTS `user_tome`;
CREATE TABLE `user_tome`  (
  `id_user_tome` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_user_manga` int(10) UNSIGNED NOT NULL,
  `id_tome` int(7) UNSIGNED NOT NULL,
  `id_tome_status` tinyint(1) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_user_tome`) USING BTREE,
  UNIQUE INDEX `unique_user_tome`(`id_user_manga`, `id_tome`) USING BTREE COMMENT 'Unique tome for each user',
  INDEX `fk_tome_delete_user_tome`(`id_tome`) USING BTREE,
  INDEX `fk_ref_tome_status_delete_user_tome`(`id_tome_status`) USING BTREE,
  CONSTRAINT `fk_ref_tome_status_delete_user_tome` FOREIGN KEY (`id_tome_status`) REFERENCES `ref_tome_status` (`id_tome_status`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_tome_delete_user_tome` FOREIGN KEY (`id_tome`) REFERENCES `manga_tome` (`id_tome`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_user_manga_delete_user_tome` FOREIGN KEY (`id_user_manga`) REFERENCES `user_manga` (`id_user_manga`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;