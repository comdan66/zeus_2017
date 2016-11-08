<div class='panel'>
  <header>
    <h2>修改文章</h2>
    <a href='<?php echo base_url ($uri_1);?>' class='icon-x'></a>
  </header>


  <form class='form full' method='post' action='<?php echo base_url ($uri_1, $obj->id);?>' enctype='multipart/form-data'>
    <input type='hidden' name='_method' value='put' />
    
    <div class='row n2'>
      <label>公開</label>
      <div>
        <label class='switch'>
          <input type='checkbox' name='is_enabled'<?php echo (isset ($posts['is_enabled']) ? $posts['is_enabled'] : $obj->is_enabled) ? ' checked' : '';?> />
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
              <option value='<?php echo $user->id;?>'<?php echo (isset ($posts['user_id']) ? $posts['user_id'] : $obj->user_id) == $user->id ? ' selected': '';?>><?php echo $user->name;?></option>
      <?php }
          }?>
        </select>
      </div>
    </div>

<?php if ($tags = ArticleTag::all ()) { ?>
        <div class='row n2'>
          <label>分類</label>
          <div>
      <?php $tag_ids = isset ($posts['tag_ids']) ? $posts['tag_ids'] : column_array ($obj->mappings, 'article_tag_id');
            foreach ($tags as $tag) { ?>
              <label class='checkbox tag'><input type='checkbox' name='tag_ids[]' value='<?php echo $tag->id;?>'<?php echo $tag_ids && in_array ($tag->id, $tag_ids) ? ' checked' : '';?> /><span></span><?php echo $tag->name;?></label>
      <?php } ?>
          </div>
        </div>
<?php }?>


    <div class='row n2'>
      <label>標題</label>
      <div>
        <input type='text' name='title' value='<?php echo isset ($posts['title']) ? $posts['title'] : $obj->title;?>' placeholder='請輸入標題..' maxlength='200' pattern='.{1,200}' required title='輸入標題!' autofocus />
      </div>
    </div>

    <div class='row n2'>
      <label>封面</label>
      <div class='img_row'>
        <div class='drop_img no_cchoice'>
          <img src='<?php echo $obj->cover->url ();?>' />
          <input type='file' name='cover' />
        </div>
      </div>
    </div>


    <div class='row n2'>
      <label>內容</label>
      <div>
        <textarea name='content' class='pure autosize cke' placeholder='請輸入內容..'><?php echo htmlspecialchars (isset ($posts['content']) ? $posts['content'] : $obj->content);?></textarea>
      </div>
    </div>

    <div class='row n2 sources' data-i='0' data-sources='<?php echo json_encode ($posts['sources']);?>'>
      <label>參考</label>
      <div>
        <div class='add_source'>
          <button type='button' class='icon-r add'></button>
        </div>
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
