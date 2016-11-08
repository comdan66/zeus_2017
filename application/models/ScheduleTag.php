<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class ScheduleTag extends OaModel {

  static $table_name = 'schedule_tags';

  static $has_one = array (
  );

  static $has_many = array (
    array ('schedules', 'class_name' => 'Schedule'),
  );

  static $belongs_to = array (
  );

  const DEFAULT_COLOR = '#000000';


  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }

  public function to_array () {
    return array (
        'id' => $this->id,
        'name' => $this->name,
        'color' => $this->color (),
      );
  }
  public function color () {
    return $this->color ? '#' . $this->color : ScheduleTag::DEFAULT_COLOR;
  }
  public function destroy () {
    if (!isset ($this->id)) return false;

    if ($schedule_ids = column_array ($this->schedules, 'id'))
      Schedule::update_all (array (
          'set' => 'schedule_tag_id = NULL',
          'conditions' => array ('id IN (?)', $schedule_ids)
        ));

    return $this->delete ();
  }
}