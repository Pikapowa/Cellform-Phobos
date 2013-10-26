# Cellform SQL Tables
#
# Installation

SET time_zone = "+00:00";

#table: 'cellform_alerts'
CREATE TABLE IF NOT EXISTS `cellform_alerts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `post_id` int(10) NOT NULL,
  `date` datetime DEFAULT NULL,
  `user` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


#table: 'cellform_coms'
CREATE TABLE IF NOT EXISTS `cellform_coms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `msg` varchar(1024) NOT NULL,
  `date` datetime DEFAULT NULL,
  `score` mediumint(10) DEFAULT NULL,
  `user` varchar(64) NOT NULL,
  `post_id` mediumint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


#table: 'cellform_comsvote'
CREATE TABLE IF NOT EXISTS `cellform_comsvote` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `com_id` int(10) NOT NULL,
  `uservoted` char(64) NOT NULL,
  `vote` char(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


#table: 'cellform_favorites'
CREATE TABLE IF NOT EXISTS `cellform_favorites` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `post_id` int(10) NOT NULL,
  `user` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


#table: 'cellform_friends'
CREATE TABLE IF NOT EXISTS `cellform_friends` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` char(64) NOT NULL,
  `friends` char(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


#table: 'cellform_mp'
CREATE TABLE IF NOT EXISTS `cellform_mp` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) DEFAULT NULL,
  `username` char(64) NOT NULL,
  `username_d` char(64) NOT NULL,
  `message` text NOT NULL,
  `date` datetime DEFAULT NULL,
  `useread` char(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


#table: 'cellform_posts'
CREATE TABLE IF NOT EXISTS `cellform_posts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `title` varchar(128) NOT NULL,
  `image` char(128) NOT NULL,
  `media` varchar(512) NOT NULL,
  `phone` varchar(125) NOT NULL,
  `date` datetime DEFAULT NULL,
  `nbcomments` int(10) DEFAULT NULL,
  `score` mediumint(10) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `user` char(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


#table: 'cellform_postsvote'
CREATE TABLE IF NOT EXISTS `cellform_postsvote` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `post_id` int(10) NOT NULL,
  `uservoted` char(64) NOT NULL,
  `vote` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


#table: 'cellform_users'
CREATE TABLE IF NOT EXISTS `cellform_users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` char(64) NOT NULL,
  `username` char(64) NOT NULL,
  `password` char(128) NOT NULL,
  `avatar` varchar(128) NOT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `valid` tinyint(1) DEFAULT NULL,
  `score` mediumint(10) DEFAULT NULL,
  `likes` int(32) NOT NULL,
  `regdate` datetime DEFAULT NULL,
  `lastvisit` datetime DEFAULT NULL,
  `level` int(10) NOT NULL,
  `token` char(128) DEFAULT 1,
  `forget` char(128) DEFAULT 1,
  `oauth_id` decimal(24) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
