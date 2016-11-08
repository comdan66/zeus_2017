<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_invoices extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `invoices` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `invoice_tag_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'Tag Id',
        `invoice_contact_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '窗口 Id',
        `user_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '負責人',

        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '專案名稱',
        `quantity` smallint(6) unsigned DEFAULT 0 COMMENT '數量',
        `single_money` int(11) unsigned DEFAULT 0 COMMENT '單價',
        `all_money` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '總金額',
        `memo` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '備註',

        `is_finished` tinyint(4) unsigned NOT NULL DEFAULT 0 COMMENT '是否請款，1 是，0 否',
        `closing_at` date NOT NULL DEFAULT '" . date ('Y-m-d') . "' COMMENT '結案日期',

        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `invoices`;"
    );
  }
}