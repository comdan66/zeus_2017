<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Work_tags extends Admin_controller {
  private $uri_1 = null;
  private $obj = null;

  public function __construct () {
    parent::__construct ();

    $this->uri_1 = 'admin/work-tags';

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy', 'sort')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->obj = WorkTag::find ('one', array ('conditions' => array ('id = ? AND work_tag_id = ?', $id, 0))))))
        return redirect_message (array ($this->uri_1), array (
            '_flash_danger' => '找不到該筆資料。'
          ));

    $this->add_param ('uri_1', $this->uri_1);
    $this->add_param ('now_url', base_url ($this->uri_1));
  }
  public function index ($offset = 0) {
    $columns = array ( 
        array ('key' => 'name', 'title' => '名稱', 'sql' => 'name LIKE ?'), 
      );

    $configs = array_merge (explode ('/', $this->uri_1), array ('%s'));
    $conditions = conditions ($columns, $configs);
    OaModel::addConditions ($conditions, 'work_tag_id = ?', 0);

    $limit = 25;
    $total = WorkTag::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul>', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $objs = WorkTag::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'sort DESC',
        'include' => array ('mappings', 'tags'),
        'conditions' => $conditions
      ));

    return $this->load_view (array (
        'objs' => $objs,
        'pagination' => $pagination,
        'columns' => $columns
      ));
  }
  public function add () {
    $posts = Session::getData ('posts', true);

    return $this->load_view (array (
        'posts' => $posts
      ));
  }
  public function create () {
    if (!$this->has_post ())
      return redirect_message (array ($this->uri_1, 'add'), array (
          '_flash_danger' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    
    if (($msg = $this->_validation_must ($posts)) || ($msg = $this->_validation ($posts)))
      return redirect_message (array ($this->uri_1, 'add'), array (
          '_flash_danger' => $msg,
          'posts' => $posts
        ));

    $posts['sort'] = WorkTag::count ();
    $create = WorkTag::transaction (function () use (&$obj, $posts) {
      return verifyCreateOrm ($obj = WorkTag::create (array_intersect_key ($posts, WorkTag::table ()->columns)));
    });

    if (!$create)
      return redirect_message (array ($this->uri_1, 'add'), array (
          '_flash_danger' => '新增失敗！',
          'posts' => $posts
        ));

    return redirect_message (array ($this->uri_1), array (
        '_flash_info' => '新增成功！'
      ));
  }
  public function edit () {
    $posts = Session::getData ('posts', true);

    return $this->load_view (array (
                    'posts' => $posts,
                    'obj' => $this->obj
                  ));
  }
  public function update () {
    if (!$this->has_post ())
      return redirect_message (array ($this->uri_1, $this->obj->id, 'edit'), array (
          '_flash_danger' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();

    if ($msg = $this->_validation ($posts))
      return redirect_message (array ($this->uri_1, $this->obj->id, 'edit'), array (
          '_flash_danger' => $msg,
          'posts' => $posts
        ));

    if ($columns = array_intersect_key ($posts, $this->obj->table ()->columns))
      foreach ($columns as $column => $value)
        $this->obj->$column = $value;
    
    $obj = $this->obj;
    $update = WorkTag::transaction (function () use ($obj, $posts) {
      return $obj->save ();
    });

    if (!$update)
      return redirect_message (array ($this->uri_1, $this->obj->id, 'edit'), array (
          '_flash_danger' => '更新失敗！',
          'posts' => $posts
        ));

    return redirect_message (array ($this->uri_1), array (
        '_flash_info' => '更新成功！'
      ));
  }

  public function destroy () {
    $obj = $this->obj;
    $delete = WorkTag::transaction (function () use ($obj) {
      return $obj->destroy ();
    });

    if (!$delete)
      return redirect_message (array ($this->uri_1), array (
          '_flash_danger' => '刪除失敗！',
        ));

    return redirect_message (array ($this->uri_1), array (
        '_flash_info' => '刪除成功！'
      ));
  }

  public function sort ($id, $sort) {
    if (!in_array ($sort, array ('up', 'down')))
      return redirect_message (array ($this->uri_1), array (
          '_flash_danger' => '排序失敗！'
        ));

    OaModel::addConditions ($conditions, 'work_tag_id = ?', 0);
    $total = WorkTag::count (array ('conditions' => $conditions));

    switch ($sort) {
      case 'up':
        $sort = $this->obj->sort;
        $this->obj->sort = $this->obj->sort + 1 >= $total ? 0 : $this->obj->sort + 1;
        break;

      case 'down':
        $sort = $this->obj->sort;
        $this->obj->sort = $this->obj->sort - 1 < 0 ? $total - 1 : $this->obj->sort - 1;
        break;
    }

    OaModel::addConditions ($conditions, 'sort = ?', $this->obj->sort);

    $obj = $this->obj;
    $update = WorkTag::transaction (function () use ($conditions, $obj, $sort) {
      if (($next = WorkTag::find ('one', array ('conditions' => $conditions))) && (($next->sort = $sort) || true))
        if (!$next->save ()) return false;
      if (!$obj->save ()) return false;

      return true;
    });

    if (!$update)
      return redirect_message (array ($this->uri_1), array (
          '_flash_danger' => '排序失敗！'
        ));

    return redirect_message (array ($this->uri_1), array (
      '_flash_info' => '排序成功！'
    ));
  }
  private function _validation (&$posts) {
    $keys = array ('name');

    $new_posts = array (); foreach ($posts as $key => $value) if (in_array ($key, $keys)) $new_posts[$key] = $value;
    $posts = $new_posts;

    if (isset ($posts['name']) && !($posts['name'] = trim ($posts['name']))) return '名稱格式錯誤！';
    return '';
  }
  private function _validation_must (&$posts) {
    if (!isset ($posts['name'])) return '沒有填寫 名稱！';
    return '';
  }
}
