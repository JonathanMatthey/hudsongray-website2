//load all script
if( typeof(CKEDITOR.config.dialogImgWeeby) == "undefined" )
{	
	
	if( typeof(document.cookie) != "undefined" )
	{
		ses_id = document.cookie.split('=')[1];
		session_uri = "s="+ses_id;
	}
	
	if( typeof(session_uri) == "undefined" )
	{	
		session_uri = 0;
	}
	
	var th = document.getElementsByTagName('head')[0];
	session_uri
	var s1 = document.createElement('script');
	s1.setAttribute('type','text/javascript');
	s1.setAttribute('src',CKEDITOR.basePath+'plugins/image_uploader/config_js.php?link='+CKEDITOR.basePath);
	th.appendChild(s1);
	var s2 = document.createElement('script');
	s2.setAttribute('type','text/javascript');
	if(CKEDITOR.config.language == '')
		CKEDITOR.config.language = 'en';
	s2.setAttribute('src',CKEDITOR.basePath+'plugins/image_uploader/lang/'+CKEDITOR.config.language.toLowerCase()+'.js');
	th.appendChild(s2);
	var s3 = document.createElement('script');
	s3.setAttribute('type','text/javascript');
	s3.setAttribute('src',CKEDITOR.basePath+'plugins/image_uploader/ckeditor_img_plugin.js');
	th.appendChild(s3);

	//load stylesheet
	if(document.createStyleSheet)  //for IE
	{
		document.createStyleSheet(CKEDITOR.basePath+'plugins/image_uploader/style/style.css');
	}
	else //for all
	{
		var styles = "@import url(' "+CKEDITOR.basePath+"plugins/image_uploader/style/style.css ');";
		var newSS=document.createElement('link');
		newSS.rel='stylesheet';
		newSS.href='data:text/css,'+escape(styles);
		document.getElementsByTagName("head")[0].appendChild(newSS);
	}
}

CKEDITOR.on( 'dialogDefinition', function( ev )
{		
	var dialogName = ev.data.name;
	var dialogDefinition = ev.data.definition;
	if ( dialogName == 'image' )
	{	
		if( typeof(CKEDITOR.config.dialogImgWeeby) == "undefined" )
		{	
			CKEDITOR.config.dialogImgWeeby = 0;
		}
		var start = 1;
		var lang = new language();
		var configuration = new configPlugin();
		var s5 = document.createElement('script');
		if(configuration.use_fancy_uploader && configuration.auto_config_fancy && CKEDITOR.config.dialogImgWeeby == 0 && typeof(CKEDITOR.config.fancy) == "undefined")
		{
			s5.setAttribute('type','text/javascript');
			s5.setAttribute('src',CKEDITOR.basePath+'plugins/image_uploader/fancy/Swiff.Uploader.js');
			th.appendChild(s5);
			var s6 = document.createElement('script');
			s6.setAttribute('type','text/javascript');
			s6.setAttribute('src',CKEDITOR.basePath+'plugins/image_uploader/fancy/Fx.ProgressBar.js');
			th.appendChild(s6);
			var s4 = document.createElement('script');
			s4.setAttribute('type','text/javascript');
			s4.setAttribute('src',CKEDITOR.basePath+'plugins/image_uploader/fancy/FancyUpload3.Attach.js');
			th.appendChild(s4);
			CKEDITOR.config.fancy = 1;
		}
		if(configuration.use_fancy_uploader && configuration.auto_config_fancy && CKEDITOR.config.dialogImgWeeby == 0)
		{
			configuration.uploader_swf = CKEDITOR.basePath+'plugins/image_uploader/fancy/Swiff.Uploader.swf';
		}
		if(configuration.use_fancy_uploader)
			CKEDITOR.config.baseFloatZIndex = configuration.fancy_uploader_z_index;
		var panel = new ckeditor_img_plugin({language:lang, config:configuration});
		
		var link = dialogDefinition.getContents('Link');
		var info = dialogDefinition.getContents('info');
		info['hidden'] = true;
		link['hidden'] = configuration.hide_link_panel;
		var advanced = dialogDefinition.getContents('advanced');
		advanced['hidden'] = configuration.hide_advanced_panel;;
			
		var weeby = {
			id:'cke_dialog_panel_'+lang.panel_name,
	        label:lang.panel_name, 
	        elements:[
	            {
	            	type:'html',
	            	width:700,
	            	html:'<img id="cke_ajax_loader" src="'+CKEDITOR.basePath+'plugins/image_uploader/style/ajax-loader.gif" width="79px" height="73px" alt="" style="position:absolute;display:block;margin-left:114px;margin-top:39px;opacity:0.9;background:#fff;padding-left:254px;padding-right:254px;padding-top:157px;padding-bottom:157px;">'
          				+'<div id="cke_load_panel" style="min-width:600px;min-height:405px;padding:50px;position: absolute;margin-top:-5px;display:none;">'
          					+'<ul style="width:600px;height:355px;" id="files_list">'
          					+'</ul>'
          					+'<span id="cke_url_upload">/</span>'
          					+'<a id="close_upload_file"  class="cke_dialog_ui_button_ok cke_dialog_ui_button" href="close">'
          						+'<span class="cke_dialog_ui_button">'
          							+lang.close
          						+'</span>'
          					+'</a>'
          				+'</div>'
	            		+'<div style="min-width:700px;min-height:425px;">'
		            		+'<div style="height:425px;width:115px;float:left;">'
		            			+'<div id="documents_ajax_tree" style="padding-left:15px;width:100px;height:425px;float:left;overflow: auto;" >'
		            			+'</div>'
		            		+'</div>'
		            		+'<div style="width:585px;height:425px;float:right;margin:0 0 0 0;padding:0;border:0;">'
		            			+'<div style="width:585px;height:75px;background:#fff;display:none;">'
		            				+'<div id="cke_url_box" style="margin-left:10px;border:1px #000 solid;padding-top:4px;margin-top:20px;width:300px;height:17px; float:left;overflow:hidden;">'
		            					+'<span style="padding-left:5px;">'
		            						+'/'
		            					+'</span>'
		            				+'</div>'
		            				+'<a href=# id="files_attach" class="cke_dialog_ui_button_ok cke_dialog_ui_button" style="margin-top:20px;margin-right:10px;width:110px; display:block; float:right;">'
		            					+'<span class="cke_dialog_ui_button">'
		            						+lang.add_picture
		            					+'</span>'
		            				+'</a>'
		            					+'<a href=# id="files_attach_another"  class="cke_dialog_ui_button_ok cke_dialog_ui_button" style="margin-top:20px;margin-right:10px;width:110px; display:block; float:right;display:none">'
		            					+'<span class="cke_dialog_ui_button">'
		            						+lang.add_picture
		            					+'</span>'
		            				+'</a>'
		            				+'<span style="display:block;margin-left:10px;float:left;margin-top:8px;">'
	            						+lang.add_picture_text
	            					+'</span>'
		            			+'</div>'
		            			+'<div style="width:585px;margin:0 0 0 0;height:40px;">'
		            				+'<a id="cke_refresh" href="#">'
		            					+'REFRESH'
		            				+'</a>'
		            				+'<span id="cke_dir_name" style="float:left;">'
		            				+'</span>'
		            				+'<form id="cke_search_form" action="#" metod="get">'
		            					+'<input id="cke_search_input" type="text" name="search" />'
		            					+'<input id="cke_reset_button" type="reset" value="" style="visibility:hidden;" />'
		            					+'<input id="cke_search_button" type="submit" value="" />'
		            				+'</form>'
		            			+'</div>'
		            			+'<div id="documents_ajax" style="width:585px;height:385px;background:#fff">'
		            			+'</div>'
		            		+'</div>' 		
	            		+'</div>',
	            	onLoad : function(){
	            		$each($$('.cke_dialog_tab'), function(el){
	            			var dialogWindow = dialogDefinition.dialog._.element.getFirst();
	            			if(el.get('title') == lang.panel_name)
	            			{
	            				dialogDefinition.dialog.firstLoad = 0;
	            				dialogDefinition.dialog._.currentTabIndex = 0;
		            			dialogDefinition.dialog._.currentTabid = "cke_dialog_panel_Pictures";
	            			
		            			if(start)
        						{
        							start = 0;
        							
        							panel.setDialogDefinition(dialogDefinition);
        		            		panel.setEditor(ev.editor);
        		            		panel.setTree();
        		            		if(configuration.use_fancy_uploader)
        		            		{
        		            			panel.loadFancy();
        		            		}
        		            		else
        		            		{
        		            			panel.loadAjaxUploader();
        		            		}
        		            		panel.setUploadPanel();
        		            		panel.setRefresh();
        		            		panel.setSearch();
        						}
		            			
	            			}
	            			

	            		});
	            		
	            	},
	            	onShow : function(){
	            		var dialogWindow = dialogDefinition.dialog._.element.getFirst();
	            		var x=dialogDefinition.dialog._.position.x-165;
						var y=dialogDefinition.dialog._.position.y;//-112;
        				if(x > document.getWidth()-734) x = document.getWidth()-734;
        				if(y > document.getHeight()-555) y = document.getHeight()-555;
        				if(x < 0) x = 0;
        				if(y < 0) y = 0;
        				dialogDefinition.dialog._.position.x = x;
        				dialogDefinition.dialog._.position.y = y;
        				dialogWindow.setStyles({left: x + "px", top: y + "px"});
	            	}
	            }]
		};	
		CKEDITOR.config.dialogImgWeeby ++;
		dialogDefinition.addContents(weeby, 'info');
		dialogDefinition.title = "Choose image from media library";
		
	} 
});
