<!DOCTYPE html>
<html lang="zh">
  <head>
    <?php echo isset ($meta_list) ? $meta_list : ''; ?>

    <title><?php echo isset ($title) ? $title : ''; ?></title>

<?php echo isset ($css_list) ? $css_list : ''; ?>

<?php echo isset ($js_list) ? $js_list : ''; ?>

  </head>
  <body lang="zh-tw">
    <?php echo isset ($hidden_list) ? $hidden_list : ''; ?>

    <div id='container' class=''>
      <div id='main_row'>
        <div id='left_side'>
          
          <header>
            <a href='<?php echo base_url ('admin', 'index');?>'>Ｚ</a>
            <span>Zeus Design Studio!</span>
          </header>

          <div id='login_user'>
            <figure class='_i'>
              <img src="<?php echo User::current ()->avatar ();?>">
            </figure>
            <div>
              <span>Hi, 您好!</span>
              <span><?php echo User::current ()->name;?></span>
            </div>
          </div>

          <ul id='main_menu'>
            <li>
              <label>
                <input type='checkbox' />
                <span class='icon-se'>個人管理</span>
                <ul>
                  <li><a href="<?php echo $url = base_url ('admin', 'my');?>" class='icon-u<?php echo $now_url == $url ? ' active' : '';?>'>基本資料</a></li>
                  <li><a href="<?php echo $url = base_url ('admin', 'calendar');?>" class='icon-ca<?php echo $now_url == $url ? ' active' : '';?>'>個人行程</a></li>
                  <li><a href="<?php echo $url = base_url ('admin', 'schedule-tags');?>" class='icon-ta<?php echo $now_url == $url ? ' active' : '';?>'>行程分類</a></li>
                </ul>
              </label>
            </li>

            <li>
              <label>
                <input type='checkbox' />
                <span class='icon-u'>人員管理</span>
                <ul>
                  <li><a href="<?php echo $url = base_url ('admin', 'users');?>" class='icon-ua<?php echo $now_url == $url ? ' active' : '';?>'>權限設定</a></li>
                </ul>
              </label>
            </li>

            <li>
              <label>
                <input type='checkbox' />
                <span class='icon-ims'>首頁上搞</span>
                <ul>
                  <li><a href="<?php echo $url = base_url ('admin', 'banners');?>" class='icon-im<?php echo $now_url == $url ? ' active' : '';?>'>旗幟設定</a></li>
                  <li><a href="<?php echo $url = base_url ('admin', 'promos');?>" class='icon-im<?php echo $now_url == $url ? ' active' : '';?>'>促銷設定</a></li>
                </ul>
              </label>
            </li>

            <li>
              <label>
                <input type='checkbox' />
                <span class='icon-f'>文章管理</span>
                <ul>
                  <li><a href="<?php echo $url = base_url ('admin', 'article-tags');?>" class='icon-ta<?php echo $now_url == $url ? ' active' : '';?>'>分類設定</a></li>
                  <li><a href="<?php echo $url = base_url ('admin', 'articles');?>" class='icon-fa<?php echo $now_url == $url ? ' active' : '';?>'>文章設定</a></li>
                </ul>
              </label>
            </li>

            <li>
              <label>
                <input type='checkbox' />
                <span class='icon-g'>作品管理</span>
                <ul>
                  <li><a href="<?php echo $url = base_url ('admin', 'work-tags');?>" class='icon-ta<?php echo $now_url == $url ? ' active' : '';?>'>分類設定</a></li>
                  <li><a href="<?php echo $url = base_url ('admin', 'works');?>" class='icon-g<?php echo $now_url == $url ? ' active' : '';?>'>作品設定</a></li>
                </ul>
              </label>
            </li>

            <li>
              <label>
                <input type='checkbox' />
                <span class='icon-ti'>帳務管理</span>
                <ul>
                  <li><a href="<?php echo $url = base_url ('admin', 'invoice-tags');?>" class='icon-ta<?php echo $now_url == $url ? ' active' : '';?>'>分類設定</a></li>
                  <li><a href="<?php echo $url = base_url ('admin', 'invoice-contacts');?>" class='icon-ua<?php echo $now_url == $url ? ' active' : '';?>'>窗口設定</a></li>
                  <li><a href="<?php echo $url = base_url ('admin', 'invoices');?>" class='icon-ti<?php echo $now_url == $url ? ' active' : '';?>'>帳務設定</a></li>
                </ul>
              </label>
            </li>
          </ul>

        </div>
        <div id='right_side'>
          <div id='top_side'>
            <button type='button' id='hamburger' class='icon-m'></button>
            <span>
              <a href='<?php echo base_url ('logout');?>' class='icon-o'></a>
            </span>
          </div>
          <div id='main'>
      <?php if ($_flash_danger = Session::getData ('_flash_danger', true)) { ?>
              <div id='_flash_danger'><?php echo $_flash_danger;?></div>
      <?php } else if ($_flash_info = Session::getData ('_flash_info', true)) { ?>
              <div id='_flash_info'><?php echo $_flash_info;?></div>
      <?php }?>
      <?php echo isset ($content) ? $content : ''; ?>
          </div>
          <div id='bottom_side'>
            後台版型設計 by 宙斯 <a href='http://www.ioa.tw/' target='_blank'>OA Wu</a>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>