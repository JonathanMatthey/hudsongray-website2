var ckeditor_file_plugin = new Class({
	
	Implements: [Options, Events],
	
	options: {
		language:null,
		config:null
	},
	 //dialog window
	dialogDefinition: {
	},
	
	//element tree iterator
	counter: 1,
	
	selectcounter:1,
	
	uploadCounter:1,
	
	//fancy upload
	fancy:null,
	
	loadImage:null,
	
	search:null,
	
	editInput:null,
	
	editInputImg:null,
	
	editFileChange:null,
	
	mouseEnterElement:null,
	
	selectItem: null,
	
	inputCount:1,
	
	//ckeditor element
	ckeditor: {
	},
	
	//initialize
	initialize: function(options){	
		this.setOptions(options);
	},
	
	//set dialog definition
	setDialogDefinition: function(dialogDefinition){
		this.dialogDefinition = dialogDefinition;
	},
	
	//set ckeditor
	setEditor: function(editor){
		if(this.ckeditor != editor)
		{
			this.ckeditor = editor;
			this.setLink();
		}
	},
	
	//get directory tree
	getDir: function(obj){
		if(obj != null && obj.get('text') != '/')
		{
			var id = obj.getParent().getParent().getParent().get('id');
			id = id.split('_');
			id=id[3];
			var newObj = $('cke_span_'+id);
			return this.getDir(newObj) + obj.get('text')+'/';
		}
		else
			return '';
	},
	
	
	//try find directory children
	tryParent: function(obj){
		var id = obj.get('id');
		id = id.split('_');
		id=id[3];
		
		if(obj.getChildren().getFirst().length != 0)
		{
			
			if(!$('cke_dir_box_'+id).hasClass('hasDir'))
			{
				$('cke_dir_box_'+id).addClass('hasDir open');
			}
		}
		else
		{
			if($('cke_dir_box_'+id).hasClass('hasDir'))
			{
				$('cke_dir_box_'+id).removeClass('hasDir');
			}
			if($('cke_dir_box_'+id).hasClass('open'))
			{
				$('cke_dir_box_'+id).removeClass('open');
			}
		}
	},
	
	
	
	editElement: function(id)
	{
		if($('cke_im_'+id) != null)
		{
			var t = $('cke_im_'+id).get('name');
			$('cke_im_'+id).destroy();
			$('cke_span_'+id).set('text',t);
		}
		if($('cke_im_'+id) == null)
		{
			var thisTemp = this;
			var text = $('cke_span_'+id).get('text');
			var input = new Element('input', {'styles':{'display':'block', 'height':'14px', 'border':'1px #000 solid', 'background':'#fff'},'id':'cke_im_'+id,'name':$('cke_span_'+id).get('text'), 'value':$('cke_span_'+id).get('text')});
			$('cke_span_'+id).set('text','');
			input.inject($('cke_span_'+id));
			thisTemp.editInput = input;
			$('cke_im_'+id).focus();
			input.addEvent('keydown', function(e){
				if (e.key=='enter')
				{
					var obj = $('cke_span_'+id);
					var param = thisTemp.getDir(obj);
					param=param.replace('//', '/');
					param = param + text+'/';
					thisTemp.setTree({'type':'edit', 'param':param,'name':input.get('value'),'id':id},e.target.getParent(),text,input);
					thisTemp.editInput = null;
				}
			});
			input.addEvent('blur', function(e){
				var obj = $('cke_span_'+id);
				var param = thisTemp.getDir(obj)	
				param=param.replace('//', '/');
				param = param + text+'/';
				thisTemp.setTree({'type':'edit', 'param':param,'name':input.get('value'),'id':id},input.getParent(),text,input);
				thisTemp.editInput = null;
			});
			
		}
			
	},
	
	
	//create directory tree
	createDirTree: function(dir, objects, obj){
		var thisTemp = this;
		var dirBox = new Element('a',{'id':'cke_dir_box_'+thisTemp.counter, 'href':'#','styles':{'display':'block','float':'left', 'height':'9px','width':'9px','margin-top':'8px', 'margin-left':'-33px'}});
		var link = new Element('a',{'id':'cke_dir_link_'+thisTemp.counter, 'href':'#', 'class':'cke_dir','styles':{'display':'block'}});
		var span = new Element('span',{'id':'cke_span_'+thisTemp.counter, 'text':objects['name'],'class':'cke_dir span','styles':{'display':'block'}});
		var del = new Element('div',{'text':'del','class':'del','styles':{'display':'block','float':'right'}});
		var div = new Element('div', {'id':'cke_div_content_'+thisTemp.counter, 'class':'content cke_dir', 'styles':{'display':'block'}});
		var edit = new Element('div',{'id':'cke_edit_button_'+thisTemp.counter, 'text':'edit','class':'edit cke_dir','styles':{'display':'block','float':'right'}});
		if(obj != $('documents_ajax_tree'))
		{
			del.inject(div);
			edit.inject(div);
		}
		var tree = new Element('div', {'id':'cke_dir_div_'+thisTemp.counter});
		
		thisTemp.counter++; //increment counter
		
		dirBox.inject(link);
		link.inject(obj);
		div.inject(link);
		span.inject(div);
		tree.inject(obj);
		
		$each(objects['items'], function(el){
				thisTemp.createDirTree(dir+'/'+objects['name'], el,tree);
		});
		
		this.tryParent(tree);
		
		//plus icon click
		dirBox.addEvent('click', function(e){
			if(thisTemp.editInput != null)
				thisTemp.editInput.fireEvent('blur');
			e.stop();
			var id = e.target.get('id');
			id = id.split('_');
			id= id[3];
			if(dirBox.hasClass('open'))
			{
				dirBox.removeClass('open');
				$('cke_dir_div_'+id).setStyle('display','none');
			}
			else
			{
				dirBox.addClass('open');
				$('cke_dir_div_'+id).setStyle('display','block');
			}
		});
		
		//this event stop enter press
		link.addEvent('click',function(e){e.stop();});
		
		//mouse down on directory, edyt or del icons
		link.addEvent('mousedown', function(e){
			e.stop();
			//if del
			if(e.target.hasClass('del'))
			{
				var id = e.target.getParent().get('id');
				id = id.split('_');
				id=id[3];
				var answer = null;
				if($('cke_dir_div_'+id).getChildren().length == 0)
					answer = confirm(thisTemp.options.language.confirm);
				else
					answer = confirm(thisTemp.options.language.confirmTree);
				if(answer)
				{
					var obj = $('cke_span_'+id);
					thisTemp.setTree({'type':'del', 'param':thisTemp.getDir(obj),'id':id});
					
					thisTemp.selectcounter = 1;
				}
			}
			//if edit icons
			if(e.target.hasClass('edit'))
			{
				var id = e.target.getParent().get('id');
				id = id.split('_');
				id=id[3];
				thisTemp.editElement(id);
			}
			//if dir click
			
			if(e.target.hasClass('cke_dir') && !e.target.hasClass('edit'))
			{
				var id = e.target.get('id');
				id = id.split('_');	
				if(e.target.hasClass('span'))
					id=id[2];
				else
					id=id[3];
				var el = $('cke_dir_link_'+id);
				if(thisTemp.editInput != null && thisTemp.editInput.getParent().getParent().getParent() != el)
					thisTemp.editInput.fireEvent('blur');
				thisTemp.selectElement(el, id);				
			}
		});
		//directory mouse enter
		link.addEvent('mouseenter', function(e){
			if(e.target.hasClass('cke_dir'))
			{
				var id = e.target.get('id');
				id = id.split('_');	
				if(e.target.hasClass('span'))
					id=id[2];
				else
					id=id[3];
				if(thisTemp.mouseEnterElement != null)
					thisTemp.mouseEnterElement.removeClass('mouse');
				$('cke_dir_link_'+id).addClass('mouse');
				thisTemp.mouseEnterElement = $('cke_dir_link_'+id);
			}
		});
		//directory mouse leave
		link.addEvent('mouseleave', function(e){
			if(e.target.hasClass('cke_dir'))
			{//cke_dir_link_
				var id = e.target.get('id');
				id = id.split('_');	
				if(e.target.hasClass('span'))
					id=id[2];
				else
					id=id[3];
				$('cke_dir_link_'+id).removeClass('mouse');
				thisTemp.mouseEnterElement = null;
			}
		});
		return thisTemp.counter-1;
	},
	
	//when user click on directory
	selectElement: function(el, id){
		var thisTemp = this;
		var l = el.href;
		var s= $('cke_span_'+id);
		if(thisTemp.selectItem != null)
		{
			thisTemp.selectItem.getParent().getParent().removeClass('select');
		}
		s.getParent().getParent().addClass('select');
		thisTemp.selectItem = s;		
		var obj = $('cke_span_'+id);
		var param = thisTemp.getDir(obj);
		$('cke_url_box').getChildren('span')[0].set('text','/'+param);
		$('cke_dir_name').set('text', $('cke_span_'+id).get('text'));
		thisTemp.loadImg(param, id);
		thisTemp.selectcounter = id;
		$('cke_add_directory').removeEvents('click');
		$('cke_add_directory').addEvent('click', function(e){
			thisTemp.setTree({'type':'add', 'param':param, 'id':id});
		});
	},
	
	//get directory tree using JSON
	//or fire event add , edit, del fictionary
	setTree: function(dataTree,obj,obj1,obj2)
	{
		var thisTemp = this;
		if(thisTemp.selectcounter == 0 && dataTree['type'] == 'add')
			return; //if panel is in search mode user cant add directory
		$('cke_ajax_loader').setStyle('display','block');
		var jsonRequest = new Request.JSON({method:'post',url: this.options.config.all_action+'?action=dir_list', data:dataTree, onSuccess: function(dir){
			//when get tree
			if( typeof(dataTree) == "undefined" )
			{
				$('documents_ajax_tree').empty();
				obj = $('documents_ajax_tree');
				thisTemp.createDirTree('http:/', dir[0],obj);
				thisTemp.loadImg('', 1);
				$('cke_dir_link_1').addClass('select');
				thisTemp.selectItem = $('cke_span_'+1);
				thisTemp.selectcounter = '1';
				$('cke_add_directory').removeEvents('click');
				$('cke_dir_name').set('text', $('cke_span_'+1).get('text'));
				$('cke_add_directory').addEvent('click', function(e){
					thisTemp.setTree({'type':'add', 'param':'/', 'id':'1'});
				});
			}
			else if(dir == null)
			{	
				$('cke_ajax_loader').setStyle('display','none');
				return;
			}
			else
			{
				//when add dir
				if(dataTree['type'] == 'add' && dir['create'] != 'false')
				{
					
					var idNewDir = thisTemp.createDirTree('http://'+dataTree['param'], {'name':dir['create'],'items':[]}, $('cke_dir_div_'+dataTree['id']));
					thisTemp.tryParent($('cke_dir_div_'+dataTree['id']));
					thisTemp.editElement(idNewDir);
				}
				//when del tree
				else if(dataTree['type'] == 'del' && dir['create'] == 'true')
				{
					var id = $('cke_dir_div_'+dataTree['id']).getParent().get('id');
					id = id.split('_');
					id=id[3];
					if( $('cke_dir_div_'+dataTree['id']))
					{	
						$('cke_dir_div_'+dataTree['id']).empty();
						$('cke_dir_div_'+dataTree['id']).destroy();
					}
					if( $('cke_dir_link_'+dataTree['id']))
					{
						$('cke_dir_link_'+dataTree['id']).empty();
						$('cke_dir_link_'+dataTree['id']).destroy();
					}
					thisTemp.tryParent($('cke_dir_div_'+id));
					var is_select = false;
					$each($$('.cke_dir'), function(el){
						if(el.getParent().hasClass('select'))
							is_select = true;
					});
					if(!is_select)
						{
							$('cke_dir_link_1').addClass('select');
							thisTemp.selectItem = $('cke_span_'+1);
							thisTemp.selectcounter = '1';
							$('cke_add_directory').removeEvents('click');
							$('cke_add_directory').addEvent('click', function(e){
								thisTemp.setTree({'type':'add', 'param':'/', 'id':'1'});
							});
							thisTemp.loadImg('', 1);
						}
				}
				//when edit tree
				if(dataTree['type'] == 'edit')
				{
					if (dir['create'] == 'true')
						obj.set('text',obj2.get('value'));
					else
						obj.set('text',obj1);
					obj2.destroy();
					var param = thisTemp.getDir($('cke_span_'+dataTree['id']));
					$('cke_url_box').getChildren('span')[0].set('text','/'+param);
					
					
					thisTemp.selectcounter = dataTree['id'];
					$each($$('.cke_dir'), function(el){
						if(el.getParent().getParent().hasClass('select'))
							el.getParent().getParent().removeClass('select');
					});
					thisTemp.loadImg(param, dataTree['id']);
					$('cke_dir_link_'+dataTree['id']).addClass('select');
					thisTemp.selectItem = $('cke_span_'+dataTree['id']);
					$('cke_add_directory').removeEvents('click');
					$('cke_add_directory').addEvent('click', function(e){
						thisTemp.setTree({'type':'add', 'param':param, 'id':dataTree['id']});
					});	
					$('cke_dir_name').set('text', $('cke_span_'+dataTree['id']).get('text'));
					
				}
				if(dir['create'] == 'false')
					alert(thisTemp.options.language.serverError);
			}
			$('cke_ajax_loader').setStyle('display','none');
		}}).post();
	},
	
	imgScale: function(picture){
		if(picture['x'] > 115 || picture['y'] > 85)
		{
			var x = 100 * 115  / picture['x'];
			var y = 100 * 85  / picture['y'];
			var scale = null;
			if(x < y)
				scale = x;
			else
				scale = y;
			picture['x'] = parseInt(picture['x']* scale / 100);
			picture['y'] = parseInt(picture['y']* scale / 100);
		}
		
	},
	
	
	setExt: function(ext)
	{
		return CKEDITOR.basePath+"plugins/file_uploader/file_icons/"+ext+".png";
	},
	
	getfileExtension: function(fileinput) 
	{ 
		var thisTemp = this;
		var ret = "";
		if(!fileinput ) return ret; 
		var filename = fileinput; 
		if( filename.length == 0 ) return ret; 
		var dot = filename.lastIndexOf("."); 
		if( dot == -1 ) return ret; 
		var ret = filename.substr(dot+1,filename.length); 
		return ret; 
	},
	
	//add image to panel
	addImg: function(picture, id, thisTemp){
		var extension = thisTemp.getfileExtension(picture['file']);
		var link_a = new Element('a',{'href':thisTemp.options.config.all_action+'?action=list_action', 'class': 'image_cke', 'styles':{'border':'1px #dddddd solid', 'height':'95px', 'width':'125px', 'display':'block'}});
		var link_del = new Element('a',{'class':'del', 'text': 'DEL', 'href':thisTemp.options.config.all_action+'?action=del'});
		var link_edit = new Element('a',{'class':'edit', 'text': 'Edit', 'href':'/url////'+picture['src']});
		var span = new Element('span',{'text': picture['file'], 'class':'cke_desc'});
		thisTemp.imgScale(picture);
		//change path for jpg,gif,png
		var src = '/'+picture['src'];
		var type = src.substring(src.length-5,src.length).split('.');
		src = src.split('//');
		src = src.join('/');
		src = thisTemp.options.config.server_main_dir+src;
		

		
		
		var img = new Element('img',{events:{error:function(){},load:function(){}}, 'alt':src,'title':picture['file'], 'src':CKEDITOR.basePath+'plugins/file_uploader/file_icons/'+extension+'.png', 'styles':{'height':picture['y'],'width':picture['x'], 'padding-left':(115-picture['x'])/2+5, 'padding-right':(115-picture['x'])/2+5, 'padding-top':(85-picture['y'])/2+5, 'padding-bottom':(85-picture['y'])/2+5}});
		var div_el = new Element('div',{'styles':{'height':'115px','width':'127px', 'margin-left':'10px','margin-top':'10px', 'float': 'left'}});
		img.inject(link_a);
		link_a.innerHTML = '<img style="padding-top:'+((85-picture['y'])/2+5)+'px;padding-bottom:'+((85-picture['y'])/2+5)+'px;padding-right:'+((115-picture['x'])/2+5)+'px;padding-left:'+((115-picture['x'])/2+5)+'px;height:'+picture['y']+'px;width:'+picture['x']+'px;" alt="'+src+'" title="'+picture["file"]+'" src="'+CKEDITOR.basePath+'plugins/file_uploader/file_icons/'+extension+'.png" onError=\'this.src="'+CKEDITOR.basePath+'plugins/file_uploader/file_icons/default.png"\' />';
		// 
		link_edit.inject(div_el,'top');
		link_del.inject(div_el,'top');
		span.inject(div_el,'top');
		link_a.inject(div_el,'top');
		div_el.set('opacity','.' + 0);
		div_el.set('tween', {duration: 'long'});
		div_el.tween('opacity',1); 
		div_el.inject($('img_ck_panel_'+id),'top');
		div_el.addEvent('mouseenter', function(e){
			link_del.setStyle('visibility','visible');
			link_edit.setStyle('visibility','visible');
		});
		div_el.addEvent('mouseleave', function(e){
			link_del.setStyle('visibility','hidden');
			link_edit.setStyle('visibility','hidden');
		});
		
		link_edit.addEvent('click', function(e){
			e.stop();
			var span = e.target.getParent().getChildren('span')[0];
			var text = span.get('text');
			var input = new Element('input', {'styles':{'display':'block', 'height':'16px','float':'left','width':'92px', 'border':'1px #000 solid', 'background':'#fff'},'value':span.get('text')});
			span.setStyle('display','none');
			input.inject(span,'after');
			thisTemp.editInputImg = input;
			input.focus();
			input.addEvent('keydown', function(e){
				if (e.key=='enter')
				{
					thisTemp.editFile(input,span,link_edit,text);
				}
			});
			input.addEvent('blur', function(e){
				thisTemp.editFile(input,span,link_edit,text);
			});

		});
		
		link_del.addEvent('click', function(e){
			e.stop();
			var answer = confirm(thisTemp.options.language.confirm);
			if(answer)
			{
				//do not use getDir metod. In search mode it don't work.
				$('cke_ajax_loader').setStyle('display','block');
				var jsonRequest = new Request.JSON({method:'post',noCache:true,url: e.target, data:{'url':picture['src']}, onSuccess: function(p){
					if(p['create'] == 'true')
					{
						var parent = e.target.getParent();
						parent.empty();
						parent.destroy();
					}
					else
					{
						alert(thisTemp.options.language.serverError);
					}
					$('cke_ajax_loader').setStyle('display','none');
				}}).post();
			}
 		});

	},
	
	
	editFile: function(input, span, link_edit,text){
		var thisTemp = this;
		$('cke_ajax_loader').setStyle('display','block');
		idPanel = span.getParent().getParent().get('id');
		idPanel = idPanel.split('_');
		idPanel=idPanel[3];
		var url = null;
		if(idPanel == 0)
			url = link_edit.href;
		else
			url =  '////'+thisTemp.getDir($('cke_span_'+idPanel)) + text;
		var jsonRequest = null;
		if(thisTemp.editFileChange == null)
		{
			jsonRequest = new Request.JSON({method:'post',noCache:true,url: thisTemp.options.config.all_action+'/?action=edit', data:{'url':url,'name':input.get('value')}, onSuccess: function(p){
				if(p['create'] == 'false')
				{
					alert(thisTemp.options.language.serverErrorFile);
				}
				else
				{
					span.set('text', input.get('value'));
					if(idPanel == 0)
						link_edit.href = '/url////'+p['create'];
					//el = span.getParent().getChildren('.image_cke')[0].removeEvents();
					img = span.getParent().getChildren('.image_cke')[0].getChildren('img')[0];
					img.alt=thisTemp.options.config.server_main_dir + '/' + p["create"];
					img.title = p['file'];
					/*el.addEvent('click', function(e){
						e.stop();
						thisTemp.ckeditor.insertHtml(''+ thisTemp.options.config.server_main_dir + '/' + p["create"]+ '');
						thisTemp.dialogDefinition.dialog.hide();
					});*/
				}
				input.destroy();
				span.setStyle('display','block');
				$('cke_ajax_loader').setStyle('display','none');
				thisTemp.editFileChange = null;
			}}).post();
		}
		thisTemp.editFileChange = jsonRequest;
		thisTemp.editInputImg = null;
	},
	
	
	//set event click on image, and insert html to ckeditor
	setLink:function(){
		var tempThis = this;
		$each($('documents_ajax').getElements('.image_cke'), function(el, index){
			var idPanel = el.getParent().getParent().get('id');
			idPanel = idPanel.split('_');
			idPanel=idPanel[3];
    		el.removeEvents('click');
			el.addEvent('click', function(e){
					
    			var src = this.getElement('img').get('src');
    			id = el.getParent().getParent().get('id');
    			id = id.split('_');
				id=id[3];
    			e.stop();
    			if(idPanel != 0) // is not search
    			{
    				var dir =  tempThis.getDir($('cke_span_'+id));
    				tempThis.ckeditor.insertHtml(''+ tempThis.options.config.server_main_dir+'/'+dir+ this.getElement('img').get('title') +'"');
    			}
    			else  //is search
    			{
    				/*src = src.split('thumbnail');
    				src = tempThis.options.config.server_main_dir + src[1];
    				src = src.split('//');
    				src = src.join('/');*/
    				var alt = this.getElement('img').get('alt');
    				tempThis.ckeditor.insertHtml(''+alt +'');
    			}
				tempThis.dialogDefinition.dialog.hide();		
    		});
    	});
	},
	
	addInput: function(){
		var thisTemp = this;
		var input = new Element('input',{'accept':thisTemp.mimeType(), 'id':'cke_input_upload_'+thisTemp.inputCount, 'styles':{'display':'block'}, 'type':'file', 'name':'Filedata'+thisTemp.inputCount});
		input.inject($('cke_send_file_submit').getParent(), 'before');
		input.getParent().set('action',thisTemp.options.config.all_action+'/?action=update&multidata='+thisTemp.inputCount);
		thisTemp.inputCount++;
		input.addEvent('change', function(e){
			var form = input.getParent().getChildren('input');
			if($('cke_doubled_file') == null)
			{
				var span = new Element('span',{'id':'cke_doubled_file', 'text':thisTemp.options.language.file_doubled, 'styles':{'display':'none'}});
				span.inject($('cke_send_file_submit').getParent(),'after');
			}
			var doubled = false;
			$each(form, function(item){
				if(item.value === input.value && item !== input)
					doubled = true;
			});
			if(doubled)
			{
				$('cke_send_file_submit').removeEvents();
				$('cke_send_file_submit').addEvent('click', function(e){e.stop();alert(thisTemp.options.language.file_doubled);});
				$('cke_doubled_file').setStyle('display','block');
			}
			else
			{
				$('cke_send_file_submit').removeEvents();
				$('cke_send_file_submit').addEvent('click', function(e){
					idForm = input.getParent().get('id');
					idForm = idForm.split('_');
					idForm =idForm[4];
					$('cke_upload_form_file_'+idForm).getParent().setStyle('display','none');
					thisTemp.addNewUploadForm(thisTemp.uploadCounter++);
				});
				$('cke_doubled_file').setStyle('display','none');
			}
			id = input.get('id');
			id = id.split('_');
			id=id[3];
			if(input.get('value')!= null && ++id == thisTemp.inputCount)
			{
				thisTemp.addInput();
			}
		});
	},
	
	mimeType: function()
	{
		var file = this.options.config.typeFilter.split(';');
		var types = new Array();
		types.extend([
		             ["image/gif",0],//0
		             ["image/jpeg",0],//1
		             ["image/png",0],//2
		             ["image/bmp",0],//3
		             ["image/tiff",0]//4
		          ]);

		$each(file, function(el){
			var type = el.split('.');
			if(type[1] != null && type[1].length > 0)
				{
					if(type[1].toLowerCase() == 'jpg')
						types[1][1] = 1;
					if(type[1].toLowerCase() == 'jpeg')
						types[1][1] = 1;
					if(type[1].toLowerCase() == 'bmp')
						types[3][1] = 1;
					if(type[1].toLowerCase() == 'png')
						types[2][1] = 1;
					if(type[1].toLowerCase() == 'gif')
						types[0][1] = 1;
					if(type[1].toLowerCase() == 'tiff')
						types[4][1] = 1;
				}
		});
		var ret = '';
		$each(types, function(el){
			if(el[1])
			{
				if(ret.length > 0)
					ret+=',';
				ret+=el[0];
			}
		});
		return ret;
	},
	
	addNewUploadForm: function(id){
		var tempThis = this;
		tempThis.inputCount = 1;
		var li = new Element('li',{ 'styles':{}});
		li.innerHTML = '<form id="cke_upload_form_file_'+id+'" action="'+this.options.config.all_action+'/?action=update&multidata=" method="post" enctype="multipart/form-data"><div id="cke_send_div_submit"><input id="cke_send_file_submit" type="submit" name="submit" value="'+tempThis.options.language.send_files+'" /></div></form>';
		li.inject($('files_list'),'top');
		var list = $('files_list').getChildren('li');
		$each(list, function(el){
			if(el.getChildren().length == 0)
				el.destroy();
		});
		this.addInput();
		//ajax upload event
		if($('cke_ajax_upload_iframe_'+id) == null)
			iframe = new IFrame({id:'cke_ajax_upload_iframe_'+id, name: 'cke_ajax_upload_iframe_'+id,styles: {display: 'none'},src: 'about:blank',
        events: {load: function(self){
			var doc = document.getElementById('cke_ajax_upload_iframe_'+id).contentWindow.document;
			if(doc.body.innerHTML != null)
				var images = JSON.decode(doc.body.innerHTML);
			else
				images = null;
			if(images != null)
			{
				$each(images, function(image){
					if(image['name'] != null)
					{
						var obj = $('cke_span_'+tempThis.selectcounter);
						var param = tempThis.getDir(obj);
						$('cke_ajax_loader').setStyle('display','block');
						var jsonRequest = new Request.JSON({method:'post',noCache:true,data:{'url':param}, url: tempThis.options.config.all_action+"/?action=add&file="+image['name'], onSuccess: function(picture){
							$('cke_ajax_loader').setStyle('display','none');
							if(image['code'] == 1 && picture['error'] == null)
							{
								tempThis.addImg(picture, tempThis.selectcounter,tempThis);
								tempThis.setLink();
								var li = new Element('li',{'text': image['name']+' '+tempThis.options.language.file_added});
								li.inject($('files_list'));
							}
							else
								var li = new Element('li',{'text': image['name']+' '+tempThis.options.language.file_error});
							if($('cke_upload_form_file_'+id) != null)
								$('cke_upload_form_file_'+id).getParent().destroy();
							li.inject($('files_list'));
							$('cke_ajax_loader').setStyle('display','none');
						}}).post();
					}
			 	});
			}
        }}}).inject($(document.body),'top');
		$('cke_upload_form_file_'+id).set('target','cke_ajax_upload_iframe_'+id);
	},
	
	
	loadAjaxUploader: function(){
		var tempThis = this;
		this.addNewUploadForm(tempThis.uploadCounter++);
		//var add = new Element('a',{'id':'cke_new_form_button', 'class':'cke_dialog_ui_button_ok cke_dialog_ui_button'});
		/*add.inject($('close_upload_file'), 'before');
		add.addEvent('click', function(e){
			$('files_list').empty();
			e.stop();
			var id = tempThis.uploadCounter-1;
			if($('cke_ajax_upload_iframe_'+id) != null)
				$('cke_ajax_upload_iframe_'+id).destroy();
			tempThis.addNewUploadForm(tempThis.uploadCounter++);
		});*/
		$('files_attach').addEvent('click', function(e){
			e.stop();
			$('cke_load_panel').setStyle('display','block');
		});
	},
	
	//load image list in select directory
	loadImg: function(param, id, refresh){
		
		var tempThis = this;
		if(id !=0)
		{
			tempThis.selectItem = $('cke_span_'+id);
			tempThis.selectcounter = id;
		}
		if($('img_ck_panel_'+id) == null || refresh || id == 0)
		{
			var uri = tempThis.getDir($('cke_span_'+tempThis.selectcounter));
			if(id == 0 && param == '/////')
			{
				id = tempThis.selectcounter;
				tempThis.selectItem = $('cke_span_'+id);
				param = uri;
			}
			else if(id == 0)
			{
				param = uri+'#_!_#'+param;
			}
		/*	if(id == 0)
			{
				if(tempThis.selectItem != null)
				{
					tempThis.selectItem.getParent().getParent().removeClass('select');
					tempThis.selectItem = null;
				}
			}*/
			if($('img_ck_panel_'+id) != null)
				$('img_ck_panel_'+id).destroy();
			$('cke_ajax_loader').setStyle('display','block');
			if(tempThis.loadImage != null)
				tempThis.loadImage.cancel();
			var start =0;
			var jsonRequest = new Request.JSON({method:'post',noCache:true,urlEncode:true, data:{'param':param}, url: this.options.config.all_action+'?action=list_action', onSuccess: function(pictures){
			 var images_panel = new Element('div', {
			    'id': 'img_ck_panel_'+id,
			    'class' : 'cke_main_panel',
			    'styles': {
					'overflow': 'auto',
					'display':'block',
			        'width': '100%',
			        'height': '100%'
			    }
			});
			 $each($$('.cke_main_panel'), function(panel){
				panel.setStyle('display', 'none');
			 });
			images_panel.inject($('documents_ajax'));
			$each(pictures, function(pictureList){
			    $each(pictureList, function(picture, index){
			    	tempThis.addImg(picture, id,tempThis);
			    	start++;
			    });
			});
			tempThis.setLink();		
			$('cke_ajax_loader').setStyle('display','none');
			tempThis.loadImage = null;
			var obj = $('cke_span_'+tempThis.selectcounter);
			var url = '/'+tempThis.getDir(obj);
			//if('///////' == url)
			//	url = '<b style="color:red;">'+tempThis.options.language.empty_dir+'</b>';
			$('cke_url_upload').set('html',url);
			if(id != 0)
			{
				$('cke_search_input').value='';
				$('cke_reset_button').setStyle('visibility','hidden');
			}
		}}).post();
			tempThis.loadImage = jsonRequest;
		}
		else
		{
			$each($$('.cke_main_panel'), function(panel){	 
				panel.setStyle('display', 'none');
			 });
			$('img_ck_panel_'+id).setStyle('display','block');
			var url = '/'+tempThis.getDir($('cke_span_'+tempThis.selectcounter));
			$('cke_url_upload').set('html',url);
			if(id != 0)
			{
				$('cke_search_input').value='';
				$('cke_reset_button').setStyle('visibility','hidden');
			}
		}
		var obj = $('cke_span_'+id);
	},
	
	//this metod setFancy uploading panel
	setUploadPanel: function(){
		$('close_upload_file').addEvent('click', function(e){
			e.stop();
			$('cke_load_panel').setStyle('display','none');
		});
	}, 
	
	setRefresh: function(){
		var panel = this;
		$('cke_refresh').addEvent('click', function(e){
			e.stop();
			var obj = $('cke_span_'+panel.selectcounter);
			if(panel.selectcounter != 0)
			{
				var param = panel.getDir(obj);
				panel.loadImg(param, panel.selectcounter,true);
			}
			else
			{
				panel.loadImg(panel.search, panel.selectcounter,true);
			}
		});
	},
	
	setSearch: function(){
		var tempThis = this;
		$('cke_search_button').addEvent('click', function(e){
			e.stop();
			tempThis.loadImg('/////'+$('cke_search_input').get('value'), 0, false);
			//tempThis.selectcounter = 0;
			//$('cke_url_box').getChildren('span')[0].set('text',tempThis.options.language.search);
			//$('cke_dir_name').set('text', tempThis.options.language.search);
			tempThis.search = '/////'+$('cke_search_input').get('value');
		});
		$('cke_search_input').addEvent('keyup', function(e){
			e.stop();
			tempThis.loadImg('/////'+$('cke_search_input').get('value'), 0, false);
			//tempThis.selectcounter = 0;
			//$('cke_url_box').getChildren('span')[0].set('text',tempThis.options.language.search);
			//$('cke_dir_name').set('text', tempThis.options.language.search);
			tempThis.search = '/////'+$('cke_search_input').get('value');
			if($('cke_search_input').get('value') !='')
				$('cke_reset_button').setStyle('visibility','visible');
			else
				$('cke_reset_button').setStyle('visibility','hidden');
		});
		$('cke_search_button').addEvent('change', function(e){
			if($('cke_search_input').get('value') !='')
				$('cke_reset_button').setStyle('visibility','visible');
			else
				$('cke_reset_button').setStyle('visibility','hidden');
		});
		$('cke_reset_button').addEvent('click', function(e){
			$('cke_reset_button').setStyle('visibility','hidden');
			tempThis.loadImg('/////', 0, false);
		});
		
	},
	
	//fancy uploader
	loadFancy: function(){
		var tempThis = this;
		var up = new FancyUpload3.Attach('files_list', '#files_attach, #files_attach_another', {
			path: tempThis.options.config.uploader_swf,
			allowDuplicates: true,
		//	bar = 'c.jpg',
		//	progress = 'b.jpg',
			url: tempThis.options.config.all_action+'/?action=update&'+session_uri,
			fileSizeMax: tempThis.options.config.file_size_max * 1024 * 1024,
			verbose: tempThis.options.config.verbose,
			appendCookieData: tempThis.options.config.appendCookieData,
			fileListMax: tempThis.options.config.fileListMax,
			typeFilter: tempThis.options.config.typeFilter,
			
			onSelectSuccess: function(files) {
				$('cke_load_panel').setStyle('display','block');
			},
		
			onSelectFail: function(files) {
				files.each(function(file) {
					new Element('li', {
						'class': 'file-invalid',
						events: {
							click: function() {
								this.destroy();
							}
						}
					}).adopt(
						new Element('span', {html: file.validationErrorMessage || file.validationError})
					).inject(this.list, 'bottom');
				}, this);	
			},
	 
			onFileSuccess: function(file) {
				file.ui.element.highlight('#e6efc2');
				var obj = $('cke_span_'+tempThis.selectcounter);
				var param = tempThis.getDir(obj);
				$('cke_ajax_loader').setStyle('display','block');
				var jsonRequest = new Request.JSON({method:'post',noCache:true,data:{'url':param}, url: tempThis.options.config.all_action+"/?action=add&file="+file.response.name, onSuccess: function(picture){
					if(picture['error'] == null)
					{
						tempThis.addImg(picture, tempThis.selectcounter,tempThis);
						tempThis.setLink();
						file.ui.element.destroy();
					}
					else
					{
						var span = new Element('span', {text: tempThis.options.language.file_error});
						span.inject(file.ui.element);
					}
					if($('files_list').getChildren().length == 0)
					{
						//$('cke_load_panel').setStyle('display','none');
					}
					$('cke_ajax_loader').setStyle('display','none');
				}}).post();
				
			},
	 
			onFileError: function(file,e,m) {
				file.ui.cancel.set('html', tempThis.options.language.renew).removeEvents().addEvent('click', function() {
					file.requeue();
					return false;
				});
	 
				new Element('span', {
					html: file.errorMessage,
					'class': 'file-error'
				}).inject(file.ui.cancel, 'after');
			},
	 
			onFileRequeue: function(file) {
				file.ui.element.getElement('.file-error').destroy();
	 
				file.ui.cancel.set('html', tempThis.options.language.del).removeEvents().addEvent('click', function() {
					file.remove();
					return false;
				});
	 
				this.start();
			}
	 
		});
		this.fancy  = up;
	}
	
});