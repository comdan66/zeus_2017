<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Admin_controller extends Oa_controller {

  public function __construct () {
    parent::__construct ();

    if (!(User::current () && User::current ()->is_login ()))
      return redirect_message (array ('login'), array ());

    $this
         ->set_componemt_path ('component', 'admin')
         ->set_frame_path ('frame', 'admin')
         ->set_content_path ('content', 'admin')
         ->set_public_path ('public')

         ->set_title ("宙思管理後台")

         ->_add_meta ()
         ->_add_css ()
         ->_add_js ()
         ->add_param ('now_url', base_url ('admin'));
         ;
  }

  private function _add_meta () {
    return $this;
  }

  private function _add_css () {
    return $this->add_css ('http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700', false);
  }

  private function _add_js () {
    return $this->add_js (res_url ('res', 'js', 'admin.js'))
                ->add_js (res_url ('res', 'js', 'autosize_v3.0.8', 'autosize.min.js'))
                ->add_js (res_url ('res', 'js', 'ckeditor_d2015_05_18', 'ckeditor.js'), false)
                ->add_js (res_url ('res', 'js', 'ckeditor_d2015_05_18', 'config.js'), false)
                ->add_js (res_url ('res', 'js', 'ckeditor_d2015_05_18', 'adapters', 'jquery.js'), false)
                ;
  }
}