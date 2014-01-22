SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `email_proxy`
-- ----------------------------
DROP TABLE IF EXISTS `email_proxy`;
CREATE TABLE `email_proxy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email_from` varchar(50) NOT NULL,
  `email_to` varchar(50) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `sent` int(1) NOT NULL DEFAULT '0' COMMENT '0 - unsent  1 - sent',
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of email_proxy
-- ----------------------------
