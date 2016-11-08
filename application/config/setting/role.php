<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

// key 不准亂更改！
$role['role_names'] = array (
    'root' => '最高權限(涵蓋所有)',
    'member' => '登入後台(可登入後台資格)',
    // 'invoice_generator' => '帳務上稿(編輯)',
    // 'bloger' => '文章上稿',
    // 'templete_generator' => '產生樣板',
    // 'user_manager' => '會員管理',
    // 'contact_manager' => '留言管理',
    // 'official_editor' => '官網上稿',
  );

$role['roles'] = array_keys ($role['role_names']);
