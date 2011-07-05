SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `issue` (
  `issue_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR( 255 ) NOT NULL,
  `description` TEXT NOT NULL,
  `status` ENUM( 'open' , 'closed' ) NOT NULL DEFAULT 'open',
  `project` INT( 11 ) UNSIGNED NOT NULL,
  `created_by` INT( 11 ) UNSIGNED NOT NULL,
  `assigned_to` INT( 11 ) UNSIGNED DEFAULT NULL,
  `created_time` DATETIME NOT NULL,
  `last_update_time` DATETIME DEFAULT NULL,
  PRIMARY KEY (`issue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `project` (
  `project_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR( 255) NOT NULL,
  PRIMARY KEY (`project_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user` (
  `user_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR( 255 ) NOT NULL,
  `password` VARCHAR( 255 ) NOT NULL,
  `last_login` DATETIME DEFAULT NULL,
  `last_ip` INT( 11 ) DEFAULT NULL,
  `register_time` DATETIME NOT NULL,
  `register_ip` INT( 11) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  KEY `username_password` (`username` , `password`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user_settings` (
  `user_id` INT(11) UNSIGNED NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  `value` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`name`)
) ENGINE=InnoDB;

CREATE TABLE `user_role` (
  `role_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR( 255) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

CREATE TABLE `user_role_linker` (
  `user_id` INT( 11 ) UNSIGNED NOT NULL ,
  `role_id` INT( 11 ) UNSIGNED NOT NULL ,
  PRIMARY KEY ( `user_id` , `role_id` )
) ENGINE = InnoDB;

CREATE TABLE `label` (
  `label_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `text` VARCHAR( 50 ) NOT NULL ,
  `color` VARCHAR( 50 ) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `issue_label_linker` (
  `issue_id` INT( 11 ) UNSIGNED NOT NULL ,
  `label_id` INT( 11 ) UNSIGNED NOT NULL ,
  PRIMARY KEY ( `issue_id` , `label_id` )
) ENGINE = InnoDB;

CREATE TABLE `issue_milestone_linker` (
  `issue_id` INT(11) UNSIGNED NOT NULL,
  `milestone_id` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`issue_id` , `milestone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `milestone` (
  `milestone_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `due_date` DATETIME DEFAULT NULL,
  `completed_date` DATETIME DEFAULT NULL,
  PRIMARY KEY (`milestone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `comment` (
  `comment_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_time` DATETIME NOT NULL,
  `created_by` INT( 11 ) UNSIGNED NOT NULL,
  `issue` INT( 11 ) UNSIGNED NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `user_role_linker`
ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE `user_role_linker`
ADD FOREIGN KEY (`role_id`) REFERENCES `user_role` (`role_id`)
ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE `user_settings`
ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE `issue_label_linker`
ADD FOREIGN KEY (`issue_id`) REFERENCES `issue` (`issue_id`)
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `issue_label_linker`
ADD FOREIGN KEY (`label_id`) REFERENCES `label` (`label_id`)
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `issue_milestone_linker`
ADD FOREIGN KEY (`milestone_id`) REFERENCES `milestone` (`milestone_id`)
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `issue_milestone_linker`
ADD FOREIGN KEY (`issue_id`) REFERENCES `issue` (`issue_id`)
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `comment`
ADD FOREIGN KEY (`issue`) REFERENCES `issue` (`issue_id`)
ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `user_role` (`role_id`, `name`) VALUES
(1, 'guest'),
(2, 'user');
