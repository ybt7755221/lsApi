$(document).ready(function() {
  /**
   * Self-adaption login page.
   */
  var window_height = $(document).height();
  var panel_height = $('.panel').height();
  panelInCenter(window_height, panel_height);
  window.onresize=function(){
    var now_w_height = $(document).height();
    if (window_height !== now_w_height) {
      var now_p_height = $('.panel').height();
      panelInCenter(now_w_height, now_p_height);
    }
  }
  /**
   * Click forget password show the dialog
   */
  $('#forget_password').click(function() {
    
  });

});
/**
 * Self-adaption login page.
 * private function
 */
function panelInCenter(window_height, panel_height) {
  var res = Math. floor( window_height - panel_height)/2;
  if ( res > 0 ) {
    $('.panel').css('margin-top', res + 'px');
  }
  return false;
}
