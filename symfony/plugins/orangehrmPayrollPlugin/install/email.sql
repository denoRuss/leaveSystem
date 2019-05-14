
CREATE TABLE IF NOT EXISTS `dk_email_pool` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_address` text DEFAULT NULL,
  `cc_address` text DEFAULT NULL,
  `from_address` text NOT NULL,
  `subject` varchar(255) NOT NULL,
  `category` varchar(60) NOT NULL,
  `content_type` enum('text/html','text/plain') DEFAULT NULL,
  `body` longtext NOT NULL,
  `status` enum('PROCESSING','SENT','PENDING','FAILED') CHARACTER SET latin1 DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- TODO this should be renamed based on domain name
UPDATE `hs_hr_config` SET `value` = 'http://52.14.55.103/symfony/web/index.php' WHERE `hs_hr_config`.`key` = 'domain.name';


