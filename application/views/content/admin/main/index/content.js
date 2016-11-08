/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  var max = $('.chart div[data-count]').map (function () {
    return $(this).data ('count');
  }).toArray ().max ();
  
  setTimeout (function () {
    $('.chart div[data-count]').each (function (i) {
      setTimeout (function () { $(this).attr ('title', $(this).data ('count') + '次').attr ('class', 'n' + parseInt ($(this).data ('count') / max * 100, 10)); }.bind ($(this)), i * 50);
    });
  }, 300);

  function initSchedule (t) {
    var $checkbox = $('<label />').addClass ('checkbox').data ('column', 'finish').data ('url', 'api/schedules/' + t.id).append (
      $('<input />').attr ('type', 'checkbox').prop ('checked', t.finish ? true : false)).append (
      $('<span />'));
    
    var $obj = $('<div />').data ('id', t.id).data ('tag_id', t.tag.id ? t.tag.id : 0).addClass ('schedule' + (t.finish ? ' finished' : '')).append (
      $('<a />').addClass ('tag').css ({'background-color': t.tag.color ? t.tag.color : '#000000'})).append (
      $('<h3 />').text (t.title)).append (
      $('<span />').addClass (t.description.length ? '': 'no').html (t.description)).append (
      $('<a />').addClass ('icon-t').data ('id', t.id).click (deleteSchedule)).append (
      $('<a />').addClass ('icon-e').data ('id', t.id).click (editSchedule)).append (
      $checkbox);
      
    window.funs.updateFlag ($checkbox, function (isTrue) {
      if (isTrue) $(this).parents ('.schedule').addClass ('finished');
      else $(this).parents ('.schedule').removeClass ('finished');
    });

    return $obj;
  }

  function createSchedule () {
    var $that = $(this);
    window.funs.schedulePrompt (function ($input, $textarea, $radios) {
      var title = $input.val ().trim ();
      var description = $textarea.val ().trim ();
      var prompt = $(this).get (0);
      var tag_id = $radios.find ('input:checked').val ();
      var time = new Date ();

      if (!title) return prompt.close ();

      var data = {
        year: time.getFullYear (),
        month: time.getMonth () + 1,
        day: time.getDate (),
        title: title,
        description: description,
      };
      if (tag_id) data.tag_id = tag_id;

      $.ajax ({
        url: window.vars.apis.schedules.postItem (),
        data: data,
        async: true, cache: false, dataType: 'json', type: 'POST',
        beforeSend: function () {
          prompt.loading ('讀取中..');
        }
      })
      .done (function (result) {
        prompt.result ('新增完成！');
        prompt.okCallback (function () {
          initSchedule (result).insertAfter ($that.parent ());
          prompt.close ();
        });
      })
      .fail (function (result) { window.funs.ajaxError (result); })
      .complete (function (result) {});
    }, '新增工作');
  }
  function editSchedule () {
    var $that = $(this);
    var $schedule = $that.parents ('.schedule');
    
    var $h3 = $schedule.find ('h3');
    var $span = $schedule.find ('>span');
    var $tag = $schedule.find ('.tag');
    
    window.funs.schedulePrompt (function ($input, $textarea, $radios) {
      var title = $input.val ().trim ();
      var description = $textarea.val ().trim ();
      var prompt = $(this).get (0);
      var tag_id = $radios.find ('input:checked').val ();

      if (!title) return prompt.close ();

      var data = {
        _method: 'put',
        title: title,
        description: description,
      };
      if (tag_id) data.tag_id = tag_id;

      $.ajax ({
        url: window.vars.apis.schedules.putItem ($that.data ('id')),
        data: data,
        async: true, cache: false, dataType: 'json', type: 'POST',
        beforeSend: function () {
          prompt.loading ('讀取中..');
        }
      })
      .done (function (result) {
        prompt.result ('更新完成！');
        prompt.okCallback (function () {
          $h3.text (result.title);
          $span.html (result.description);
          if (result.description.length) $span.removeClass ('no');
          else $span.addClass ('no');

          $schedule.data ('tag_id', result.tag.id ? result.tag.id : 0);
          $tag.css ({'background-color': result.tag.color ? result.tag.color : '#000000'});
          prompt.close ();
        });
      })
      .fail (function (result) { window.funs.ajaxError (result); })
      .complete (function (result) {});
    }, '修改工作', $h3.text (), $span.html (), $schedule.data ('tag_id'));
  }
  function deleteSchedule () {
    if (!confirm ('確定刪除？')) return false;

    var $schedule = $(this).parents ('.schedule');
    $.ajax ({
      url: window.vars.apis.schedules.deleteItem ($(this).data ('id')),
      async: true, cache: false, dataType: 'json', type: 'delete',
      beforeSend: function () {}
    })
    .done (function (result) {
      $schedule.remove ();
    })
    .fail (function (result) { window.funs.ajaxError (result); })
    .complete (function (result) {});
  }
  function updateSort (data) {
    if (!data.length) return;
    
    $.ajax ({
      url: window.vars.apis.schedules.postSort (),
      data: { data: data },
      async: true, cache: false, dataType: 'json', type: 'post'
    });
  }
  $('.add').click (createSchedule);
  $('.edit').click (editSchedule);
  $('.delete').click (deleteSchedule);

  window.funs.updateFlag ($('.schedules .schedule .checkbox'), function (isTrue) {
    if (isTrue) $(this).parents ('.schedule').addClass ('finished');
    else $(this).parents ('.schedule').removeClass ('finished');
  });

  $('.schedules').sortable ({
    handle: 'h3',
    placeholder: 'highlight',
    update: function () {
      updateSort ($('.schedules .schedule').map (function (i) {
        return {id: $(this).data ('id'), sort: i};
      }).toArray ());
    }
  });
  // $('h1').click (function () {
  //   window.funs.schedulePrompt (function ($input, $textarea, $radios) {
  //     var title = $input.val ().trim ();
  //     var description = $textarea.val ().trim ();
  //     var prompt = $(this).get (0);
  //     var $radio = $radios.find ('input:checked');

  //   }, 'asd', 1, 2, 1);
  // });

});