# phpMyAdmin SQL Dump
# version 2.5.5-pl1
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Oct 04, 2004 at 06:31 PM
# Server version: 4.0.20
# PHP Version: 4.3.8
# 
# Database : `bookmark`
# 

# --------------------------------------------------------

#
# Table structure for table `bookmarks`
#

DROP TABLE IF EXISTS `bookmarks`;
CREATE TABLE `bookmarks` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `folder_id` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `url` text NOT NULL,
  `user_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) TYPE=MyISAM AUTO_INCREMENT=9 ;

# --------------------------------------------------------

#
# Table structure for table `users`
#

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;
