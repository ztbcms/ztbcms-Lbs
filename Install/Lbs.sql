DROP TABLE IF EXISTS `cms_lbs_config_tencent`;
CREATE TABLE `cms_lbs_config_tencent` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(128) DEFAULT NULL,
  `secret_key` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `cms_lbs_config_tencent` (`id`, `key`, `secret_key`)
VALUES
	(1, '4LHBZ-R7RKG-FVAQO-I63RU-M3XKF-73BHZ', NULL);
	(2, 'MDNBZ-KIOCW-T6PRJ-OUNPH-VFQ6H-EKBEP', NULL);

-- ----------------------------
-- Table structure for cms_lbs_address_info
-- ----------------------------
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
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;

-- ----------------------------
-- Table structure for cms_lbs_config
-- ----------------------------
DROP TABLE IF EXISTS `cms_lbs_config`;
CREATE TABLE `cms_lbs_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cms_lbs_config
-- ----------------------------
INSERT INTO `cms_lbs_config` VALUES (1, 'time', '30', '地址缓存更新时间(天)');

SET FOREIGN_KEY_CHECKS = 1;
