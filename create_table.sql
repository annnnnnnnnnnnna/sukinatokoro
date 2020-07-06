CREATE TABLE `kansou` (
  `kansou_id` bigint(20) NOT NULL,
  `sakuhin_id` bigint(20) NOT NULL,
  `kansou_text` varchar(100) NOT NULL,
  `vote_num` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sakuhin` (
  `sakuhin_id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `sakuhin_title` varchar(100) NOT NULL,
  `sakusha_name` varchar(100) NOT NULL,
  `thx_page` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `url` (
  `uri_key` varchar(20) NOT NULL,
  `sakuhin_id` bigint(20) NOT NULL,
  `type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

