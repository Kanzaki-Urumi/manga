CREATE DATABASE `manga` CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci';

CREATE TABLE `manga`.`ref_genre`  (
  `id_genre` smallint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `label` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_genre`),
  UNIQUE INDEX `unique_genre_label`(`label`) USING BTREE COMMENT 'no duplique genre'
) ENGINE = InnoDB;

CREATE TABLE `manga`.`ref_type`  (
  `id_type` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `label` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_type`),
  UNIQUE INDEX `unique_type_label`(`label`) USING BTREE COMMENT 'no duplique type'
) ENGINE = InnoDB;

CREATE TABLE `manga`.`ref_tome_status`  (
  `id_tome_status` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `label` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_tome_status`),
  UNIQUE INDEX `unique_status_label`(`label`) USING BTREE COMMENT 'no duplique state'
) ENGINE = InnoDB;

CREATE TABLE `manga`.`manga`  (
    `id_manga` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(180) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `mangaka` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `id_type` tinyint(1)  UNSIGNED NULL DEFAULT NULL,
    PRIMARY KEY (`id_manga`),
    UNIQUE INDEX `unique_manga`(`name`, `mangaka`) USING BTREE COMMENT 'Unique manga name of mangaka',
    CONSTRAINT `fk_type_delete_setnull` FOREIGN KEY (`id_type`) REFERENCES `manga`.`ref_type` (`id_type`) ON DELETE SET NULL
) ENGINE = InnoDB;

CREATE TABLE `manga`.`manga_tome`  (
    `id_tome` int(7) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_manga` smallint(5) UNSIGNED NOT NULL,
    `number` smallint(3) UNSIGNED NOT NULL,
    `image` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    PRIMARY KEY (`id_tome`),
    UNIQUE INDEX `unique_tome`(`id_manga`, `number`) USING BTREE COMMENT 'only 1 tome of each number for each manga',
    UNIQUE INDEX `unique_cover`(`image`) USING BTREE COMMENT 'Cant have same image for more than 1 tome',
    CONSTRAINT `fk_manga_delete_tome` FOREIGN KEY (`id_manga`) REFERENCES `manga`.`manga` (`id_manga`) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE `manga`.`manga_genre`  (
    `id_manga_genre` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_manga` smallint(5) UNSIGNED NOT NULL,
    `id_genre` smallint(3) UNSIGNED NOT NULL,
    PRIMARY KEY (`id_manga_genre`),
    UNIQUE INDEX `unique_genre_manga`(`id_manga`, `id_genre`) USING BTREE COMMENT 'Unique genre apply for each manga (but more than 1 genre can be add for same)',
    CONSTRAINT `fk_refgenre_delete_genre` FOREIGN KEY (`id_genre`) REFERENCES `manga`.`ref_genre` (`id_genre`) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE `manga`.`user`  (
    `id_user` int(7) UNSIGNED NOT NULL AUTO_INCREMENT,
    `pseudo` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0: disabled, 1: activ',
    PRIMARY KEY (`id_user`),
    UNIQUE INDEX `unique_user`(`pseudo`) USING BTREE COMMENT 'Unique user name'
) ENGINE = InnoDB;

CREATE TABLE `manga`.`user_manga`  (
    `id_user_manga` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_user` int(7) UNSIGNED NOT NULL,
    `id_manga` smallint(5) UNSIGNED NOT NULL,
    PRIMARY KEY (`id_user_manga`),
    UNIQUE INDEX `unique_user_manga`(`id_user`, `id_manga`) USING BTREE COMMENT 'Unique manga for each user',
    CONSTRAINT `fk_manga_delete_user_manga` FOREIGN KEY (`id_manga`) REFERENCES `manga`.`manga` (`id_manga`) ON DELETE CASCADE,
    CONSTRAINT `fk_user_delete_user_manga` FOREIGN KEY (`id_user`) REFERENCES `manga`.`user` (`id_user`) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE `manga`.`user_tome`  (
    `id_user_tome` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_user_manga` int(10) UNSIGNED NOT NULL,
    `id_tome` int(7) UNSIGNED NOT NULL,
    `id_tome_status` tinyint(1) UNSIGNED NOT NULL,
    PRIMARY KEY (`id_user_tome`),
    UNIQUE INDEX `unique_user_tome`(`id_user_manga`, `id_tome`) USING BTREE COMMENT 'Unique tome for each user',
    CONSTRAINT `fk_tome_delete_user_tome` FOREIGN KEY (`id_tome`) REFERENCES `manga`.`manga_tome` (`id_tome`) ON DELETE CASCADE,
    CONSTRAINT `fk_user_manga_delete_user_tome` FOREIGN KEY (`id_user_manga`) REFERENCES `manga`.`user_manga` (`id_user_manga`) ON DELETE CASCADE,
    CONSTRAINT `fk_ref_tome_status_delete_user_tome` FOREIGN KEY (`id_tome_status`) REFERENCES `manga`.`ref_tome_status` (`id_tome_status`) ON DELETE CASCADE
) ENGINE = InnoDB;