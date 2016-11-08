<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Invoice extends OaModel {

  static $table_name = 'invoices';

  static $has_many = array (
  );

  static $belongs_to = array (
    array ('tag', 'class_name' => 'InvoiceTag'),
    array ('contact', 'class_name' => 'InvoiceContact'),
    array ('user', 'class_name' => 'User'),
  );

  const NO_FINISHED = 0;
  const IS_FINISHED = 1;

  static $finishNames = array(
    self::NO_FINISHED => '未請款',
    self::IS_FINISHED => '已請款',
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }

  public function destroy () {
    if (!isset ($this->id))
      return false;

      return $this->delete ();
  }
}