CREATE TABLE IF NOT EXISTS `nasa`
(
    `id`     INT          NOT NULL AUTO_INCREMENT,
    `name`   VARCHAR(255) NOT NULL,
    `weight` DOUBLE       NOT NULL,
    PRIMARY KEY (`id`)
) Engine = InnoDB;