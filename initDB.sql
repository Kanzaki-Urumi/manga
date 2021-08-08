CREATE DATABASE `manga` CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci';
CREATE TABLE `manga`.`manga`  (
    `id_manga` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(180) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `mangaka` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    PRIMARY KEY (`id_manga`),
    UNIQUE INDEX `uniqueManga`(`name`, `mangaka`) USING BTREE COMMENT 'Unique manga name of mangaka'
) ENGINE = InnoDB;
CREATE TABLE `manga`.`manga_tome`  (
    `id_tome` smallint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
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
    `id_genre` tinyint(3) UNSIGNED NOT NULL,
    PRIMARY KEY (`id_manga_genre`),
    UNIQUE INDEX `unique_genre_manga`(`id_manga`, `id_genre`) USING BTREE COMMENT 'Unique genre apply for each manga (but more than 1 genre can be add for same)'
) ENGINE = InnoDB;
