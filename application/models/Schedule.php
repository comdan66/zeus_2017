<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Schedule extends OaModel {

  static $table_name = 'schedules';

  static $has_one = array (
  );

  static $has_many = array (
  );

  static $belongs_to = array (
    array ('tag', 'class_name' => 'ScheduleTag'),
  );

  const NO_FINISHED = 0;
  const IS_FINISHED = 1;

  static $finishNames = array(
    self::NO_FINISHED => '未完成',
    self::IS_FINISHED => '已完成',
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
  public function to_array () {
    return array (
        'id' => $this->id,
        'title' => $this->title,
        'description' => $this->description,
        'finish' => $this->finish,
        'year' => $this->year,
        'month' => $this->month,
        'day' => $this->day,
        'sort' => $this->sort,
        'tag' => $this->tag ? $this->tag->to_array () : array ()
      );
  }
  public function destroy () {
    if (!isset ($this->id)) return false;
    return $this->delete ();
  }
}