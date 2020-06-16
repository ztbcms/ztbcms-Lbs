DROP TABLE IF EXISTS `cms_lbs_config_tencent`;
CREATE TABLE `cms_lbs_config_tencent` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(128) DEFAULT NULL,
  `secret_key` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4;

INSERT INTO `cms_lbs_config_tencent` (`id`, `key`, `secret_key`)
VALUES
	(1, '4LHBZ-R7RKG-FVAQO-I63RU-M3XKF-73BHZ', NULL);
	(2, 'MDNBZ-KIOCW-T6PRJ-OUNPH-VFQ6H-EKBEP', NULL);


DROP TABLE IF EXISTS `cms_lbs_address_info`;
CREATE TABLE `cms_lbs_address_info`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `formatted_addresses` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `lat` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `lng` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ad_info` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  `update_time` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `address`(`address`) USING BTREE
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

DROP TABLE IF EXISTS `cms_lbs_config`;
CREATE TABLE `cms_lbs_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `cms_lbs_config` VALUES (1, 'time', '30', '地址缓存更新时间(天)');

DROP TABLE IF EXISTS `cms_lbs_geohash`;
CREATE TABLE `cms_lbs_geohash` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `target_type` varchar(32) NOT NULL DEFAULT '' COMMENT '地点类型',
  `target_id` varchar(32) NOT NULL DEFAULT '' COMMENT '地点唯一标志',
  `latitude` varchar(32) NOT NULL DEFAULT '' COMMENT '纬度',
  `longitude` varchar(32) NOT NULL DEFAULT '' COMMENT '经度',
  `geohash` varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `target_type` (`target_type`),
  KEY `geohash` (`geohash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4;