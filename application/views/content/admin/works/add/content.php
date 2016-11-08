<div class='panel'>
  <header>
    <h2>新增作品</h2>
    <a href='<?php echo base_url ($uri_1);?>' class='icon-x'></a>
  </header>

  <form class='form full' method='post' action='<?php echo base_url ($uri_1);?>' enctype='multipart/form-data'>
    
    <div class='row n2'>
      <label>公開</label>
      <div>
        <label class='switch'>
          <input type='checkbox' name='is_enabled'<?php echo isset ($posts['is_enabled']) && ($posts['is_enabled'] == Article::ENABLE_YES) ? ' checked' : '';?> />
          <span></span>
        </label>
      </div>
    </div>

    <div class='row n2'>
      <label>作者</label>
      <div>
        <select name='user_id'>
    <?php if ($users = User::all (array ('select' => 'id, name'))) {
            foreach ($users as $user) { ?>
              <option value='<?php echo $user->id;?>'<?php echo (isset ($posts['user_id']) ? $posts['user_id'] : User::current ()->id) == $user->id ? ' selected': '';?>><?php echo $user->name;?></option>
      <?php }
          }?>
        </select>
      </div>
    </div>

    <div class='row n2'>
      <label>標題</label>
      <div>
        <input type='text' name='title' value='<?php echo isset ($posts['title']) ? $posts['title'] : '';?>' placeholder='請輸入標題..' maxlength='200' pattern='.{1,200}' required title='輸入標題!' autofocus />
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
      <label>圖片</label>
      <div class='imgs_row'>

        <div class='drop_img no_cchoice'>
          <img src='' />
          <input type='file' name='images[]' />
          <a class='icon-t'></a>
        </div>

      </div>
    </div>


<?php if ($tags = WorkTag::find ('all', array ('include' => array ('tags'), 'conditions' => array ('work_tag_id = ?', 0)))) { ?>
        <div class='row n2'>
          <label style='margin-top: 7px;'>分類</label>
          <div class='tags'>
      <?php foreach ($tags as $i => $tag) { ?>
              <div class='tag'>
                <label class='main'><input type='checkbox' name='tag_ids[]' value='<?php echo $tag->id;?>'<?php echo isset ($posts['tag_ids']) && $posts['tag_ids'] && in_array ($tag->id, $posts['tag_ids']) ? ' checked' : '';?> /> <?php echo $tag->name;?></label>
          <?php if ($tag->tags) {
                  foreach ($tag->tags as $sub_tag) { ?>
                    <label class='sub'><input type='checkbox' class='l' name='tag_ids[]' value='<?php echo $sub_tag->id;?>'<?php echo isset ($posts['tag_ids']) && $posts['tag_ids'] && in_array ($sub_tag->id, $posts['tag_ids']) ? ' checked' : '';?> /> <?php echo $sub_tag->name;?></label>
            <?php }
                } ?>
              </div>
      <?php } ?>
          </div>
        </div>
<?php }?>


    <div class='row n2'>
      <label>內容</label>
      <div>
        <textarea name='content' class='pure autosize cke' placeholder='請輸入內容..'><?php echo isset ($posts['content']) ? $posts['content'] : '';?></textarea>
      </div>
    </div>

    <div class='row n2'>
      <label>說明</label>
      <div>
        <button type='button' data-i='0' class='icon-r add_block' data-blocks='<?php echo json_encode ($blocks);?>'>新增說明</button>
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
