$(function () {
  /**
   * Public variable.
   *
   * @type {*}
   */
  var url = $(location).attr('href').split("?")[0],
      _token=$('input[name=_token]').val();
/** Start: The all method work on the user view in this area. */
  /**
   * Get all form data when user click the button in the option.
   */
  $('.db-href').click(function (){
    var all_data = window.atob($(this).parent().children('input[name=all_data]').val()).split("||");
      id = all_data[0],
      name = all_data[1],
      email = all_data[2],
      _token = _token,
      status = all_data[3],
      currentClass = $(this).attr('class').split(" ")[1].split("-")[1];
    if ( currentClass === 'removed' || currentClass === 'disabled' ) {
      if (confirm('Are you sure you want to do this?')) {
        if (currentClass === 'removed')
          sendMsg(url, '', {id: id, status: status, _token: _token}, 'delete', false);
        else if (currentClass === 'disabled')
          sendMsg(url, 'disabled', {id: id, status: status, _token: _token}, 'put', false);
        return false;
      }
    }else if (currentClass === 'edit') {
      $('#user-form').append('<input type="hidden" name="_method" value="put" /><input type="hidden" name="id" value="'+id+'" />');
      $('input[name="name"]').val(name);
      $('input[name="email"]').val(email);
      $('input[name="_token"]').val(_token);
      if (status == 4) {
        var info = $('#tr_'+id+' .user_status').html();
        $('#status').html('<option value=4 selected="true">'+info+'</option>');
      }else {
        $('#status option[value=' + status + ']').attr('selected', true);
      }
    }else if(currentClass === 'oauth'){
      var url_arr = url.split('/');
      url_arr.pop();
      var new_url = url_arr.join('/')+'/dashboard';
      sendMsg(new_url,'create',{id:id, _token: _token}, 'post', false);
    }
  });
/** End: The all method work on the user view in this area. */
/** Start: The all method work on the menu view in this area. */
  $(document).on('click', '.db-href-menu', function (){
    var all_data = window.atob($(this).parent().children('input[name=all_data]').val()).split("||"),
        cat_id = all_data[0],
        cat_name = all_data[1],
        is_display = all_data[2],
        type = all_data[3],
        cat_url = all_data[4],
        sort = all_data[5],
        fid = all_data[6],
        id = $(this).parent().parent().children('td:first').children().val(),
        currentClass = $(this).attr('class').split(" ")[1].split("-")[1];
    if ( currentClass === 'removed' ) {
      if (confirm('Are you sure you want to do this?')) {
        sendMsg(url, '', {id: id, _token: _token}, 'delete', false);
        return false;
      }
    }else if ( currentClass === 'disabled' || currentClass === 'enabled' ) {
      sendMsg(url, currentClass, {id: id, op: currentClass, _token: _token}, 'put', false);
      if(currentClass === 'disabled') {
        $(this).attr('class', 'db-href-menu db-enabled');
        $(this).html('Enable');
        $('#tr_'+id+' .db-display').html('hidden');
      }
      if(currentClass === 'enabled') {
        $(this).attr('class', 'db-href-menu db-disabled');
        $(this).html('Disable');
        $('#tr_'+id+' .db-display').html('show');
      }
      return false;
    }else if (currentClass === 'edit') {
      $('#user-form').append('<input type="hidden" name="_method" value="put" /><input type="hidden" name="id" value="'+cat_id+'" />');
      $('input[name="cat_name"]').val(cat_name);
      $('#fid option[value='+fid+']').attr('selected', true);
      $('#type option[value='+type+']').attr('selected', true);
      $('#fid option[value='+cat_id+']').remove();
      $('input[name="url"]').val(cat_url);
      $('#display option[value='+is_display+']').attr('selected', true);
    }
  });
  $('.sub_menu').click(function (){
    var class_str = $(this).attr('class');
    if (class_str == 'sub_menu') {
      var cat_id = $(this).parent().parent().attr('id').split("_")[1],
          cat_name = $(this).parent().parent().children('.cat_name').html();
      sendMsg(url, 'subMenu', {id: cat_id, cat_name: cat_name, _token: _token}, 'post', true);
    }else{
      var id = $(this).attr('id').split('_')[2];
      $('.tr_fid_'+id).remove();
      $('#sub_menu_'+id).attr('class', 'sub_menu')
    }
  });
/** End: The all method work on the menu view in this area. */
/** Start: The all method work on the content view in this area. */
  $('.db-href-content').click(function(){
    var check = $('#create_table').attr('class').indexOf('in');
    if(check !== -1) {
      return false;
    }
    var id = $(this).parent().parent().children('td:first').children().val(),
        user_id = $(this).parent().parent().children('td.user_id').attr('id'),
        currentClass = $(this).attr('class').split(" ")[1].split("-")[1];
    if (currentClass == 'edit') {
      $.post(url + '/getOldData', {id: id, _token: _token},
        function (res) {
          var res = $.parseJSON(res);
          if (res.success === 1) {
            var url_arr = url.split('/');
            url_arr.pop();
            var url_final = url_arr.join("/");
            var thumbnail = url_final + '/..' + res.result.thumb;
            $('#thumbnail').attr('src', thumbnail);
            $('#content-form input[name=title]').val(res.result.title);
            $('.note-editable').html(res.result.body);
            $('#summernote').html(res.result.body);
            $('#content-form').append('<input type="hidden" name="_method" value="put" /><input type="hidden" name="id" value='+id+' readonly="true" /><input type="hidden" name="user_id" value='+user_id+' readonly="true" />');
            $('#comment_status option[value="' + res.result.status + '"]').attr('selected', true);
            $('#state option[value="' + res.result.state + '"]').attr('selected', true);
            $('#cat_id option[value="' + res.result.cat_id + '"]').attr('selected', true);
            $('tr[id!="tr_' + id + '"]').attr('class', 'hidden');
            var old_e_class = $('#tr_'+id+' .db-edit').attr('class');
            $('#tr_'+id+' .db-edit').attr('class', old_e_class+' hidden');
            var old_class = $('#tr_'+id+' .db-removed').attr('class');
            $('#tr_'+id+' .db-removed').attr('class', old_class+' hidden');
            $('#tr_'+id+' span').attr('class', 'hidden');
            $('#tr_'+id+' #option').append('<a class="collapse-close">Close</a>');
            $('.collapse').collapse('show');
          } else if (res.success === 0) {
            $('#alert-static').attr('class', 'alert alert-danger');
          } else if (res.success === -1) {
            $('#alert-static').attr('class', 'alert alert-warning');
          }
          return false;
        });
    } else if (currentClass == 'removed') {
      if (confirm('Are you sure you want to do this?')) {
        sendMsg(url, '', {id: id, _token: _token}, 'delete', false);
        return false;
      }
    }
  });
  $(document).on('click', '.collapse-close', function() {
    var id = $('#content-form input[name=id]').val();
    $('#user-form').attr('action', url+'/create');
    $('#content-form input[name!=_token]').val('');
    $('#content-form input[name=_method]').remove();
    $('#content-form option').removeAttr('selected');
    $('.note-editable').html('');
    $('#content-form input[name=id]').remove();
    $('#content-form input[name=user_id]').remove();
    $('.collapse').collapse('hide');
    $('tr[id!="tr_' + id + '"]').attr('class', '');
    var old_e_class = $('#tr_'+id+' .db-edit').attr('class');
    var raw_e_class = old_e_class.substring(0, old_e_class.indexOf('hidden'));
    $('#tr_'+id+' .db-edit').attr('class', raw_e_class);
    var old_class = $('#tr_'+id+' .db-removed').attr('class');
    var raw_class = old_class.substring(0, old_class.indexOf('hidden'));
    $('#tr_'+id+' .db-removed').attr('class', raw_class);
    $('#tr_'+id+' span').attr('class', '');
    $('#tr_'+id+' #option .collapse-close').remove();
    return false;
  });
  $('#now_state').change(function() {
    var state = $(this).children('option:selected').val();
    if( state == -1 )
      window.location.href = url;
    else
      window.location.href = url+'?state='+state;
  });
/** End: The all method work on the content view in this area. */

/** Start: The all method work on the fields view in this area. */
  $('.db-href-fields').click(function() {
    var id = $(this).parent().parent().children('td:first').children().val(),
      currentClass = $(this).attr('class').split(" ")[1].split("-")[1];
    if (currentClass == 'removed') {
      sendMsg(url, 'fields', {id: id, _token: _token, html_id: 'tr'}, 'delete', false);
    } else if (currentClass == 'edit') {
      var all_data = window.atob($(this).parent().children('input[name=all_data]').val()).split("||"),
        field_label = all_data[0],
        field_key = all_data[1],
        field_params = all_data[2],
        field_publish = all_data[3],
        field_type = all_data[4];
      if (url.substr(url.length-1, 1) !== '/')
        url = url + '/';
      $('#field-form').append('<input type="hidden" name="_method" value="put" /><input type="hidden" name="id" value="'+id+'" readonly="true" />');
      $('#field-form input[name=label]').val(field_label);
      $('#field-form input[name=key]').val(field_key);
      $('#field-form input[name=params]').val(field_params);
      $('#field-form input[name=publish]').val(field_publish);
      $('#field-form input[name=field_type]').val(field_type);
      $('#field-form #publish option[value="' + field_publish + '"]').attr('selected', true);
      $('#field-form #field_type option[value="' + field_type + '"]').attr('selected', true);
    }
  });
  $('.db-link-edit').click(function() {
    var id = $(this).parent().parent().children('td:first').children().val(),
      all_data = window.atob($(this).parent().children('input[name=all_data]').val()).split("||"),
      link_title = all_data[0],
      link_url = all_data[1],
      link_status = all_data[2],
      link_description = all_data[3],
      link_image = all_data[4];
    if (url.substr(url.length-1, 1) !== '/')
      url = url + '/';
    $('#link-form').append('<input type="hidden" name="_method" value="put" /><input type="hidden" name="id" value="'+id+'" readonly="true" />');
    $('#link-form input[name=title]').val(link_title);
    $('#link-form input[name=url]').val(link_url);
    $('#link-form input[name=status]').val(link_status);
    $('#link-form #description').html(link_description);
    $('#thumb').attr('src', link_image);
  });
  $('.db-link-delete').click(function() {
    var id = $(this).parent().parent().children('td:first').children().val();
    sendMsg(url , 'link', {id: id, _token: _token, html_id: 'link'}, 'delete', false);
  });
  $('.click_create').click(function (){
    $('.can_be_clear').val('');
    $('input[name=_method]').remove();
    $('textarea').html('');
  })
/** End: The all method work on the fields view in this area. */
/** Start: The all method work on the oauth view in this area. */
  $('.db-oauth').click(function() {
    var currentClass = $(this).attr('class').split(" ")[1].split("-")[1];
    if ( currentClass == 'create') {
      sendMsg(url, '', {_token:'oauth'}, 'post', false);
    }else if( currentClass == 'refresh'){
      sendMsg(url, '', {_token:'oauth'}, 'put', false);
    }
  });
/** End: The all method work on the oauth view in this area. */
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
  $('.links_checkbox').change(function (){
    $('.links_checkbox').prop("checked",this.checked);
    $('.link_checkbox').prop("checked",this.checked);
  });
  $('.fields_checkbox').change(function (){
    $('.fields_checkbox').prop("checked",this.checked);
    $('.field_checkbox').prop("checked",this.checked);
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
      ids=[];
    $.each($('.checkbox:checked'), function(){
      ids.push($(this).val());
    });
    if(ids.length > 0){
      sendMsg(url, 'multiOperation', {ids:ids, _token: _token, op: currentClass}, 'delete', false);
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
  var sendMsg = function (url, operation, data, request_type, returnStatus) {
    if (operation.length > 0)
      url = url+'/'+operation;
    $.ajax({
      url: url,
      dataType: 'json',
      data: data,
      type: request_type,
      success: function(res){
        if(returnStatus) {
          if (res.success === 1) {
            res.result.forEach(function(cv){
              var html = '<tr id="tr_'+cv.id+'" class="bg-white tr_fid_'+cv.fid+' child_category">';
              html += '<td><input type="checkbox" class="checkbox" name="id" value="'+cv.id+'"/></td>';
              html += '<td>'+data.cat_name+'</td>';
              html += '<td class="cat_name">'+cv.cat_name+'</td>';
              html += '<td class="db-display">'+cv.display+'</td>';
              html += '<td>'+cv.type+'</td>';
              html += '<td>'+cv.url+'</td>';
              html += '<td><input type="hidden" name="all_data" value="'+window.btoa(cv.id+'||'+cv.cat_name+'||'+cv.display+'||'+cv.type+'||'+cv.url+'||'+cv.sort+'||'+cv.fid)+'" readonly><a data-toggle="modal" data-target="#myModal" class="db-href-menu db-edit">Edit</a>&nbsp;|&nbsp;<a class="db-href-menu db-removed">Remove</a>&nbsp;</td>';
              html += '<td>sub menu</td></tr>';
              $('#tr_'+cv.fid).after(html);
            });
            $('#sub_menu_'+data.id).attr('class', 'sub_menu_close');
            return res;
          }else{
            $('#alert-static').attr('class', 'alert alert-warning');
            $('#alert-static').html('<p>'+res.result+'</p>')
            return res;
          }
        }
        $('.will-hide').hide();
        $('#alert-static p').html(res.result);
        if (res.success === 1) {
          if(res.result.info){
            $('#alert-static').html(res.result.info);
            $('#oauth_id').html(res.result.data.id);
            $('#oauth_secret').html(res.result.data.secret);
            $('#alert-static').attr('class', 'alert alert-success');
          }else{
            $('#alert-static').attr('class', 'alert alert-success');
            if (request_type === 'delete') {
              if(data.html_id){
                $('#' + data.html_id + '_' + data.id).remove();
              }else{
                $('#tr_' + data.id).remove();
              }
            }
          }
        } else if (res.success === 0) {
          $('#alert-static').attr('class', 'alert alert-danger');
        } else if (res.success === -1) {
          $('#alert-static').attr('class', 'alert alert-warning');
        }
        return false;
      },
      error: function(err){
        console.log(err);
        window.location.href = url + '/error';
      }
    });
  }
  var getImgUrl = function ( showDefImg ) {
    var str = url.substring(url.length-1, url.length);
    if (str !== '/') {
      var url_arr = url.split('/');
      url_arr.pop();
      url_arr.pop();
      var url_str = url_arr.join('/');
      if (showDefImg) {
        url_str = url_str+'/storage/uploads/default.png';
      }
    }else{
      if (showDefImg){
        url_str = url+'../storage/uploads/default.png';
      }else{
        var url_arr = url.split('/');
        url_arr.pop();
        url_arr.pop();
        var url_str = url_arr.join('/');
      }
    }
    return url_str;
  };
});