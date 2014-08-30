/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	CKEDITOR.config.language = 'en';
	CKEDITOR.config.toolbarCanCollapse = false;
	
	CKEDITOR.scriptLoader.load( CKEDITOR.basePath+'plugins/image_uploader/plugin.js' );
	//CKEDITOR.scriptLoader.load( CKEDITOR.basePath+'plugins/file_uploader/plugin.js' );
	   
	CKEDITOR.config.skin = 'office2003'; 
	//CKEDITOR.config.skin = 'v2'; 

	CKEDITOR.config.resize_enabled = false;
	
};
