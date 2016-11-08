<header>
  <div class='title'>
    <h1>窗口列表</h1>
    <p><?php echo $parent->name;?></a> 的窗口</p>
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
    <h2><a href='<?php echo base_url ('admin', 'invoice-contacts');?>'><?php echo $parent->name;?></a> 的聯絡窗口</h2>
    <a href='<?php echo base_url ($uri_1, $parent->id, $uri_2, 'add');?>' class='icon-r'></a>
  </header>

  <div class='content'>


    <table class='table'>
      <thead>
        <tr>
          <th width='80'>#</th>
          <th >名稱</th>
          <th width='100'>帳務數</th>
          <th width='85' class='right'>修改/刪除</th>
        </tr>
      </thead>
      <tbody>
  <?php if ($objs) {
          foreach ($objs as $obj) { ?>
            <tr>
              <td><?php echo $obj->id;?></td>
              <td><?php echo $obj->name;?></td>
              <td><?php echo count ($obj->invoices);?></td>
              <td class='right'>
                <a class='icon-e' href="<?php echo base_url ($uri_1, $parent->id, $uri_2, $obj->id, 'edit');?>"></a>
                /
                <a class='icon-t' href="<?php echo base_url ($uri_1, $parent->id, $uri_2, $obj->id);?>" data-method='delete'></a>
              </td>
            </tr>
    <?php }
        } else { ?>
          <tr>
            <td colspan='4' class='no_data'>沒有任何資料。</td>
          </tr>
  <?php } ?>
      </tbody>
    </table>

    <div class='pagination'>
      <?php echo $pagination;?>
    </div>

  </div>
</div>

