<header>
  <div class='title'>
    <h1>文章</h1>
    <p>文章管理</p>
  </div>

  <form class='select'>
    <button type='submit'>尋找</button>

<?php 
    if ($columns) { ?>
<?php foreach ($columns as $column) {
        if (isset ($column['select']) && $column['select']) { ?>
          <select name='<?php echo $column['key'];?>'>
            <option value=''>請選擇 <?php echo $column['title'];?>..</option>
      <?php foreach ($column['select'] as $option) { ?>
              <option value='<?php echo $option['value'];?>'<?php echo (is_numeric ($column['value']) && ($column['value'] == $option['value'])) || ($option['value'] === $column['value']) ? ' selected' : '';?>><?php echo $option['text'];?></option>
      <?php } ?>
          </select>
  <?php } else { ?>
          <label>
            <input type='text' name='<?php echo $column['key'];?>' value='<?php echo $column['value'];?>' placeholder='<?php echo $column['title'];?>搜尋..' />
            <i class='icon-s'></i>
          </label>
<?php   }
      }?>
<?php 
    } ?>

  </form>
</header>


<div class='panel'>
  <header>
    <h2>文章列表</h2>
    <a href='<?php echo base_url ($uri_1, 'add');?>' class='icon-r'></a>
  </header>

  <div class='content'>
    <table class='table'>
      <thead>
        <tr>
          <th width='50' class='center'>#</th>
          <th width='80' class='center'>上、下架</th>
          <th width='70' class='center'>封面</th>
          <th width='100'>作者</th>
          <th width='200'>標題</th>
          <th>內容</th>
          <th width='80' class='center'>修改/刪除</th>
        </tr>
      </thead>
      <tbody>
  <?php if ($objs) {
          foreach ($objs as $obj) { ?>
            <tr>
              <td class='center'><?php echo $obj->id;?></td>
              <td class='center'>
                <label class='switch' data-column='is_enabled' data-url='<?php echo base_url ($uri_1, $obj->id);?>'>
                  <input type='checkbox' name='is_enabled'<?php echo $obj->is_enabled == Article::ENABLE_YES ? ' checked' : '';?> />
                  <span></span>
                </label>
              </td>
              <td class='center'>
                <figure class='_i' href='<?php echo $obj->cover->url ();?>'>
                  <img src='<?php echo $obj->cover->url ('400x180c');?>' />
                  <figcaption data-description='<?php echo $obj->mini_content (0);?>'><?php echo $obj->title;?></figcaption>
                </figure>
              </td>
              <td><?php echo $obj->user->name;?></td>
              <td><?php echo $obj->mini_title (25);?></td>
              <td><?php echo $obj->mini_content (50);?></td>
              <td class='center'>
                <a class='icon-e' href="<?php echo base_url ($uri_1, $obj->id, 'edit');?>"></a>
                /
                <a class='icon-t' href="<?php echo base_url ($uri_1, $obj->id);?>" data-method='delete'></a>
              </td>
            </tr>
    <?php }
        } else { ?>
          <tr>
            <td colspan='7' class='no_data'>沒有任何資料。</td>
          </tr>
  <?php } ?>
      </tbody>
    </table>

    <div class='pagination'>
      <?php echo $pagination;?>
    </div>

  </div>
</div>

