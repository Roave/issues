SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `issue` (
  `issue_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `status` ENUM('open','closed') NOT NULL DEFAULT 'open',
  `project` INT(11) UNSIGNED NOT NULL,
  `created_by` INT(11) UNSIGNED NOT NULL,
  `assigned_to` INT(11) UNSIGNED DEFAULT NULL,
  `created_time` DATETIME NOT NULL,
  `last_update_time` DATETIME DEFAULT NULL,
  `private` BOOLEAN NOT NULL DEFAULT '0',
  PRIMARY KEY (`issue_id`),
  KEY `status_assignee` (`status`, `assigned_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `project` (
  `project_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `private` BOOLEAN NOT NULL DEFAULT '0',
  PRIMARY KEY (`project_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user` (
  `user_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `password` CHAR(128) NOT NULL,
  `salt` BINARY(16) NOT NULL,
  `last_login` DATETIME DEFAULT NULL,
  `last_ip` INT(11) DEFAULT NULL,
  `register_time` DATETIME NOT NULL,
  `register_ip` INT(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user_settings` (
  `user_id` INT(11) UNSIGNED NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  `value` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user_role` (
  `role_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `weight` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`role_id`),
  KEY `weight` (`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=4;

CREATE TABLE `user_role_linker` (
  `user_id` INT(11) UNSIGNED NOT NULL,
  `role_id` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`)
) ENGINE=InnoDB;

CREATE TABLE `label` (
  `label_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `text` VARCHAR(50) NOT NULL,
  `color` VARCHAR(50) NOT NULL,
  `private` BOOLEAN NOT NULL DEFAULT '0'
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `issue_label_linker` (
  `issue_id` INT(11) UNSIGNED NOT NULL,
  `label_id` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`issue_id`,`label_id`)
) ENGINE=InnoDB;

CREATE TABLE `issue_milestone_linker` (
  `issue_id` INT(11) UNSIGNED NOT NULL,
  `milestone_id` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`issue_id`,`milestone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `milestone` (
  `milestone_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `due_date` DATETIME DEFAULT NULL,
  `completed_date` DATETIME DEFAULT NULL,
  `private` BOOLEAN NOT NULL DEFAULT '0',
  PRIMARY KEY (`milestone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `comment` (
  `comment_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_time` DATETIME NOT NULL,
  `created_by` INT(11) UNSIGNED NOT NULL,
  `issue` INT(11) UNSIGNED NOT NULL,
  `text` text NOT NULL,
  `private` BOOLEAN NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `acl_record` (
  `role_id` INT(11) UNSIGNED NOT NULL,
  `resource` VARCHAR(255) DEFAULT NULL,
  `action` VARCHAR(255) DEFAULT NULL,
  `type` ENUM('allow','deny') NOT NULL DEFAULT 'allow',
  UNIQUE KEY `role_resource_action` (`role_id`,`resource`,`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `acl_resource_record` (
  `role_id` INT(11) UNSIGNED NOT NULL,
  `resource_type` VARCHAR(255) NOT NULL,
  `resource_id` INT(11) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`resource_type`,`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `issue_history` (
  `issue_id` INT(11) UNSIGNED NOT NULL,
  `revision_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `revision_author` INT(11) UNSIGNED NULL,
  `revision_time` DATETIME NOT NULL,
  `action` VARCHAR(255) NOT NULL COMMENT 'update/delete/add-label/etc',
  `field` VARCHAR(255) NULL,
  `old_value` LONGTEXT NULL,
  `new_value` LONGTEXT NULL,
  PRIMARY KEY (`issue_id`,`revision_id`),
  KEY (`revision_id`),
  KEY (`revision_author`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `user_role_linker`
ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `user_role_linker`
ADD FOREIGN KEY (`role_id`) REFERENCES `user_role` (`role_id`)
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `user_settings`
ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
ON DELETE CASCADE ON UPDATE CASCADE;

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

ALTER TABLE `acl_record`
ADD FOREIGN KEY (`role_id`) REFERENCES `user_role` (`role_id`)
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `acl_resource_record`
ADD FOREIGN KEY (`role_id`) REFERENCES `user_role` (`role_id`)
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `issue_history`
ADD FOREIGN KEY (`issue_id`) REFERENCES `issue` (`issue_id`)
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `issue_history`
ADD FOREIGN KEY (`revision_author`) REFERENCES `user` (`user_id`)
ON DELETE SET NULL ON UPDATE SET NULL;

INSERT INTO `user_role` (`role_id`,`name`,`weight`) VALUES
(1, 'guest', 0),
(2, 'admin', 99),
(3, 'user',  10);

INSERT INTO `acl_record` (`role_id`, `resource`, `action`, `type`) VALUES
-- GUEST PERMISSIONS
(1,  NULL,         'view',          'allow'),
(1,  NULL,         'list',          'allow'),
(1,  'user',       'login',         'allow'),
(1,  'user',       'register',      'allow'),
-- ADMIN PERMISSIONS
(2,  NULL,         NULL,            'allow'),
-- USER PERMISSIONS
(3,  'user',       'login',         'deny'),
(3,  'user',       'register',      'deny'),
(3,  'user',       'logout',        'allow'),
(3,  'issue',      'create',        'allow'),
(3,  'issue',      'edit-own',      'allow'),
(3,  'issue',      'label-own',     'allow'),
(3,  'issue',      'comment',       'allow'),
(3,  'comment',    'edit-own',      'allow'),
(3,  'comment',    'edit-all',      'allow'),
(3,  'comment',    'delete-own',    'allow'),
(3,  'label',      'create',        'allow'),
(3,  'milestone',  'create',        'allow'),
(3,  'milestone',  'add-issue',     'allow'),
(3,  'project',    'view',          'allow');
