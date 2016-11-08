<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Schedule_tags extends Api_controller {
  private $user = null;
  private $tag = null;

  public function __construct () {
    parent::__construct ();

    if (User::current ()) $this->user = User::current ();
    else $this->user = ($token = $this->input->get_request_header ('Token')) && ($user = User::find ('one', array ('conditions' => array ('token = ?', $token)))) ? $user : null;

    if (in_array ($this->uri->rsegments (2, 0), array ('finish', 'update', 'destroy')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->tag = ScheduleTag::find ('one', array ('conditions' => array ('id = ? AND user_id = ?', $id, $this->user->id))))))
        return $this->disable ($this->output_error_json ('Not found Data!'));
  }
  public function index () {
    $tags = ScheduleTag::find ('all', array (
      'order' => 'id DESC',
      'conditions' => array ('user_id = ?', $this->user->id)));

    $tags = array_map (function ($tag) {
      return $tag->to_array ();
    }, $tags);

    return $this->output_json ($tags);
  }
}
