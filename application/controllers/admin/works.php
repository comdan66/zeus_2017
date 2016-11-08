<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Works extends Admin_controller {
  private $uri_1 = null;
  private $obj = null;

  public function __construct () {
    parent::__construct ();
    
    $this->uri_1 = 'admin/works';

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->obj = Work::find ('one', array ('conditions' => array ('id = ?', $id))))))
        return redirect_message (array ($this->uri_1), array (
            '_flash_danger' => '找不到該筆資料。'
          ));

    $this->add_param ('uri_1', $this->uri_1);
    $this->add_param ('now_url', base_url ($this->uri_1));
  }
  public function index ($offset = 0) {
    $columns = array ( 
        array ('key' => 'content', 'title' => '內容', 'sql' => 'content LIKE ?'), 
        array ('key' => 'title', 'title' => '標題', 'sql' => 'title LIKE ?'), 
        array ('key' => 'user_id', 'title' => '作者', 'sql' => 'user_id = ?', 'select' => array_map (function ($user) { return array ('value' => $user->id, 'text' => $user->name);}, User::all (array ('select' => 'id, name')))),
      );

    $configs = array_merge (explode ('/', $this->uri_1), array ('%s'));
    $conditions = conditions ($columns, $configs);

    $limit = 10;
    $total = Work::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul>', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $objs = Work::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'include' => array ('user'),
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

    $blocks = array_values (array_filter (array_map (function ($block) {
      if (!$block['title'] = htmlentities (trim ($block['title']))) return array ();
      
      $block['items'] = array_values (array_filter (array_map (function ($item) {
          $item['title'] = htmlentities (isset ($item['title']) ? trim ($item['title']) : '');
          $item['link'] = htmlentities (isset ($item['link']) ? trim ($item['link']) : '');
          return $item['title'] || $item['link'] ? $item : array ();
        }, isset ($block['items']) && $block['items'] ? $block['items'] : array ())));
      return $block;
    }, isset ($posts['blocks']) && $posts['blocks'] ? $posts['blocks'] : array ())));

    return $this->load_view (array (
        'posts' => $posts,
        'blocks' => $blocks
      ));
  }
  public function create () {
    if (!$this->has_post ())
      return redirect_message (array ($this->uri_1, 'add'), array (
          '_flash_danger' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    if (isset ($posts['content'])) $posts['content'] = OAInput::post ('content', false);
    $post_tag_ids = isset ($posts['tag_ids']) ? $posts['tag_ids'] : array ();

    $blocks = array_values (array_filter (array_map (function ($block) {
        if (!$block['title'] = trim ($block['title'])) return array ();
  
        $block['items'] = array_values (array_filter (array_map (function ($item) {
            $item['title'] = isset ($item['title']) ? trim ($item['title']) : '';
            $item['link'] = isset ($item['link']) ? trim ($item['link']) : '';
            return $item['title'] || $item['link'] ? $item : array ();
          }, isset ($block['items']) && $block['items'] ? $block['items'] : array ())));
        return $block;
      }, isset ($posts['blocks']) && $posts['blocks'] ? $posts['blocks'] : array ())));


    $cover = OAInput::file ('cover');
    $images = OAInput::file ('images[]');

    if (!$cover)
      return redirect_message (array ($this->uri_1, 'add'), array (
          '_flash_danger' => '請選擇照片(gif、jpg、png)檔案!',
          'posts' => $posts
        ));

    if (($msg = $this->_validation_must ($posts)) || ($msg = $this->_validation ($posts)))
      return redirect_message (array ($this->uri_1, 'add'), array (
          '_flash_danger' => $msg,
          'posts' => $posts
        ));

    $create = Work::transaction (function () use (&$obj, $posts, $cover) {
      return verifyCreateOrm ($obj = Work::create (array_intersect_key ($posts, Work::table ()->columns))) && $obj->cover->put ($cover);
    });

    if (!$create)
      return redirect_message (array ($this->uri_1, 'add'), array (
          '_flash_danger' => '新增失敗！',
          'posts' => $posts
        ));

    if ($post_tag_ids && ($tag_ids = column_array (WorkTag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $post_tag_ids))), 'id')))
      foreach ($tag_ids as $tag_id)
        WorkTagMapping::transaction (function () use ($tag_id, $obj) {
          return verifyCreateOrm (WorkTagMapping::create (array_intersect_key (array ('work_tag_id' => $tag_id, 'work_id' => $obj->id), WorkTagMapping::table ()->columns)));
        });

    if ($images)
      foreach ($images as $image)
        WorkImage::transaction (function () use ($image, $obj) {
          return verifyCreateOrm ($img = WorkImage::create (array_intersect_key (array ('work_id' => $obj->id), WorkImage::table ()->columns))) && $img->name->put ($image);
        });

    if ($blocks)
      foreach ($blocks as $block)
        if (!($b = null) && WorkBlock::transaction (function () use ($block, $obj, &$b) { return verifyCreateOrm ($b = WorkBlock::create (array_intersect_key (array_merge ($block, array ('work_id' => $obj->id)), WorkBlock::table ()->columns))); }))
          if (($items = $block['items']) && $b)
            foreach ($items as $item)
              WorkBlockItem::transaction (function () use ($item, $b) {
                return verifyCreateOrm (WorkBlockItem::create (array_intersect_key (array_merge ($item, array ('work_block_id' => $b->id)), WorkBlockItem::table ()->columns)));
              });

    return redirect_message (array ($this->uri_1), array (
        '_flash_info' => '新增成功！'
      ));
  }
  public function edit () {
    $posts = Session::getData ('posts', true);

    $blocks = array_values (array_filter (array_map (function ($block) {
      if (!$block['title'] = htmlentities (trim ($block['title']))) return array ();
      
      $block['items'] = array_values (array_filter (array_map (function ($item) {
          $item['title'] = htmlentities (isset ($item['title']) ? trim ($item['title']) : '');
          $item['link'] = htmlentities (isset ($item['link']) ? trim ($item['link']) : '');
          return $item['title'] || $item['link'] ? $item : array ();
        }, isset ($block['items']) && $block['items'] ? $block['items'] : array ())));
      return $block;
    }, isset ($posts['blocks']) && $posts['blocks'] ? $posts['blocks'] : array_map (function ($block) {
      return array (
          'title' => $block->title,
          'items' => array_map (function ($item) {
            return array (
                'title' => $item->title,
                'link' => $item->link,
              );
          }, $block->items)
        );
    }, $this->obj->blocks))));


    return $this->load_view (array (
        'posts' => $posts,
        'blocks' => $blocks,
        'obj' => $this->obj
      ));
  }
  public function update () {
    if (!$this->has_post ())
      return redirect_message (array ($this->uri_1, $this->obj->id, 'edit'), array (
          '_flash_danger' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $is_api = isset ($posts['_type']) && ($posts['_type'] == 'api') ? true : false;
    if (isset ($posts['content'])) $posts['content'] = OAInput::post ('content', false);
    $post_tag_ids = isset ($posts['tag_ids']) ? $posts['tag_ids'] : array ();
    $oldimg = isset ($posts['oldimg']) ? $posts['oldimg'] : array ();

    $blocks = array_values (array_filter (array_map (function ($block) {
        if (!$block['title'] = trim ($block['title'])) return array ();
  
        $block['items'] = array_values (array_filter (array_map (function ($item) {
            $item['title'] = isset ($item['title']) ? trim ($item['title']) : '';
            $item['link'] = isset ($item['link']) ? trim ($item['link']) : '';
            return $item['title'] || $item['link'] ? $item : array ();
          }, isset ($block['items']) && $block['items'] ? $block['items'] : array ())));
        return $block;
      }, isset ($posts['blocks']) && $posts['blocks'] ? $posts['blocks'] : array ())));


    $cover = OAInput::file ('cover');
    $images = OAInput::file ('images[]');

    if (!((string)$this->obj->cover || $cover))
      return $is_api ? $this->output_error_json ('Pic Format Error!') : redirect_message (array ($this->get_class (), $this->obj->id, 'edit'), array (
          '_flash_danger' => '請選擇圖片(gif、jpg、png)檔案!',
          'posts' => $posts
        ));
    
    if ($msg = $this->_validation ($posts))
      return $is_api ? $this->output_error_json ($msg) : redirect_message (array ($this->uri_1, $this->obj->id, 'edit'), array (
          '_flash_danger' => $msg,
          'posts' => $posts
        ));

    if ($columns = array_intersect_key ($posts, $this->obj->table ()->columns))
      foreach ($columns as $column => $value)
        $this->obj->$column = $value;
    
    $obj = $this->obj;
    $update = Work::transaction (function () use ($obj, $posts, $cover) {
      if (!$obj->save ())
        return false;

      if ($cover && !$obj->cover->put ($cover))
        return false;

      return true;
    });

    if (!$update)
      return $is_api ? $this->output_error_json ('更新失敗！') : redirect_message (array ($this->uri_1, $this->obj->id, 'edit'), array (
          '_flash_danger' => '更新失敗！',
          'posts' => $posts
        ));

    $ori_ids = column_array ($obj->mappings, 'work_tag_id');

    if (($del_ids = array_diff ($ori_ids, $post_tag_ids)) && ($mappings = WorkTagMapping::find ('all', array ('select' => 'id, work_tag_id', 'conditions' => array ('work_id = ? AND work_tag_id IN (?)', $obj->id, $del_ids)))))
      foreach ($mappings as $mapping)
        WorkTagMapping::transaction (function () use ($mapping) {
          return $mapping->destroy ();
        });

    if (($add_ids = array_diff ($post_tag_ids, $ori_ids)) && ($tags = WorkTag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $add_ids)))))
      foreach ($tags as $tag)
        WorkTagMapping::transaction (function () use ($tag, $obj) {
          return verifyCreateOrm (WorkTagMapping::create (Array_intersect_key (array ('work_tag_id' => $tag->id, 'work_id' => $obj->id), WorkTagMapping::table ()->columns)));
        });

    if (($del_ids = array_diff (column_array ($obj->images, 'id'), $oldimg)) && ($images = WorkImage::find ('all', array ('select' => 'id, name', 'conditions' => array ('id IN (?)', $del_ids)))))
      foreach ($images as $image)
        WorkImage::transaction (function () use ($image) { return $image->destroy (); });

    if ($images = OAInput::file ('images[]'))
      foreach ($images as $image)
        WorkImage::transaction (function () use ($image, $obj) {
          return verifyCreateOrm ($img = WorkImage::create (array_intersect_key (array ('work_id' => $obj->id), WorkImage::table ()->columns))) && $img->name->put ($image);
        });

    $clean_blocks = WorkBlock::transaction (function () use ($obj) {
      foreach ($obj->blocks as $block)
        if (!$block->destroy ())
          return false;
      return true;
    });

    if ($blocks)
      foreach ($blocks as $block)
        if (!($b = null) && WorkBlock::transaction (function () use ($block, $obj, &$b) { return verifyCreateOrm ($b = WorkBlock::create (array_intersect_key (array_merge ($block, array ('work_id' => $obj->id)), WorkBlock::table ()->columns))); }))
          if (($items = $block['items']) && $b)
            foreach ($items as $item)
              WorkBlockItem::transaction (function () use ($item, $b) {
                return verifyCreateOrm (WorkBlockItem::create (array_intersect_key (array_merge ($item, array ('work_block_id' => $b->id)), WorkBlockItem::table ()->columns)));
              });

    return $is_api ? $this->output_json ($obj->to_array ()) : redirect_message (array ($this->uri_1), array (
        '_flash_info' => '更新成功！'
      ));
  }
  public function destroy () {
    $obj = $this->obj;
    $delete = Work::transaction (function () use ($obj) {
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
  private function _validation (&$posts) {
    $keys = array ('user_id', 'title', 'content', 'is_enabled');

    $new_posts = array (); foreach ($posts as $key => $value) if (in_array ($key, $keys)) $new_posts[$key] = $value;
    $posts = $new_posts;

    if (isset ($posts['user_id']) && !(is_numeric ($posts['user_id'] = trim ($posts['user_id'])) && User::find_by_id ($posts['user_id']))) return '作者 ID 格式錯誤！';
    if (isset ($posts['title']) && !($posts['title'] = trim ($posts['title']))) return '標題格式錯誤！';
    if (isset ($posts['content']) && !($posts['content'] = trim ($posts['content']))) return '內容格式錯誤！';
    if (isset ($posts['is_enabled']) && !(is_numeric ($posts['is_enabled'] = trim ($posts['is_enabled'])) && in_array ($posts['is_enabled'], array_keys (Work::$enableNames)))) return '狀態格式錯誤！';
    return '';
  }
  private function _validation_must (&$posts) {
    if (!isset ($posts['user_id'])) return '沒有填寫 作者！';
    if (!isset ($posts['title'])) return '沒有填寫 標題！';
    if (!isset ($posts['content'])) return '沒有填寫 內容！';
    return '';
  }
}
