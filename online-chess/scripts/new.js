function setupLoginDialog() {
  	$('#login-button').click( function() {
  		var dialogdiv = $('#login-dialog');
  		if (dialogdiv.css('display') == 'none') {
  			dialogdiv.show();
  			$('#loginwrapper').show();
  			setTimeout(function() {
  				dialogdiv.css('top', '70px');
  			}, 20);
  		}
  	});