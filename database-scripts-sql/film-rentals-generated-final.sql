SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema video_pujcovna
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `video_pujcovna`
  DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci;
USE `video_pujcovna`;

-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id`            INT AUTO_INCREMENT PRIMARY KEY,
  `email`         VARCHAR(255) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `login`         VARCHAR(255) NOT NULL,
  `name`          VARCHAR(100) NOT NULL,
  `surname`       VARCHAR(100) NOT NULL,
  `phone`         VARCHAR(45)  NOT NULL,
  `role`          ENUM('user','admin') NOT NULL DEFAULT 'user',
  `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uq_users_email` (`email`),
  UNIQUE KEY `uq_users_login` (`login`)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table `genres`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `genres` (
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL,
  UNIQUE KEY `uq_genres_name` (`name`)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table `movies`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `movies` (
  `id`           INT AUTO_INCREMENT PRIMARY KEY,
  `title`        VARCHAR(255) NOT NULL,
  `description`  TEXT,
  `release_year` YEAR,
  `rental_rate`  DECIMAL(6,2),
  `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `genre_id`     INT,
  INDEX   `idx_movies_genre` (`genre_id`),
  CONSTRAINT `fk_movies_genres`
    FOREIGN KEY (`genre_id`)
    REFERENCES `genres`(`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table `copies`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `copies` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `copy_number` VARCHAR(45),
  `status`      ENUM('dostupné','zapůjčené','ztraceno','údržba')
                   NOT NULL DEFAULT 'dostupné',
  `movie_id`    INT NOT NULL,
  INDEX `uq_copies_number`   (`copy_number`),
  INDEX `idx_copies_movie`    (`movie_id`),
  CONSTRAINT `fk_copies_movies`
    FOREIGN KEY (`movie_id`)
    REFERENCES `movies`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table `rentals`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `rentals` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `rental_date` DATETIME    NOT NULL,
  `due_date`    DATETIME    NOT NULL,
  `return_date` DATETIME,
  `rental_fee`  DECIMAL(6,2),
  `late_fee`    DECIMAL(6,2),
  `status`      ENUM('probíhá','vráceno','po splatnosti','zrušeno')
                   DEFAULT 'probíhá',
  `user_id`     INT         NOT NULL,
  `copy_id`     INT         NOT NULL,
  INDEX `idx_rentals_user`  (`user_id`),
  INDEX `idx_rentals_copy`  (`copy_id`),
  CONSTRAINT `fk_rentals_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users`(`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_rentals_copies`
    FOREIGN KEY (`copy_id`)
    REFERENCES `copies`(`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table `payments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `payments` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `amount`      DECIMAL(7,2),
  `method`      ENUM('hotově','kartou','paypal'),
  `payment_date` DATETIME,
  `rental_id`   INT NOT NULL,
  INDEX `idx_payments_rental` (`rental_id`),
  CONSTRAINT `fk_payments_rentals`
    FOREIGN KEY (`rental_id`)
    REFERENCES `rentals`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
