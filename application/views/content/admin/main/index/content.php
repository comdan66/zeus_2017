<div class='panel'>
  <header>
    <h2>基本資料</h2>
  </header>

  <div class='person'>
    <div class='info'>
      <figure class='avatar _it' href='<?php echo $user->avatar (200, 200);?>'>
        <img src='<?php echo $user->avatar (200, 200);?>' />
        <figcaption data-description='<?php echo $user->name;?>'><?php echo $user->name;?></figcaption>
      </figure>
      <h1><?php echo $user->name;?></h1>
      <span class='icon-ma' title='電子信箱'><a href='mailto:<?php echo $user->email;?>'><?php echo $user->email;?></a></span>
      <span class='icon-fb' title='臉書鏈結'><?php echo mini_link ('https://www.facebook.com/' . $user->uid);?></span>
      <span class='icon-si' title='登入時間'><?php echo $user->logined_at ? '<time datetime="' . $user->logined_at->format ('Y-m-d H:i:s') . '">' . $user->logined_at->format ('Y-m-d H:i:s') . '</time>' : '-';?> 登入</span>
      <span class='icon-cl' title='註冊時間'><?php echo $user->created_at ? '<time datetime="' . $user->created_at->format ('Y-m-d H:i:s') . '">' . $user->created_at->format ('Y-m-d H:i:s') . '</time>' : '-';?> 註冊</span>
      <h2>後台權限</h2>

<?php foreach ($user->roles as $role) { ?>
        <span class='icon-bo'><?php echo $role->name ();?></span>
<?php } ?>

    </div>
    <div class='datas'>
      <h2>活躍度</h2>
      <div class='chart n<?php echo count ($chart);?>'>
        <div class='lines'>
    <?php foreach ($chart as $count) { ?>
            <div><div data-count='<?php echo $count;?>'></div></div>
    <?php } ?>
        </div>
        <div class='titles'>
    <?php foreach (array_keys ($chart) as $data) { ?>
            <div title="<?php echo $data;?>"><?php echo date ('m/d', strtotime ($data));?></div>
    <?php } ?>
        </div>
      </div>

      <div class='tabs_title'>
        <a href='<?php echo base_url ('admin', 'my', 'schedules');?>'<?php echo $type == 'schedules' ? " class='active'" : '';?>>今日行程</a>
        <a href='<?php echo base_url ('admin', 'my', 'user_logs');?>'<?php echo $type == 'user_logs' ? " class='active'" : '';?>>活躍細節</a>
      </div>

      <div class='tabs_content <?php echo $type;?>'>
  <?php $today = date ('Y-m-d');
        $yesterday = date ('Y-m-d', strtotime (date ('Y-m-d') . '-1 day'));
        foreach ($user_logs as $date => $logs) { ?>
          <time><?php echo $date != $today ? $date != $yesterday ? $date : '昨天' : '今天';?></time>

    <?php foreach ($logs as $log) { ?>
            <div>
              <div class='<?php echo $log->icon;?>'></div>
              <div>
                <span class='note'>
                  <time><?php echo $log->created_at->format ('H:i');?></time>
                  <?php echo $log->content;?>
                </span>
          <?php if ($log->desc) { ?>
                  <span class='desc'><?php echo $log->desc;?></span>
          <?php } ?>
              </div>
            </div>
    <?php }
        } 
        if ($schedules) { ?>
          <div class='btns'>
            <button type='button' class='icon-r add'></button>
          </div>
    <?php foreach ($schedules as $schedule) { ?>
            <div class='schedule<?php echo $schedule->finish ? ' finished' : '';?>' data-id='<?php echo $schedule->id;?>' data-tag_id='<?php echo $schedule->schedule_tag_id;?>'>
              <a class='tag' style='background-color: <?php echo $schedule->tag ? $schedule->tag->color () : ScheduleTag::DEFAULT_COLOR;?>;'></a>

              <h3><?php echo $schedule->title;?></h3>
              <span<?php echo !$schedule->description ? ' class="no"' : '';?>><?php echo $schedule->description;?></span>
              <a class='delete icon-t' data-id='<?php echo $schedule->id;?>'></a>
              <a class='edit icon-e' data-id='<?php echo $schedule->id;?>'></a>
              <label class='checkbox' data-column='finish' data-url='<?php echo base_url ('admin', 'api', 'schedules', $schedule->id);?>'>
                <input type='checkbox'<?php echo $schedule->finish ? ' checked' : '';?> />
                <span></span>
              </label>
            </div>
    <?php }
        } ?>
        <div class='pagination'><?php echo $pagination;?></div>
      </div>
    </div>
  </div>
</div>
