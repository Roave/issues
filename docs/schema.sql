SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `issue` (
  `issue_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `project` int(11) UNSIGNED NOT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `assigned_to` int(11) UNSIGNED DEFAULT NULL,
  `created_time` datetime NOT NULL,
  `last_update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`issue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `project` (
  `project_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`project_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) UNSIGNED NOT NULL DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `last_ip` int(11) DEFAULT NULL,
  `register_time` datetime NOT NULL,
  `register_ip` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  KEY `username_password` (`username`,`password`),
  KEY `role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user_role` (
  `role_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE `issues`.`label` (
  `label_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `text` VARCHAR( 50 ) NOT NULL ,
  `color` VARCHAR( 50 ) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE `issues`.`issue_label_linker` (
  `issue_id` INT( 11 ) UNSIGNED NOT NULL ,
  `label_id` INT( 11 ) UNSIGNED NOT NULL ,
  PRIMARY KEY ( `issue_id` , `label_id` )
) ENGINE = InnoDB;

ALTER TABLE `issue_label_linker`
ADD FOREIGN KEY (`issue_id`) REFERENCES `issues`.`issue` (`issue_id`)
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `issue_label_linker`
ADD FOREIGN KEY (`label_id`) REFERENCES `issues`.`label` (`label_id`)
ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `user_role` (`role_id`, `name`) VALUES
(1, 'user');


ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role`) REFERENCES `user_role` (`role_id`);

