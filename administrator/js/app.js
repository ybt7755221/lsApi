$(document).ready(
  $('#field_button').click(function (){
    var status = $('#field_table').css('display');
    if ( status === 'block' ) {
      $('#field_button').html($('#show').html());
    } else {
      $('#field_button').html($('#hidden').html());
    }
  })
);