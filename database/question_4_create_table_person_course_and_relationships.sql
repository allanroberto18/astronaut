CREATE TABLE IF NOT EXISTS `person`
(
    `id`   INT          NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) Engine = InnoDB;

CREATE TABLE IF NOT EXISTS `course`
(
    `id`   INT          NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) Engine = InnoDB;

CREATE TABLE IF NOT EXISTS `person_course`
(
    `person_id` INT NOT NULL,
    `course_id` INT NOT NULL,
    PRIMARY KEY (`person_id`, `course_id`),
    CONSTRAINT `cons_person_course_person_fk`
        FOREIGN KEY `person_fk` (`person_id`) REFERENCES `person` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
    CONSTRAINT `cons_person_course_course_fk`
        FOREIGN KEY `course_fk` (`course_id`) REFERENCES `course` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
) Engine = InnoDB;