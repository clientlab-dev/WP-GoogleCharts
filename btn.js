(function() {
    tinymce.PluginManager.add('ex_first_button', function( editor, url ) {
        editor.addButton( 'ex_first_button', {
            text: 'GoogleChart',
            icon: false,
            onclick: function() {

								var text = tinyMCE.activeEditor.selection.getContent();

					 		//	console.log(text);

								if (text.length>6) {
									jQuery.ajax({
										url: GoogleCharts.GCS_URL + "shortcode-parser.php",
										data: {shortcode_text:text},
										method: "POST",
										success:function(data){
											if (data=='') {

												tinyMCE.activeEditor.notificationManager.open({
													text: 'Выделенный текст не распознан как шорткод googleChart',
													type: 'error'
												} );
												return false;
											}
											data = JSON.parse(data);
											var query = serialize(data);

											tinyMCE.activeEditor.windowManager.open({
												url: GoogleCharts.GCS_URL + "tiny-mce-modal-form.php?"+query,
												width: 480,
												height: 640
											}, {
												custom_param: 1,
												tinymce: tinymce
											});
										},
										error:function(){
											tinyMCE.activeEditor.notificationManager.open({
												text: 'Не удалось распознать шорткод. Попробуйте снова выделив текст шорткода полностью включая []',
												type: 'error'
											} );
											return false;
										}
									});
								}else{
									tinyMCE.activeEditor.windowManager.open({
										url: GoogleCharts.GCS_URL + "tiny-mce-modal-form.php",
										width: 480,
										height: 640
									}, {
										custom_param: 1,
										tinymce: tinymce
									});
								}

            }
        });
    });
})();
function serialize(obj) {
  var str = [];
  for (var p in obj)
    if (obj.hasOwnProperty(p)) {
      str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
    }
  return str.join("&");
}
