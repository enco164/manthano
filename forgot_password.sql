/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50532
Source Host           : localhost:3306
Source Database       : lako_do_kola

Target Server Type    : MYSQL
Target Server Version : 50532
File Encoding         : 65001

Date: 2014-01-25 15:27:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `forgot_password`
-- ----------------------------
DROP TABLE IF EXISTS `forgot_password`;
CREATE TABLE `forgot_password` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hash` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of forgot_password
-- ----------------------------
