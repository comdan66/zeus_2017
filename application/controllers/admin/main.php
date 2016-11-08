<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */
class Main extends Admin_controller {

  public function calendar () {
    $tags = ScheduleTag::find ('all', array ('select' => 'id, name, color', 'conditions' => array ('user_id = ?', User::current ()->id)));
    $tags = array_map (function ($tag) {
      return array (
          'id' => $tag->id,
          'name' => $tag->name,
          'color' => $tag->color ()
        );
    }, $tags);

    return $this->add_param ('now_url', base_url ('admin', 'calendar'))
                ->load_view (array (
        'tags' => $tags
      ));
  }
  public function index ($type = 'schedules', $offset = 0) {
    $pagination = '';
    $user_logs = $schedules = $columns = array ();
    $configs = array ('admin', 'my', $type, '%s');
    $conditions = conditions ($columns, $configs);
    $this->load->library ('pagination');

    if ($type == 'user_logs') {
      $limit = 5;
      OaModel::addConditions ($conditions, 'user_id = ?', User::current ()->id);
      $total = UserLog::count (array ('conditions' => $conditions));
      $offset = $offset < $total ? $offset : 0;

      $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul>', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
      $logs = UserLog::find ('all', array (
          'offset' => $offset,
          'limit' => $limit,
          'order' => 'id DESC',
          'conditions' => $conditions
        ));

      foreach ($logs as $log)
        if (!isset ($user_logs[$log->created_at->format ('Y-m-d')])) $user_logs[$log->created_at->format ('Y-m-d')] = array ($log);
        else array_push ($user_logs[$log->created_at->format ('Y-m-d')], $log);
    } else {
      $limit = 10;
      OaModel::addConditions ($conditions, 'user_id = ?', User::current ()->id);
      OaModel::addConditions ($conditions, 'year = ?', date ('Y'));
      OaModel::addConditions ($conditions, 'month = ?', date ('m'));
      OaModel::addConditions ($conditions, 'day = ?', date ('d'));

      $total = Schedule::count (array ('conditions' => $conditions));
      $offset = $offset < $total ? $offset : 0;

      $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul>', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
      $schedules = Schedule::find ('all', array (
          'offset' => $offset,
          'limit' => $limit,
          'include' => array ('tag'),
          'order' => 'sort ASC, id DESC',
          'conditions' => $conditions
        ));
    }

    $logs = UserLog::find ('all', array (
      'select' => 'count(id) AS cnt, created_at, DATE(`created_at`) AS date',
      'limit' => 12,
      'group' => 'date',
      'order' => 'date DESC',
      'conditions' => array ('user_id = ?', User::current ()->id)));
    $logs = array_combine (column_array ($logs, 'date'), $logs);

    $chart = array ();
    for ($i = 0; $i < 12; $i++) $chart[$date = date ('Y-m-d', strtotime (date ('Y-m-d') . $i ? '-' . $i . ' day' : ''))] = isset ($logs[$date]) ? $logs[$date]->cnt : 0;
    $chart = array_reverse ($chart);
    

    return $this->add_param ('now_url', base_url ('admin', 'my'))
                ->load_view (array (
        'user' => User::current (),
        'chart' => $chart,
        'type' => $type,
        'user_logs' => $user_logs,
        'schedules' => $schedules,
        'pagination' => $pagination
      ));
  }
}
