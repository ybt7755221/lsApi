$(function () {
/** Start: The all method work on the user view in this area. */
  /**
   * Get all form data when user click the button in the option.
   */
  $('.db-href').click(function (){
    var all_data = window.atob($(this).parent().children('input[name=all_data]').val()).split("-");
    var _token = $(this).parent().parent().parent().children('input[name=_token]').val(),
      id = all_data[0],
      name = all_data[1],
      email = all_data[2],
      _token = _token,
      url = $(location).attr('href').split("?")[0],
      status = all_data[3],
      currentClass = $(this).attr('class').split(" ")[1].split("-")[1];
    if ( currentClass === 'removed' || currentClass === 'disabled' ) {
      if (confirm('Are you sure you want to do this?')) {
        sendMsg(url, currentClass, {id: id, name: name, email: email, status: status, _token: _token});
        return false;
      }
    }else if (currentClass === 'edit') {
      $('#user-form').attr('action', url+'/'+currentClass);
      $('#user-form').append('<input type="hidden" name="id" value="'+id+'" />');
      $('input[name="name"]').val(name);
      $('input[name="email"]').val(email);
      $('input[name="_token"]').val(_token);
      $('#status option').eq(status).prop('selected', true);
    }
  });
/** End: The all method work on the user view in this area. */
/** Start: The all method work on the menu view in this area. */

/** End: The all method work on the menu view in this area. */
  /**
   * click table button can show or hidden table.
   */
  $('#field_button').click(function (){
    var status = $('#field_table').css('display');
    if ( status === 'block' ) {
      $('#field_button').html($('#show').html());
    } else {
      $('#field_button').html($('#hidden').html());
    }
  });
  /**
   * This method work for that check all checkbox.
   */
  $('.check_all').change(function (){
    $(':checkbox').prop("checked",this.checked);
  });
  /**
   * This function work for close the alert.
   */
  $('#alert-static-close').click(function (){
    $('#alert-static p').html('');
    $('#alert-static').attr('class', 'alert hidden');
  });
  /**
   * check the id and ensure send a post request to corresponding controller according to user click button.
   */
  $('.select_href').click(function (){
    var currentClass = $(this).attr('id'),
      ids=[],
      _token=$('input[name=_token]').val();
    url = $(location).attr('href').split("?")[0];
    $.each($('.checkbox:checked'), function(){
      ids.push($(this).val());
    });
    if(ids.length > 0){
      if (currentClass == 'disable') {
        sendMsg(url, 'multiOperation', {ids:ids, _token: _token, op:'disable'});
      } else if (currentClass === 'remove') {
        sendMsg(url, 'multiOperation', {ids:ids, _token: _token, op:'remove'});
      }
      setTimeout(function () {
        window.location.reload()
      }, 2000);
    }else{
      $('#alert-static p').html("You hadn't select anything.");
      $('#alert-static').attr('class', 'alert alert-warning');
    }
  });
  /**
   * This function send a post request to backend.
   *
   * @param url
   * @param operation
   * @param data
   */
  var sendMsg = function (url, operation, data) {
    $.post(url + '/' + operation, data, function (res, status) {
      var res = $.parseJSON(res);
      $('.will-hide').hide();
      $('#alert-static p').html(res.result);
      if (res.success === 1) {
        $('#alert-static').attr('class', 'alert alert-success');
        if (operation === 'removed') {
          $('#tr_' + data.id).remove();
        }
      } else if (res.success === 0){
        $('#alert-static').attr('class', 'alert alert-danger');
      } else if (res.success === -1){
        $('#alert-static').attr('class', 'alert alert-warning');
      }
    });
  };
});