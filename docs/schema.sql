CREATE TABLE IF NOT EXISTS `issue` (
  `issue_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('open','closed') NOT NULL,
  `created_by` int(11) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `created_time` datetime NOT NULL,
  `last_update_time` datetime NOT NULL,
  PRIMARY KEY (`issue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `issue_group` (
  `issue_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_group_id` int(11) DEFAULT NULL,
  `type` enum('project','milestone','label') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'label',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`issue_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `issue_group_linker` (
  `issue_id` int(11) NOT NULL,
  `issue_group_id` int(11) NOT NULL,
  UNIQUE KEY `issue_id` (`issue_id`,`issue_group_id`),
  KEY `issue_id_2` (`issue_id`),
  KEY `issue_group_id` (`issue_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `password` varchar(64) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


ALTER TABLE `issue_group_linker`
  ADD CONSTRAINT `issue_group_linker_ibfk_1` FOREIGN KEY (`issue_id`) REFERENCES `issue` (`issue_id`),
  ADD CONSTRAINT `issue_group_linker_ibfk_2` FOREIGN KEY (`issue_group_id`) REFERENCES `issue_group` (`issue_group_id`);
