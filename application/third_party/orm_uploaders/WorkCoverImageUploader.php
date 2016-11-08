<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class WorkCoverImageUploader extends OrmImageUploader {

  public function getVersions () {
    return array (
        '' => array (),
        '400x180c' => array ('adaptiveResizeQuadrant', 400, 180, 'c'),
        '1200x630c' => array ('adaptiveResizeQuadrant', 1200, 630, 'c'),
      );
  }
}