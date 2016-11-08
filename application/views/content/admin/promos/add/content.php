<div class='panel'>
  <header>
    <h2>新增 Promo</h2>
    <a href='<?php echo base_url ($uri_1);?>' class='icon-x'></a>
  </header>

  <form class='form' method='post' action='<?php echo base_url ($uri_1);?>' enctype='multipart/form-data'>
    <div class='row n2'>
      <label>標題</label>
      <div>
        <input type='text' name='title' value='<?php echo isset ($posts['title']) ? $posts['title'] : '';?>' placeholder='請輸入標題..' maxlength='200' pattern='.{1,200}' required title='輸入標題!' autofocus />
      </div>
    </div>
    <div class='row n2'>
      <label>內容</label>
      <div>
        <textarea name='content' class='pure autosize' placeholder='請輸入內容..'><?php echo isset ($posts['content']) ? $posts['content'] : '';?></textarea>
      </div>
    </div>
    <div class='row n2'>
      <label>鏈結</label>
      <div>
        <input type='text' name='link' value='<?php echo isset ($posts['link']) ? $posts['link'] : '';?>' placeholder='請輸入鏈結..' maxlength='200' pattern='.{1,200}' required title='輸入鏈結!' />
      </div>
    </div>

    <div class='row n2'>
      <label>封面</label>
      <div class='img_row'>
        <div class='drop_img no_cchoice'>
          <img src='' />
          <input type='file' name='cover' />
        </div>
      </div>
    </div>
    <div class='row n2'>
      <label>開啟方式</label>
      <div class='radios'>
        <label>
          <input type='radio' name='target' value='<?php echo Promo::TARGET_SELF;?>' <?php echo (isset ($posts['target']) && $posts['target'] == Promo::TARGET_SELF) || !isset ($posts['target']) ? ' checked' : '';?> />
          <span></span>
          本頁
        </label>
        <label>
          <input type='radio' name='target' value='<?php echo Promo::TARGET_BLANK;?>' <?php echo isset ($posts['target']) && $posts['target'] == Promo::TARGET_BLANK ? ' checked' : '';?>/>
          <span></span>
          分頁
        </label>
      </div>
    </div>

    <div class='row n2'>
      <label>上、下架</label>
      <div>
        <label class='switch'>
          <input type='checkbox' name='is_enabled'<?php echo isset ($posts['is_enabled']) && ($posts['is_enabled'] == Promo::ENABLE_YES) ? ' checked' : '';?> />
          <span></span>
        </label>
      </div>
    </div>

    <div class='btns'>
      <div class='row n2'>
        <label></label>
        <div>
          <button type='reset'>取消</button>
          <button type='submit'>送出</button>
        </div>
      </div>
    </div>
  </form>
</div>
