<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_schedules extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `schedules` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` int(11) unsigned NOT NULL COMMENT 'User ID',
        `schedule_tag_id` int(11) unsigned DEFAULT NULL COMMENT 'Schedule Tag ID',

        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '標題',
        `description` text NOT NULL COMMENT '描述',
        `finish` tinyint(4) DEFAULT '0' COMMENT '完成 1，未完成 0',

        `year`  smallint(6) unsigned DEFAULT '0' COMMENT '年',
        `month` tinyint(4) unsigned  DEFAULT '0' COMMENT '月',
        `day`   tinyint(4) unsigned  DEFAULT '0' COMMENT '日',
        
        `sort`   tinyint(4) unsigned  DEFAULT '0' COMMENT '排序，ASC 為主',

        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `schedules`;"
    );
  }
}