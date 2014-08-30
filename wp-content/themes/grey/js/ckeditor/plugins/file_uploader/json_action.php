<?php

	$config = parse_ini_file("ckeplugin.ini");	
	
	@ini_set('memory_limit', $config['max_file_size']);
	@ini_set('post_max_size', $config['max_file_size']);
	@ini_set('upload_max_filesize', $config['max_file_size']);
	

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
	
	function delTree($dir) 
	{
	    $handler = @opendir($dir);
	    while ($file = @readdir($handler)) {
	    	if ($file != '.' && $file != '..')
	    	{
				if($dir.'/' != $config['temp_dir'] && $file[0] != '.')
				{
		        	if( is_dir( $dir.'/'.$file ) )
		            	delTree( $dir.'/'.$file );
		        	else
		            	@unlink( $dir.'/'.$file );
				}
	    	}
	    }
	  
	    	$ret = @rmdir( $dir );
	  return $ret;
	}
	
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
	        			if(!is_dir($param.$file)) //if not dir and is picture file.
	        			{
        					$uri = $dir.'/'.$f.'/'.$file;
							$uri = str_replace("//", "/", $uri);
        				//	@$size = getimagesize($uri);
        					@$item['x'] =256;// $size[0];
        					@$item['y'] =256;// $size[1];
        					array_push($ret, $item);
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
		}
		if($create)
				echo '{"create":"'.$newParam.$name.'","file":"'.$name.'"}';
			else
				echo '{"create":"false"}';	
			return;
	}
	else if($_GET['action'] == 'list_action')
	{
		$delpath = $config['public'] . $config['temp_dir'];
		if(!is_dir($config['public'] . $config['temp_dir']))
		{
			if(@mkdir($config['public'] . $config['temp_dir'], 0777))
			{
				$create = @chmod($config['public'] . $config['temp_dir'], 0777);
			}
		}
		$handler = opendir($delpath);
	    while ($file = readdir($handler)) {
	    	if ($file != '.' && $file != '..')
	    	{
		        if(!is_dir( $delpath.'/'.$file ) )
		            @unlink( $delpath.'/'.$file );
	    	}
	    }
		
		$param = $_POST['param'];
		//if search result
		if(strstr($param,'/////'))
		{
			
			list($directory, $p) = split('#_!_#',$param);
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
				}
			}
			$return = array('picture' =>$ret);
			echo json_encode($return);
		}
		else // if print directory file
		{
			$pictureList = array();
			$lenght = strlen($param);
			if($param[$lenght -1] == '/')
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
        					//@$size = getimagesize($config['public'].$config['pictures'].$param.$file);
        					@$item['x'] = 256;//$size[0];
        					@$item['y'] = 256;//$size[1];
        					array_push($pictureList, $item);
	        			}
	        		}
	    		}
			}
			$return = array('picture' =>$pictureList);
			echo json_encode($return);
		}
	}
	else if($_GET['action'] == 'dir_list')
	{
		$param = $_POST['param'];
		$type = $_POST['type'];
		$param = urldecode($param);
		$lenght = strlen($param);
		if($param[0] == '/')
			$param = substr($param, 1, $lenght);
		$lenght = strlen($param);
		if($param[$lenght -1] == '/')
		{
			$param = substr($param, 0, -1);
		}
		
		//add new dir
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
				$create = delTree($config['public'] .$config['pictures']. $param);
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
			if($paramNew[$lenght -1] == '/')
			{
				$paramNew = substr($paramNew, 0, -1);
			}
			$create = @rename($config['public'] .$config['pictures']. $param,$config['public'] .$config['pictures']. $paramNew.'/'.$name);
			if($create)
				echo '{"create":"true"}';
			else
				echo '{"create":"false"}';	
			return;
		}	
		//if print all directory list		
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
		
		if($create)
			echo '{"create":"true"}';
		else
			echo '{"create":"false"}';	
		return;
	}
	else if($_GET['action'] == 'update')
	{
		$url = $_POST['url'];
		$url = split('/',$url);
		unset($url[0]);
		$url = join('/',$url);
		$multidata = (int)$_GET['multidata'];
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
	
				if ($size) {
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
	else if($_GET['action'] == 'add')
	{
		$param = $_POST['url'];
		//converting url
		$param = urldecode($param);
		$lenght = strlen($param);
		if($param[$lenght -1] == '/')
		{
			$param = substr($param, 0, -1);
		}
		if($param[0] != null)
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
		$copy = @copy($config['public'].$config['temp_dir'].$_GET['file']  , $config['public'].$config['pictures'].$param.$new_file_path);

		@unlink($config['public'].$config['temp_dir'].$_GET['file']);
		if($copy)
			echo '{"src":"'.$data['path'].'", "file":"'.$new_file_path.'", "x":"'.$x.'", "y":"'.$y.'"}';
		else
			echo '{"error":"true", "src":"'.$data['path'].'", "file":"'.$new_file_path.'"}';
	}
?>