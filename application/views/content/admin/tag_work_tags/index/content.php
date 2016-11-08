<header>
  <div class='title'>
    <h1>作品分類</h1>
    <p>作品類型子分類</p>
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
              <option value='<?php echo $option['value'];?>'<?php echo $option['value'] === $column['value'] ? ' selected' : '';?>><?php echo $option['text'];?></option>
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
    <h2><a href='<?php echo base_url ('admin', 'work-tags');?>'><?php echo $parent->name;?></a> 的子分類列表</h2>
    <a href='<?php echo base_url ($uri_1, $parent->id, $uri_2, 'add');?>' class='icon-r'></a>
  </header>

  <div class='content'>


    <table class='table'>
      <thead>
        <tr>
          <th width='80'>#</th>
          <th >名稱</th>
          <th width='100'>作品數</th>
          <th width='50' class='center'>排序</th>
          <th width='85' class='right'>修改/刪除</th>
        </tr>
      </thead>
      <tbody>
  <?php if ($objs) {
          foreach ($objs as $obj) { ?>
            <tr>
              <td><?php echo $obj->id;?></td>
              <td><?php echo $obj->name;?></td>
              <td><?php echo count ($obj->mappings);?></td>
              <td class='center'>
                <a class='icon-tu' href='<?php echo base_url ($uri_1, $parent->id, $uri_2, $obj->id, 'sort', 'up');?>' data-method='post'></a>
                <a class='icon-td' href='<?php echo base_url ($uri_1, $parent->id, $uri_2, $obj->id, 'sort', 'down');?>' data-method='post'></a>
              </td>
              <td class='right'>
                <a class='icon-e' href="<?php echo base_url ($uri_1, $parent->id, $uri_2, $obj->id, 'edit');?>"></a>
                /
                <a class='icon-t' href="<?php echo base_url ($uri_1, $parent->id, $uri_2, $obj->id);?>" data-method='delete'></a>
              </td>
            </tr>
    <?php }
        } else { ?>
          <tr>
            <td colspan='5' class='no_data'>沒有任何資料。</td>
          </tr>
  <?php } ?>
      </tbody>
    </table>

    <div class='pagination'>
      <?php echo $pagination;?>
    </div>

  </div>
</div>

