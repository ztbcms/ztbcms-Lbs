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
