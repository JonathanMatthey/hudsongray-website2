<?php

	$config = parse_ini_file("ckeplugin.ini");	
	include_once("resizeimage.php");
	
	//variable initialize
	@ini_set('memory_limit', $config['max_file_size'].'M');
	@ini_set('post_max_size', $config['max_file_size'].'M');
	@ini_set('upload_max_filesize', $config['max_file_size'].'M');
	

	$config['public'] = $config['root'];
	if($config['public'] == '')
	{
		$lenght = strlen($_SERVER["DOCUMENT_ROOT"]);
		if($_SERVER["DOCUMENT_ROOT"][$lenght -1] != '/')
		{
			$config['public'] = $_SERVER["DOCUMENT_ROOT"].'/';
		}
		else
		{
			$config['public'] = $_SERVER["DOCUMENT_ROOT"];
		}
	}
	else
	{
		$lenght = strlen($_SERVER["DOCUMENT_ROOT"]);
		if($_SERVER["DOCUMENT_ROOT"][$lenght -1] != '/')
		{
			$config['public'] = $_SERVER["DOCUMENT_ROOT"].$config['root'] .'/';
		}
		else
		{
			$config['public'] = $_SERVER["DOCUMENT_ROOT"].'/'.$config['root'] .'/';
		}
	}
	if($config['temp_dir'] =='')
	{
		$config['temp_dir'] = $config['pictures'];
	}
	
	$config['pictures'] .='/';
	$config['temp_dir'] .='/.temp_pic/';
	
	//del tree function
	
	function delTree($dir, $conf) 
	{
	    $handler = @opendir($dir);
	    while ($file = @readdir($handler)) {
	    	if ($file != '.' && $file != '..')
	    	{
				if($dir.'/' != $conf && $file[0] != '.')
				{
		        	if( is_dir( $dir.'/'.$file ) )
		            	delTree( $dir.'/'.$file, $conf);
		        	else
		            	@unlink( $dir.'/'.$file );
				}
	    	}
	    }
	  
	  $ret = @rmdir( $dir );
	  return $ret;
	}
	
	//dir tree function
	//return in $ret all directory tree or file ftree is $search is set
	
	function dirtree($dir, $f, &$ret, $search=null, $directory=null)
	{
		
		$tree = array();
		$uri = $dir.'/'.$f;
		$uri = str_replace("//", "/", $uri);
		$handler = @opendir($uri);
		//open all directories
		while ($file = @readdir($handler)) 
		{
        	if ($file != '.' && $file != '..')
        	{
        		$items = $dir.'/'.$f;
        		if(is_dir($items.'/'.$file))
        		{
        			if($file[0] !='.')
        			{
	        			if($search == null)
	        				dirtree($items, $file, $tree);
	        			else
	        			{
	        				if($directory != '')
	        					$src = $directory.'/'.$file;
	        				else
	        					$src = $file;
	        				dirtree($items, $file, &$ret,$search, $src);
	        			}
        			}
        		}
        		else if($search != null) //if search
        		{
        			$file_parts = pathinfo($file);
	        		if(strstr(strtolower($file),strtolower($search))) //if search string is in file name
	        		{
	        			if($directory != '') //if root dir
        					$src = $directory.'/'.$file;
        				else
        					$src = $file;
	        			$item = array( 'src' => $src, 'file' => $file); 
	        			if(isset($param))
	        			$param="";
	        			if( @!is_dir($param.$file)) //if not dir and is picture file.
	        			{
	        				
	        				if(!strpos(strtolower($file_parts['basename']),"_thm_") && (strtolower($file_parts['extension']) == 'jpeg' || strtolower($file_parts['extension']) == 'jpg' || strtolower($file_parts['extension']) == 'png' || strtolower($file_parts['extension']) == 'gif' || strtolower($file_parts['extension']) == 'bmp'))
	        				{	
	        					preg_match( "(-[\d]+x[\d]+\.)", strtolower($file_parts['basename']), $pregResult);
	        					if(!isset($pregResult[0]))
	        					{
		        					$uri = $dir.'/'.$f.'/'.$file;
									$uri = str_replace("//", "/", $uri);
		        					@$size = getimagesize($uri);
		        					@$item['x'] = $size[0];
		        					@$item['y'] = $size[1];
		        					array_push($ret, $item);
	        					}
	        				}
	        			}
	        		}
        		}
        	}
    	}
    	if($search === null) // add dir to aray
    	{
    		$it = array('name' => $f,'items' => $tree);
    		array_push($ret,$it);
    	}
	}
	
	//test Thumbnail dir. If thumbnail not exist is created
	
	function tryThumbnail($config,$param, $file)
	{
		try{
	        $handlerDir = $config['public'].$config['temp_dir'].'.thumbnail/';
	        if (!is_dir($handlerDir))
			{
				if(@mkdir($handlerDir, 0777))
				{
					$create = @chmod($handlerDir, 0777);
				}
			}
	        $lenght = strlen($param);
			if($lenght > 0 && $param[$lenght -1] == '/')
			{
				$paramTest = substr($param, 0, -1);
				if(strlen($paramTest) > 0)
				{
					while($p = strpos($paramTest, '/'))
					{
						$handlerDir .= substr($paramTest,0,$p).'/';
						if (!is_dir($handlerDir))
						{
							if(@mkdir($handlerDir, 0777))
							{
								$create = @chmod($handlerDir, 0777);
							}
						}
						$paramTest = strstr($paramTest, '/');
						$paramTest = substr($paramTest,1);
					}
					$handlerDir .= $paramTest.'/';
					if (!is_dir($handlerDir))
					{
						if(@mkdir($handlerDir, 0777))
						{
							$create = @chmod($handlerDir, 0777);
						}	
					}
				}
			}
	        $thumbnail = resize($config['public'].$config['pictures'].$param.$file, $config['public'].$config['temp_dir'].'.thumbnail/'.$param.$file);
	    }
	    catch(Exception $e)
        {
        	echo '{"picture":[{"src":"ERROR '.$e->getMessage().'","file":"ERROR '.$e->getMessage().'","x":10,"y":10}]}';
        	exit;
        }
	}
	
	//edit file
	
	if($_GET['action'] == 'edit')
	{
		$param = $_POST['url'];
		$param = urldecode($param);
		list($tempDomain, $p) = split('////',$param);
		$param = $p;
		$param = str_replace('//','/',$param);
		$name = $_POST['name'];
		if($param[0] == '/')
			$param = substr($param, 1, $lenght);
		$newParam = '';
		$param2 = $param;
		while($p = strpos($param2, '/'))
		{
			$newParam .= substr($param2,0,$p).'/';
			$param2 = substr($param2,$p+1);
		}
		$lenght = strlen($newParam);
		$fileOld = pathinfo($name);
		$fileNew = pathinfo($param);
		$create = false;
		if(strtolower($fileOld['extension']) == strtolower($fileNew['extension']))
		{
			$create = @rename($config['public'].$config['pictures'].$param,$config['public'].$config['pictures'].$newParam.$name);
			@rename($config['public'].$config['temp_dir'].'.thumbnail/'.$param,$config['public'].$config['temp_dir'].'.thumbnail/' .$newParam.$name);
		}
		if($create)
				echo '{"create":"'.$newParam.$name.'", "file":"'.$name.'"}';
			else
				echo '{"create":"false"}';	
			return;
	}
	
	//show all files in directory and clining cache
	
	else if($_GET['action'] == 'list_action')
	{
		//clining cache (TEMP_dir) directory
		
		$delpath = $config['public'] . $config['temp_dir'];
		if(!is_dir($config['public'] . $config['temp_dir']))
		{
			if(@mkdir($config['public'] . $config['temp_dir'], 0777))
			{
				$create = @chmod($config['public'] . $config['temp_dir'], 0777);
			}
		}
		$handler = @opendir($delpath);
	    while ($file = @readdir($handler)) {
	    	if ($file != '.' && $file != '..')
	    	{
		        if(!is_dir( $delpath.'/'.$file ) )
		            @unlink( $delpath.'/'.$file );
	    	}
	    }
		
		$param = $_POST['param'];
		
		//if search result 		'/////' <- this is search POST
		
		if(strstr($param,'/////'))
		{
			
			list($directory, $p) = split('#_!_#',$param);		//'#_!_#' <-this is search separator
			$param = str_replace('/////','',$p);
			$ret = array();
			if (file_exists($config['public'].$config['pictures'].$directory))
			{
				dirtree($config['public'],$config['pictures'].$directory, $ret, $param, $directory);
			}
			foreach($ret as $item)
			{
				$file_parts = pathinfo($item['src']);
				$param = $file_parts['dirname'];
				$file = $file_parts['basename'];
				if (file_exists($config['public'].$config['pictures'].$param))
				{
					$handler = @opendir($config['public'].$config['pictures'].$param);
					
					$lenght = strlen($param);
					if($param[$lenght -1] != '/')
					{
						$param = $param.'/';
					}
	        		if(!is_dir($config['public'].$config['pictures'].$param.$file))
	        		{
	        			if(!strpos(strtolower($file_parts['basename']),"_thm_") && (strtolower($file_parts['extension']) == 'jpeg' || strtolower($file_parts['extension']) == 'jpg' || strtolower($file_parts['extension']) == 'png' || strtolower($file_parts['extension']) == 'gif' || strtolower($file_parts['extension']) == 'bmp'))
	        			{
	        				preg_match( "(-[\d]+x[\d]+\.)", strtolower($file_parts['basename']), $pregResult);
	        					if(!isset($pregResult[0]))
	        					{
			        				//try directory to file
			        				tryThumbnail($config, $param, $file);
	        					}
	        			}
	        		}
				}
			}
			$return = array('picture' =>$ret);
			echo json_encode($return);
		}
		
		// if print directory files (not search)
		
		else 
		{
			$pictureList = array();
			$lenght = strlen($param);
			if($lenght > 0 && $param[$lenght -1] == '/')
			{
				$param = substr($param, 0, -1);
			}
	
			if (file_exists($config['public'].$config['pictures'].$param))
			{
				$handler = @opendir($config['public'].$config['pictures'].$param);
				
				if($param != '')
					$param.='/';
				while ($file = @readdir($handler)) 
				{
	        		if ($file != '.' && $file != '..')
	        		{
	        			$file_parts = pathinfo($file);
	        			$item = array('src' => $param.$file, 'file' => $file);
	        			if(!is_dir($config['public'].$config['pictures'].$param.$file))
	        			{
	        				if(!strpos(strtolower($file_parts['basename']),"_thm_") && (strtolower($file_parts['extension']) == 'jpeg' || strtolower($file_parts['extension']) == 'jpg' || strtolower($file_parts['extension']) == 'png' || strtolower($file_parts['extension']) == 'gif' || strtolower($file_parts['extension']) == 'bmp'))
	        				{
	        					preg_match( "(-[\d]+x[\d]+\.)", strtolower($file_parts['basename']), $pregResult);
	        					if(!isset($pregResult[0]))
	        					{
		        					//try directory to file
		        					tryThumbnail($config, $param, $file);
		        					@$size = getimagesize($config['public'].$config['pictures'].$param.$file);
		        					@$item['x'] = $size[0];
		        					@$item['y'] = $size[1];
		        					array_push($pictureList, $item);
	        					}
	        				}
	        			}
	        		}
	    		}
			}
			$return = array('picture' =>$pictureList);
			echo json_encode($return);
		}
	}
	
	//this block do all function with directory tree
	
	else if($_GET['action'] == 'dir_list')	
	{
		$param="";
		@$param = $_POST['param'];
		@$type = $_POST['type'];
		$param = urldecode($param);
		$lenght = strlen($param);
		if($lenght > 0 && $param[0] == '/')
			$param = substr($param, 1, $lenght);
		$lenght = strlen($param);
		if($lenght > 0 && $param[$lenght -1] == '/')
		{
			$param = substr($param, 0, -1);
		}
		
		//add new directory
		
		if($type == 'add')
		{
			$create = false;
			
			$dir = $config['new_dir_name'];
			//find first free number 
			if (is_dir($config['public'].$config['pictures'].$param.'/'.$dir) )
			{
				$i = 0;
				do
				{
					++$i;
					$new_dir = $config['new_dir_name'].sprintf("%02d", $i);
				}
				while (is_dir($config['public'].$config['pictures'].$param.'/'.$new_dir));
				$dir = $new_dir;
			}
			//try create new dir
			if(@mkdir($config['public'] . $config['pictures'].$param.'/'.$dir, 0777))
			{
				$create = @chmod($config['public'] . $config['pictures'].$param.'/'.$dir, 0777);
			}
			if($create)
				echo '{"create":"'.$dir.'"}';
			else
				echo '{"create":"false"}';	
			return;
		}
		
		//del directory action
		
		else if($type == 'del')
		{
			if(is_dir($config['public'] .$config['pictures']. $param))
			{
				$create = delTree($config['public'] .$config['pictures']. $param,$config['temp_dir']);
				delTree($config['public'] .$config['temp_dir'].'.thumbnail/'. $param,$config['temp_dir']);
			}
			if($create)
				echo '{"create":"true"}';
			else
				echo '{"create":"false"}';	
			return;
		}
		
		//edit directory action
		
		else if($type == 'edit')
		{
			$name = $_POST['name'];
			$paramNew = split('/',$param);
			$l = count($paramNew);
			if($l > 0)
				unset($paramNew[$l-1]);
			$paramNew = join('/',$paramNew);
			$lenght = strlen($paramNew);
			if($lenght > 0 && $paramNew[$lenght -1] == '/')
			{
				$paramNew = substr($paramNew, 0, -1);
			}
			$create = @rename($config['public'] .$config['pictures']. $param,$config['public'] .$config['pictures']. $paramNew.'/'.$name);
			if(!@rename($config['public'] .$config['temp_dir'].'.thumbnail/'. $param,$config['public'] .$config['temp_dir'].'.thumbnail/'. $paramNew.'/'.$name))
				@rename($config['public'] .$config['temp_dir'].'.thumbnail/'. $param,$config['public'] .$config['temp_dir'].'.thumbnail/'. $paramNew.'/'.$name);
			if($create)
				echo '{"create":"true"}';
			else
				echo '{"create":"false"}';	
			return;
		}	
		
		//print directory tree
		
		else
		{	
			$ret = array();
			if (is_dir($config['public'].$config['pictures']))
			{
				dirtree($config['public'],$config['pictures'], $ret);
				$ret[0]['name'] = '/';
			}
			else
			{
				$ret[0]['name'] = 'Wrong URL to dir '.$config['public'].$config['pictures'];
				$ret[0]['items'] = array();
			}
			echo json_encode($ret);
		}
	}
	
	//del image
	
	else if($_GET['action'] == 'del')
	{
		$param = $_POST['url'];
		$param = urldecode($param);
		$lenght = strlen($param);
		if($param[$lenght -1] == '/')
		{
			$param = substr($param, 0, -1);
		}
		$create = @unlink($config['public'] .$config['pictures']. $param);
		@unlink($config['public'] .$config['temp_dir'].'.thumbnail/'. $param);
		
		if($create)
			echo '{"create":"true"}';
		else
			echo '{"create":"false"}';	
		return;
	}
	
	//this block is start when user add file
	//this function copy all files to temp-dir, after copy start add block
	
	else if($_GET['action'] == 'update')
	{
		$url = "";
		@$url = $_POST['url'];
		$url = split('/',$url);
		unset($url[0]);
		$url = join('/',$url);
		
		//multidata is special parameter. Is set only when user not use fancy uploader
		//multidata is counter of upload filres
		$multidata = 0;
		@$multidata = (int)$_GET['multidata'];
		$multidata--;
		$is_multi = true;
		if($multidata < 1)
		{
			$is_multi = false;
			
		}
		if($multidata < 0)
		{
			$multidata = 1;
		}
		$multi_return = array();
		
		//if user use fancy uploader multidata == 1
		for($i=1; $i<= $multidata ; $i++)
		{
			if(!$is_multi)
				$i='';			
			$error = false;
			
			$file_name = $_FILES['Filedata'.$i]['name'];
			$file_path = $config['public'] . $config['temp_dir'] . $file_name;		
			$pathinfo = pathinfo($file_path);
			// Error checking
			if (!isset($_FILES["Filedata".$i]) || !is_uploaded_file($_FILES["Filedata".$i]['tmp_name']))
			{
				var_dump($_FILES["Filedata".$i]);
				$error = 'Invalid Upload';
			}
	
			if (!$error && $_FILES['Filedata'.$i]['size'] > $config['max_file_size'] * 1024 * 1024)
			{
				$error = 'Invalid Upload max '.$config['max_file_size'].'MB';
			}
			
			if (file_exists($file_path) )
			{
				// Check, if file with the same name exists
				// If yes - add counter to the end of file name
		
				$i = 0;
				do
				{
					++$i;
					if(strlen($url) > 2 )
						$new_file_path = $pathinfo['dirname'].'/'.$url.'/'.$pathinfo['filename'].sprintf("%02d", $i).'.'.$pathinfo['extension'];
					else
						$new_file_path = $pathinfo['dirname'].'/'.$pathinfo['filename'].sprintf("%02d", $i).'.'.$pathinfo['extension'];
				}
				while (file_exists($new_file_path)==true);
				$file_path = $new_file_path;
				$pathinfo = pathinfo($file_path);
			}
			
			// Return fail
			if ($error)
			{
				$return = array('code' => 0, 'error' => $error);
			}
			else
			{
				// Move file to destination folder
				@move_uploaded_file($_FILES['Filedata'.$i]['tmp_name'], $file_path);
			
				// Return success
				$return = array('code' => 1, 'name' => $pathinfo['basename']);
	
				if (isset($size) && $size) {
					$return['width'] = $size[0];
					$return['height'] = $size[1];
					$return['mime'] = $size['mime'];
				}
			}
			if(!$is_multi)
				$i=100;
			else
				array_push($multi_return, $return);
		}
		if($is_multi)
			$return = $multi_return;
		// Send return data in JSON 
		echo json_encode($return);
	}
	
	//copy updated files to select directory
	
	else if($_GET['action'] == 'add')
	{
		try{
			$param = $_POST['url'];
			//converting url
			$param = urldecode($param);
			$lenght = strlen($param);
			if($lenght > 0 && $param[$lenght -1] == '/')
			{
				$param = substr($param, 0, -1);
			}
			if(isset($param[0]))
				$param.='/';
			
			
			$data = array();
			
			
			//copy file
			$data['name'] = trim(stripslashes(substr($_GET['file'],0,100))); // file name with extension
			$data['path'] = $param . $data['name']; // file path
		
			$new_file_path = $_GET['file'];
			
			if($param == '//////')
			{
				echo '{"error":"true", "src":"NULL", "file":"NULL"}';
				return;
			}
			if (file_exists($config['public'].$config['pictures'].$param.$_GET['file']) )
			{
				// Check, if file with the same name exists
				// If yes - add counter to the end of file name
				$pathinfo = pathinfo($_GET['file']);
				$i = 0;
				do
				{
					++$i;
					$new_file_path = $pathinfo['filename'].sprintf("%02d", $i).'.'.$pathinfo['extension'];
				}
				while (file_exists($config['public'].$config['pictures'].$param.$new_file_path) == true);
			}
			@$size = getimagesize($config['public'].$config['temp_dir'].$_GET['file']);
		    @$x = $size[0];
		    @$y = $size[1];
			@$copy = copy($config['public'].$config['temp_dir'].$_GET['file']  , $config['public'].$config['pictures'].$param.$new_file_path);
		    tryThumbnail($config, $param,$new_file_path);
	
			@unlink($config['public'].$config['temp_dir'].$_GET['file']);
			if($copy)
				echo '{"src":"'.$data['path'].'", "file":"'.$new_file_path.'", "x":"'.$x.'", "y":"'.$y.'"}';
			else
				echo '{"error":"true", "src":"'.$data['path'].'", "file":"'.$new_file_path.'"}';
		}
		catch (Exception $e)
		{
			echo '{"error":"true", "src":"'.$e->getMessage().'", "file":"'.$e->getMessage().'"}';
		}
	}
?>