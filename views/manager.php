<?php
/**
 * @var \yii\web\View $this
 * @var array $options

 */
use mihaildev\elfinder\Assets;
use yii\helpers\Json;


Assets::register($this);
Assets::addLangFile($options['lang'], $this);

$this->registerJs("
function ElFinderGetCommands(disabled){
    var Commands = elFinder.prototype._options.commands;
    $.each(disabled, function(i, cmd) {
        (idx = $.inArray(cmd, Commands)) !== -1 && Commands.splice(idx,1);
    });
    return Commands;
}

    var winHashOld = '';
    function elFinderFullscreen(){

        var width = $(window).width()-($('#elfinder').outerWidth(true) - $('#elfinder').width());
        var height = $(window).height()-($('#elfinder').outerHeight(true) - $('#elfinder').height());

        var el = $('#elfinder').elfinder('instance');

        var winhash = $(window).width() + '|' + $(window).height();


        if(winHashOld == winhash)
            return;

        winHashOld = winhash;

        el.resize(width, height);
    }

    $('#elfinder').elfinder(".Json::encode($options).").elfinder('instance');

    $(window).resize(elFinderFullscreen);

    elFinderFullscreen();
    ");
if(!empty($options['tinyMCE'])) {
	$this->registerJs("
		var findField = function(name, control){
			if(control.name() == name){
				return control;
			}

			if(control.items != null){
				var ta = control.items().toArray();
				for(var x = 0; x < ta.length; x++){
					var r = findField(name, ta[x]);
					if(r != null) return r;
				}
			}
			return null;
		}
		
		var FileBrowserDialogue = {
			init: function() {
				// Here goes your code for setting your custom things onLoad.
			},
			mySubmit: function (file) {
				var params = parent.tinymce.activeEditor.windowManager.getParams();
				params.setUrl(file.url);
				
				if(params.type == 'image') {
					var t = top.tinymce.activeEditor.windowManager.windows[0];
					var f = findField('width', t);    
					if(f != null){
						params.window.document.getElementById(f._id).value = file.width;
					}
					f = findField('height', t);    
					if(f != null) {
						params.window.document.getElementById(f._id).value = file.height;
					}
				} 
				
				parent.tinymce.activeEditor.windowManager.close(); // close popup window
			}
		}"
	);
	$this->registerCss("
	#elfinder, #elfinder .elfinder-toolbar {
		border-top-left-radius: 0;
		border-top-right-radius: 0;
	}
	");
}


$this->registerCss("
html, body {
    height: 100%;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    position: relative;
    padding: 0; margin: 0;
}
");




?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>elFinder 2.0</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div id="elfinder"></div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>