<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_article_sources extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `article_sources` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `article_id` int(11) unsigned NOT NULL COMMENT 'Article ID',
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '標題',
        `href` text  COMMENT '網址',
        `sort` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排列順序，上至下 ASC',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `article_sources`;"
    );
  }
}