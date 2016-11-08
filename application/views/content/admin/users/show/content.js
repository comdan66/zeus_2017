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

  $('.person .info .checkbox').each (function () {
    var $that = $(this);
    var $input = $(this).find ('input');
    var url = $(this).data ('url');
    var data = {_method: 'put', _type: 'api', roles: {}};

    $input.unbind ('change').change (function () {
      $that.get (0).oriVal = !$(this).prop ('checked');

      if (!url) {
        setTimeout (function () { $(this).prop ('checked', $that.removeClass ('loading').get (0).oriVal).prop ('disabled', false); }.bind ($(this)), 100);
        return false;
      }
      
      data.roles[$(this).val ()] = $(this).prop ('checked') ? 1 : 0;

      $.ajax ({ url: url, data: data, async: true, cache: false, dataType: 'json', type: 'POST',
        beforeSend: function () {
          $(this).prop ('disabled', true);
          $that.addClass ('loading');
        }.bind ($(this))
      })
      .done (function (result) {
        $(this).prop ('checked', result.roles.length && ($.inArray ($(this).val (), result.roles.column ('key')) != -1)).prop ('disabled', false);
        $that.removeClass ('loading').get (0).oriVal = $(this).prop ('checked');
      }.bind ($(this)))
      .fail (function (result) {
        $(this).prop ('checked', $that.removeClass ('loading').get (0).oriVal).prop ('disabled', false);
      }.bind ($(this)));
    });
  });


  // $('.schedules').sortable ({
  //   handle: 'h3',
  //   placeholder: 'highlight',
  //   update: function () {
  //     // updateSort ($('.daySchedule .schedule').map (function (i) {
  //     //   return {id: $(this).data ('id'), sort: i};
  //     // }).toArray ());
  //   }
  // });
});