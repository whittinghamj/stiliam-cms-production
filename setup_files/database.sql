/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

# Dump of table addon_dns
# ------------------------------------------------------------

DROP TABLE IF EXISTS `addon_dns`;

CREATE TABLE `addon_dns` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `server_id` int(11) DEFAULT NULL,
  `hostname` varchar(50) DEFAULT NULL,
  `domain` varchar(50) DEFAULT NULL,
  `cf_domain_id` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table bouquets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bouquets`;

CREATE TABLE `bouquets` (
  `id` bigint(30) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `streams` text,
  `old_xc_id` int(11) DEFAULT NULL,
  `type` varchar(20) DEFAULT 'live',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;



# Dump of table capture_devices
# ------------------------------------------------------------

DROP TABLE IF EXISTS `capture_devices`;

CREATE TABLE `capture_devices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `updated` bigint(20) NOT NULL DEFAULT 0,
  `server_id` int(11) NOT NULL DEFAULT 0,
  `enable` varchar(3) NOT NULL DEFAULT 'no',
  `status` varchar(20) DEFAULT '',
  `name` varchar(100) DEFAULT '',
  `video_device` varchar(50) DEFAULT '',
  `video_codec` varchar(10) NOT NULL DEFAULT 'libx264',
  `framerate_in` int(4) NOT NULL DEFAULT 30,
  `framerate_out` int(4) DEFAULT 30,
  `screen_resolution` varchar(10) DEFAULT '1280x720',
  `audio_device` varchar(50) DEFAULT '',
  `audio_codec` varchar(10) DEFAULT 'aac',
  `audio_bitrate` int(4) DEFAULT 128,
  `audio_sample_rate` int(6) DEFAULT 44100,
  `bitrate` int(10) DEFAULT 3500,
  `output_type` varchar(10) DEFAULT 'http',
  `rtmp_server` varchar(500) DEFAULT '',
  `http_server` varchar(500) DEFAULT '',
  `watermark_type` varchar(10) DEFAULT 'disable',
  `watermark_image` varchar(500) DEFAULT '',
  `running_command` text DEFAULT NULL,
  `running_pid` int(10) DEFAULT NULL,
  `stream_uptime` varchar(20) DEFAULT '0',
  `used_by` varchar(25) DEFAULT '',
  `type` varchar(20) DEFAULT '',
  `dvb_signal` varchar(10) DEFAULT 'no_signal',
  `dvb_snr` varchar(10) DEFAULT 'no_signal',
  `dvb_type` varchar(10) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table capture_devices_audio
# ------------------------------------------------------------

DROP TABLE IF EXISTS `capture_devices_audio`;

CREATE TABLE `capture_devices_audio` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `server_id` int(11) DEFAULT NULL,
  `audio_device` varchar(100) NOT NULL DEFAULT '',
  `status` varchar(10) NOT NULL DEFAULT 'available',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table cdn_streams
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cdn_streams`;

CREATE TABLE `cdn_streams` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `enable` varchar(3) NOT NULL DEFAULT 'yes',
  `category` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `source_url` varchar(1000) NOT NULL DEFAULT '',
  `country` varchar(3) NOT NULL DEFAULT '',
  `publish_name` varchar(30) DEFAULT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'tv',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table cdn_streams_servers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cdn_streams_servers`;

CREATE TABLE `cdn_streams_servers` (
  `id` varchar(100) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT 0,
  `server_id` int(11) NOT NULL DEFAULT 0,
  `stream_id` int(11) NOT NULL DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'loading',
  `running_pid` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table channel_connection_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `channel_connection_logs`;

CREATE TABLE `channel_connection_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` bigint(11) DEFAULT NULL,
  `server_id` varchar(100) DEFAULT NULL,
  `channel_id` int(11) DEFAULT NULL,
  `stream_name` varchar(50) DEFAULT NULL,
  `client_ip` varchar(15) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table channels
# ------------------------------------------------------------

DROP TABLE IF EXISTS `channels`;

CREATE TABLE `channels` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `server_id` int(11) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `cover_photo` varchar(1000) NOT NULL DEFAULT 'img/no_image_available.jpg',
  `status` varchar(20) NOT NULL DEFAULT 'offline',
  `enable` varchar(3) NOT NULL DEFAULT 'no',
  `uptime` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table channels_files
# ------------------------------------------------------------

DROP TABLE IF EXISTS `channels_files`;

CREATE TABLE `channels_files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `server_id` int(11) DEFAULT NULL,
  `channel_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `file_location` text DEFAULT NULL,
  `order` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table customers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `reseller_id` int(11) NOT NULL DEFAULT 0,
  `package_id` int(11) NOT NULL DEFAULT 1,
  `updated` bigint(20) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'enabled',
  `first_name` varchar(50) DEFAULT '',
  `last_name` varchar(50) DEFAULT '',
  `username` varchar(25) NOT NULL DEFAULT '',
  `password` varchar(25) NOT NULL DEFAULT '',
  `email` varchar(100) DEFAULT '',
  `max_connections` int(4) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `allowed_ips` varchar(500) DEFAULT '',
  `expire_date` varchar(20) DEFAULT NULL,
  `live_content` varchar(10) NOT NULL DEFAULT 'on',
  `channel_content` varchar(10) NOT NULL DEFAULT 'on',
  `vod_content` varchar(10) NOT NULL DEFAULT 'on',
  `bouquet` text DEFAULT NULL,
  `reseller_notes` text DEFAULT NULL,
  `old_xc_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`user_id`,`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table downloads
# ------------------------------------------------------------

DROP TABLE IF EXISTS `downloads`;

CREATE TABLE `downloads` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `enable` varchar(10) NOT NULL DEFAULT 'yes',
  `name` varchar(1000) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `filename` varchar(100) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table geoip
# ------------------------------------------------------------

DROP TABLE IF EXISTS `geoip`;

CREATE TABLE `geoip` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `lat` varchar(20) NOT NULL DEFAULT '',
  `lng` varchar(20) NOT NULL DEFAULT '',
  `country_code` varchar(100) NOT NULL DEFAULT '',
  `country_name` varchar(100) NOT NULL DEFAULT '',
  `region_name` varchar(100) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `zip_code` varchar(20) NOT NULL DEFAULT '',
  `time_zone` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_address` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table headend_server_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `headend_server_logs`;

CREATE TABLE `headend_server_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `added` bigint(20) DEFAULT NULL,
  `type` varchar(100) NOT NULL DEFAULT 'info',
  `server_id` varchar(10) NOT NULL DEFAULT '0',
  `message` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table headend_servers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `headend_servers`;

CREATE TABLE `headend_servers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `updated` bigint(20) DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT 0,
  `uuid` varchar(512) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'headend',
  `status` varchar(15) NOT NULL DEFAULT 'installing',
  `name` varchar(100) DEFAULT '',
  `ip_address` varchar(15) NOT NULL DEFAULT '0.0.0.0',
  `wan_ip_address` varchar(15) NOT NULL DEFAULT '0.0.0.0',
  `public_hostname` varchar(100) DEFAULT '',
  `http_stream_port` int(5) NOT NULL DEFAULT 1202,
  `ssh_port` int(5) NOT NULL DEFAULT 22,
  `uptime` bigint(20) NOT NULL DEFAULT 0,
  `ssh_password` varchar(64) DEFAULT '',
  `cpu_usage` varchar(10) NOT NULL DEFAULT '0',
  `ram_usage` varchar(10) NOT NULL DEFAULT '0',
  `disk_usage` varchar(10) NOT NULL DEFAULT '0',
  `bandwidth_down` varchar(10) NOT NULL DEFAULT '0',
  `bandwidth_up` varchar(10) NOT NULL DEFAULT '0',
  `astra_config_file` longblob DEFAULT NULL,
  `astra_port` varchar(5) NOT NULL DEFAULT '8000',
  `nginx_stats` longblob DEFAULT NULL,
  `mumudvb_config_file` varchar(250) DEFAULT '',
  `tvheadend_config_file` varchar(250) DEFAULT '',
  `tvheadend_port` int(6) NOT NULL DEFAULT 9981,
  `astra_license` varchar(500) DEFAULT '',
  `lat` varchar(20) DEFAULT '',
  `lng` varchar(20) DEFAULT '',
  `country_code` varchar(3) DEFAULT '',
  `country_name` varchar(30) DEFAULT '',
  `region_name` varchar(30) DEFAULT '',
  `city` varchar(30) DEFAULT '',
  `zip_code` varchar(30) DEFAULT '',
  `time_zone` varchar(30) DEFAULT '',
  `os_version` varchar(50) DEFAULT '',
  `gpu_stats` text DEFAULT NULL,
  `cpu_model` varchar(100) DEFAULT '',
  `cpu_cores` varchar(10) DEFAULT '',
  `cpu_speed` varchar(20) DEFAULT '',
  `ram_total` varchar(20) NOT NULL DEFAULT '0',
  `kernel` varchar(100) DEFAULT '',
  `active_connections` int(5) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `headend_servers` WRITE;
/*!40000 ALTER TABLE `headend_servers` DISABLE KEYS */;

INSERT INTO `headend_servers` (`user_id`, `uuid`, `name`)
VALUES
  (1,'e762b280f732f173efb1c0db32a7c756','Main Server');

/*!40000 ALTER TABLE `headend_servers` ENABLE KEYS */;
UNLOCK TABLES;

# Dump of table headend_stats_history
# ------------------------------------------------------------

DROP TABLE IF EXISTS `headend_stats_history`;

CREATE TABLE `headend_stats_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `added` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `server_id` int(11) NOT NULL,
  `bandwidth_up` int(11) NOT NULL,
  `bandwidth_down` int(11) NOT NULL,
  `cpu_usage` varchar(11) NOT NULL DEFAULT '',
  `ram_usage` varchar(11) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL DEFAULT 0,
  `job` varchar(500) NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `logs`;

CREATE TABLE `logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `added` bigint(20) NOT NULL DEFAULT 0,
  `message` varchar(1000) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table mag_devices
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mag_devices`;

CREATE TABLE `mag_devices` (
  `mag_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `bright` int(10) NOT NULL DEFAULT 200,
  `contrast` int(10) NOT NULL DEFAULT 127,
  `saturation` int(10) NOT NULL DEFAULT 127,
  `aspect` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `video_out` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'rca',
  `volume` int(5) NOT NULL DEFAULT 50,
  `playback_buffer_bytes` int(50) NOT NULL DEFAULT 0,
  `playback_buffer_size` int(50) NOT NULL DEFAULT 0,
  `audio_out` int(5) NOT NULL DEFAULT 1,
  `mac` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ls` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ver` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locale` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en_GB.utf8',
  `city_id` int(11) DEFAULT 0,
  `hd` int(10) NOT NULL DEFAULT 1,
  `main_notify` int(5) NOT NULL DEFAULT 1,
  `fav_itv_on` int(5) NOT NULL DEFAULT 0,
  `now_playing_start` int(50) DEFAULT NULL,
  `now_playing_type` int(11) NOT NULL DEFAULT 0,
  `now_playing_content` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_last_play_tv` int(50) DEFAULT NULL,
  `time_last_play_video` int(50) DEFAULT NULL,
  `hd_content` int(11) NOT NULL DEFAULT 1,
  `image_version` varchar(350) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_change_status` int(11) DEFAULT NULL,
  `last_start` int(11) DEFAULT NULL,
  `last_active` int(11) DEFAULT NULL,
  `keep_alive` int(11) DEFAULT NULL,
  `playback_limit` int(11) NOT NULL DEFAULT 3,
  `screensaver_delay` int(11) NOT NULL DEFAULT 10,
  `stb_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `sn` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_watchdog` int(50) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `country` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plasma_saving` int(11) NOT NULL DEFAULT 0,
  `ts_enabled` int(11) DEFAULT 0,
  `ts_enable_icon` int(11) NOT NULL DEFAULT 1,
  `ts_path` varchar(35) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ts_max_length` int(11) NOT NULL DEFAULT 3600,
  `ts_buffer_use` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'cyclic',
  `ts_action_on_exit` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no_save',
  `ts_delay` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'on_pause',
  `video_clock` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Off',
  `rtsp_type` int(11) NOT NULL DEFAULT 4,
  `rtsp_flags` int(11) NOT NULL DEFAULT 0,
  `stb_lang` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `display_menu_after_loading` int(11) NOT NULL DEFAULT 1,
  `record_max_length` int(11) NOT NULL DEFAULT 180,
  `plasma_saving_timeout` int(11) NOT NULL DEFAULT 600,
  `now_playing_link_id` int(11) DEFAULT NULL,
  `now_playing_streamer_id` int(11) DEFAULT NULL,
  `device_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `device_id2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hw_version` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_password` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0000',
  `spdif_mode` int(11) NOT NULL DEFAULT 1,
  `show_after_loading` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'main_menu',
  `play_in_preview_by_ok` int(11) NOT NULL DEFAULT 1,
  `hdmi_event_reaction` int(11) NOT NULL DEFAULT 1,
  `mag_player` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `play_in_preview_only_by_ok` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'true',
  `fav_channels` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `tv_archive_continued` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `tv_channel_default_aspect` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'fit',
  `last_itv_id` int(11) NOT NULL DEFAULT 0,
  `units` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'metric',
  `token` varchar(32) COLLATE utf8_unicode_ci DEFAULT '',
  `lock_device` tinyint(4) NOT NULL DEFAULT 0,
  `watchdog_timeout` int(11) DEFAULT NULL,
  `old_xc_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`mag_id`),
  KEY `user_id` (`customer_id`),
  KEY `mac` (`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table packages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `packages`;

CREATE TABLE `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `is_trial` tinyint(4) NOT NULL,
  `credits` float NOT NULL,
  `trial_duration` int(11) NOT NULL,
  `official_duration` int(11) NOT NULL,
  `bouquets` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `old_xc_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `is_trial` (`is_trial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT IGNORE INTO `packages` (`id`, `user_id`, `name`) VALUES (1, 1, 'Default Package'); 

# Dump of table remote_playlists
# ------------------------------------------------------------

DROP TABLE IF EXISTS `remote_playlists`;

CREATE TABLE `remote_playlists` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(11) DEFAULT NULL,
  `name` varchar(250) NOT NULL DEFAULT 'Remote Playlist',
  `url` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table resellers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `resellers`;

CREATE TABLE `resellers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `updated` bigint(20) DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `group_id` int(11) DEFAULT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'enabled',
  `email` varchar(200) DEFAULT '',
  `username` varchar(50) DEFAULT '',
  `password` varchar(50) DEFAULT '',
  `first_name` varchar(50) DEFAULT '',
  `last_name` varchar(50) DEFAULT '',
  `avatar` varchar(250) NOT NULL DEFAULT 'img/avatar.png',
  `credits` varchar(11) NOT NULL DEFAULT '0',
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table resellers_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `resellers_groups`;

CREATE TABLE `resellers_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table roku_devices
# ------------------------------------------------------------

DROP TABLE IF EXISTS `roku_devices`;

CREATE TABLE `roku_devices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `updated` bigint(20) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending_adoption',
  `user_id` int(11) DEFAULT NULL,
  `server_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `ip_address` varchar(20) DEFAULT NULL,
  `device_brand` varchar(5) NOT NULL DEFAULT 'roku',
  `serial_number` varchar(50) DEFAULT NULL,
  `device_uuid` varchar(50) DEFAULT NULL,
  `model_name` varchar(50) DEFAULT NULL,
  `model_number` varchar(50) DEFAULT NULL,
  `wifi_mac` varchar(50) DEFAULT NULL,
  `ethernet_mac` varchar(50) DEFAULT NULL,
  `network_type` varchar(20) DEFAULT NULL,
  `software_version` varchar(20) DEFAULT NULL,
  `uptime` int(11) DEFAULT NULL,
  `app` varchar(20) DEFAULT NULL,
  `channel` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table stream_categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stream_categories`;

CREATE TABLE `stream_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `stream_categories` (`id`, `user_id`, `name`) VALUES (1, 1, 'Default Category');

DROP TABLE IF EXISTS `vod_categories`;

CREATE TABLE `vod_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

INSERT INTO `vod_categories` (`id`, `user_id`, `name`) VALUES (1, 1, 'General');


# Dump of table stream_progress
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stream_progress`;

CREATE TABLE `stream_progress` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` bigint(20) DEFAULT NULL,
  `stream_id` varchar(50) NOT NULL DEFAULT '',
  `data` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table streams
# ------------------------------------------------------------

DROP TABLE IF EXISTS `streams`;

CREATE TABLE `streams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `updated` bigint(20) NOT NULL DEFAULT 0,
  `user_id` int(5) NOT NULL DEFAULT 0,
  `server_id` int(11) NOT NULL DEFAULT 0,
  `enable` varchar(3) NOT NULL DEFAULT 'no',
  `status` varchar(20) NOT NULL DEFAULT 'offline',
  `stream_type` varchar(15) NOT NULL DEFAULT 'input',
  `job_status` varchar(25) NOT NULL DEFAULT 'none',
  `category_id` int(11) DEFAULT 0,
  `name` varchar(100) NOT NULL DEFAULT '',
  `source` varchar(500) NOT NULL DEFAULT '',
  `source_type` varchar(25) DEFAULT 'restream',
  `source_stream_id` int(20) NOT NULL DEFAULT 0,
  `source_server_id` int(11) NOT NULL DEFAULT 0,
  `watermark_type` varchar(10) NOT NULL DEFAULT 'disable',
  `watermark_image` varchar(500) DEFAULT '',
  `running_command` text DEFAULT NULL,
  `running_pid` varchar(10) DEFAULT '',
  `stream_uptime` varchar(20) NOT NULL DEFAULT '0',
  `cpu_gpu` varchar(4) NOT NULL DEFAULT 'copy',
  `gpu` int(2) NOT NULL DEFAULT 0,
  `fingerprint` varchar(20) NOT NULL DEFAULT 'disable',
  `fingerprint_type` varchar(25) NOT NULL DEFAULT 'static_text',
  `fingerprint_text` varchar(500) NOT NULL DEFAULT 'Sample Text',
  `fingerprint_location` varchar(25) NOT NULL DEFAULT 'bottom_right',
  `fingerprint_fontsize` varchar(3) NOT NULL DEFAULT '24',
  `fingerprint_color` varchar(20) NOT NULL DEFAULT 'white',
  `gpu_data` text DEFAULT NULL,
  `pending_changes` varchar(3) NOT NULL DEFAULT 'no',
  `transcode` varchar(3) NOT NULL DEFAULT 'no',
  `user_agent` varchar(50) DEFAULT '',
  `fps` varchar(10) DEFAULT '',
  `speed` varchar(10) DEFAULT '',
  `profile_id` varchar(3) DEFAULT '',
  `surfaces` int(3) NOT NULL DEFAULT 10,
  `video_codec` varchar(20) DEFAULT '',
  `screen_resolution` varchar(15) DEFAULT '',
  `framerate` int(3) DEFAULT 0,
  `audio_codec` varchar(20) NOT NULL DEFAULT 'copy',
  `ffmpeg_re` varchar(3) NOT NULL DEFAULT 'no',
  `audio_bitrate` int(3) NOT NULL DEFAULT 128,
  `audio_sample_rate` int(5) NOT NULL DEFAULT 44100,
  `bitrate` int(10) NOT NULL DEFAULT 5000,
  `ac` int(2) DEFAULT 2,
  `preset` varchar(10) DEFAULT '3',
  `profile` varchar(10) DEFAULT 'baseline',
  `aspect` varchar(10) DEFAULT '16:9',
  `dehash` varchar(10) DEFAULT 'disable',
  `dehash_file_path` varchar(500) DEFAULT '',
  `dehash_padding` varchar(20) NOT NULL DEFAULT '0-0-0-0',
  `dehash_scale` varchar(20) NOT NULL DEFAULT '0.75-1.25',
  `dehash_buffer_queue_size` int(5) NOT NULL DEFAULT 768,
  `dehash_dedect_interval` int(5) NOT NULL DEFAULT 1,
  `dehash_score_min` varchar(5) NOT NULL DEFAULT '0.4',
  `logo` text DEFAULT NULL,
  `probe_width` varchar(11) DEFAULT '',
  `probe_height` varchar(11) DEFAULT '',
  `probe_bitrate` varchar(11) DEFAULT '',
  `probe_aspect_ratio` varchar(11) DEFAULT '',
  `probe_video_codec` varchar(11) DEFAULT '',
  `probe_audio_codec` varchar(11) DEFAULT '',
  `transcoding_profile_id` int(11) DEFAULT 0,
  `old_xc_id` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT 0,
  `ondemand` varchar(11) DEFAULT 'no',
  `direct` varchar(3) DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table streams_acl_rules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `streams_acl_rules`;

CREATE TABLE `streams_acl_rules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `server_id` int(11) NOT NULL,
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `comment` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table streams_connection_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `streams_connection_logs`;

CREATE TABLE `streams_connection_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` bigint(11) DEFAULT NULL,
  `server_id` varchar(100) DEFAULT NULL,
  `stream_id` int(11) DEFAULT NULL,
  `stream_name` varchar(50) DEFAULT NULL,
  `client_ip` varchar(15) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table transcoding_profiles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `transcoding_profiles`;

CREATE TABLE `transcoding_profiles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `data` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table tv_series
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tv_series`;

CREATE TABLE `tv_series` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `server_id` int(11) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `cover_photo` varchar(250) NOT NULL DEFAULT 'img/no_image_available.jpg',
  `status` varchar(20) NOT NULL DEFAULT 'offline',
  `enable` varchar(3) NOT NULL DEFAULT 'yes',
  `watch_folder` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table tv_series_files
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tv_series_files`;

CREATE TABLE `tv_series_files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `server_id` varchar(11) DEFAULT NULL,
  `tv_series_id` int(11) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `file_location` varchar(500) DEFAULT NULL,
  `order` int(3) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_sessions`;

CREATE TABLE `user_sessions` (
  `id` varchar(32) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `fingerprint` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `data` text COLLATE latin1_general_ci DEFAULT NULL,
  `access` int(32) NOT NULL DEFAULT 0,
  `date` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL DEFAULT 'customer',
  `status` varchar(20) NOT NULL DEFAULT 'enabled',
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `first_name` varchar(50) NOT NULL DEFAULT '',
  `last_name` varchar(50) NOT NULL DEFAULT '',
  `avatar` varchar(250) NOT NULL DEFAULT 'img/avatar.png',
  `email` varchar(200) NOT NULL DEFAULT '',
  `max_servers` int(3) NOT NULL DEFAULT 0,
  `premium_streams` varchar(3) NOT NULL DEFAULT 'no',
  `addon_dns` varchar(3) NOT NULL DEFAULT 'no',
  `addon_playlist_manager` varchar(3) NOT NULL DEFAULT 'no',
  `addon_roku_manager` varchar(3) NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `type`, `status`, `username`, `password`, `first_name`, `last_name`, `avatar`, `email`, `max_servers`, `premium_streams`, `addon_dns`, `addon_playlist_manager`, `addon_roku_manager`)
VALUES
  (1,'admin','enabled','admin','admin','Admin','User','img/avatar.png','you@example.com',20,'yes','yes','yes','yes');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

# Dump of table vod
# ------------------------------------------------------------

DROP TABLE IF EXISTS `vod`;

CREATE TABLE `vod` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `server_id` varchar(11) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `file_location` varchar(500) DEFAULT NULL,
  `cover_photo` varchar(500) DEFAULT NULL,
  `year` varchar(10) DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `runtime` varchar(20) DEFAULT NULL,
  `language` varchar(200) DEFAULT NULL,
  `category_id` varchar(20) NOT NULL DEFAULT '1',
  `watch_folder` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table xc_import_jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `xc_import_jobs`;

CREATE TABLE `xc_import_jobs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `user_id` int(11) NOT NULL,
  `filename` varchar(200) NOT NULL DEFAULT '',
  `error_message` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `global_settings`;

CREATE TABLE `global_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `config_name` varchar(100) DEFAULT NULL,
  `config_value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

INSERT INTO `global_settings` (`id`, `config_name`, `config_value`)
VALUES
  (1, 'cms_ip', ''),
  (2, 'cms_port', ''),
  (3, 'cms_domain_name', ''),
  (4, 'cms_name', 'SlipStream CMS'),
  (5, 'cms_terms_accepted', 'no'),
  (6, 'WHMCS', 'namppW9kZJihnpqjqahjmZqhqZaYpKGkY5ikomSipJmqoZqoZKiap6uap6hkoZ6YmqOonqOcZKuap56brmOlnaU=');

DROP TABLE IF EXISTS `vod_watch`;

CREATE TABLE `vod_watch` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `server_id` int(11) DEFAULT NULL,
  `folder` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `customers_ips`;

CREATE TABLE `customers_ips` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `ip_address` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `bouquets_content`;

CREATE TABLE `bouquets_content` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bouquet_id` int(11) DEFAULT '0',
  `content_id` int(11) DEFAULT '0',
  `order` int(11) DEFAULT '999999',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bouquet_content` (`bouquet_id`,`content_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `epg_setting`;

CREATE TABLE `epg_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL DEFAULT '',
  `etag` varchar(255) NOT NULL DEFAULT '',
  `updated` datetime DEFAULT NULL,
  `id_prefix` varchar(64) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `lang_code` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uri` (`uri`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `epg_xml_ids`;

CREATE TABLE `epg_xml_ids` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `epg_source_id` int(11) DEFAULT '0',
  `xml_id` varchar(30) DEFAULT '',
  `xml_name` varchar(50) DEFAULT '',
  `xml_language` varchar(20) DEFAULT 'en',
  PRIMARY KEY (`id`),
  UNIQUE KEY `xml_id` (`xml_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `vod_connection_logs`;

CREATE TABLE `vod_connection_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` bigint(11) DEFAULT NULL,
  `server_id` varchar(100) DEFAULT NULL,
  `vod_id` int(11) DEFAULT NULL,
  `stream_name` varchar(50) DEFAULT NULL,
  `client_ip` varchar(15) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `series_connection_logs`;

CREATE TABLE `series_connection_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` bigint(11) DEFAULT NULL,
  `server_id` varchar(100) DEFAULT NULL,
  `series_id` int(11) DEFAULT NULL,
  `stream_name` varchar(50) DEFAULT NULL,
  `client_ip` varchar(15) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `addon_licenses`;

CREATE TABLE `addon_licenses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product` varchar(250) DEFAULT NULL,
  `license` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
