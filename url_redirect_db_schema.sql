SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `access_log`
-- ----------------------------
DROP TABLE IF EXISTS `access_log`;
CREATE TABLE `access_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `redirect_id` int(11) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `ipaddr` varchar(255) DEFAULT NULL,
  `referer` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `device_type` varchar(255) DEFAULT NULL,
  `platform` varchar(255) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `time_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1906 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `redirects`
-- ----------------------------
DROP TABLE IF EXISTS `redirects`;
CREATE TABLE `redirects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` varchar(255) DEFAULT NULL,
  `shorturl` varchar(255) DEFAULT NULL,
  `redirect` varchar(255) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `enabled` char(50) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;

SET FOREIGN_KEY_CHECKS = 1;
