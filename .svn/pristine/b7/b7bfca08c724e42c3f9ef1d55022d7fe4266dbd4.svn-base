<?php

class DownimgModel extends AppModel {

// 获取早晨检测图片
    function getSchoolDownimgList($sh,$school_id) {
        $this->setSearch($sh);
        $sql = "
				SELECT tsd.`org_img_url`,tsd.`student_id`,ts.`name` FROM `tb_student_detection` tsd 
				LEFT JOIN `tb_student` ts ON ts.`id` = tsd.`student_id`
				LEFT JOIN `tb_school` th ON th.`id` = ts.`school_id`
				WHERE th.`id` = {$school_id}
        ";
        $rs = $this->getAll($sql);
        return $rs;
    }

    function getStudentDownimgList($sh,$student_id,$school_id){
    	$this->setSearch($sh);
    	$sql = "
				SELECT * FROM (SELECT tsd.`org_img_url`,tsd.`student_id`,ts.`name` FROM `tb_student_detection` tsd 
				LEFT JOIN `tb_student` ts ON ts.`id` = tsd.`student_id`
				LEFT JOIN `tb_school` th ON th.`id` = ts.`school_id`
				WHERE th.`id` = {$school_id}) AS schoolAllImg WHERE `student_id` = {$student_id}
    	";
    	$rs = $this->getAll($sql);
    	return $rs;
    }

	function formatArray($array)
	{
		sort($array);
		$tem = "";
		$temarray = array();
		$j = 0;
		for($i=0;$i<count($array);$i++)
		{
			if($array[$i]!=$tem)
			{
				$temarray[$j] = $array[$i];
				$j++;
			}
		$tem = $array[$i];
		}
		return $temarray;

	}
// 获取学校列表
	function getSchoolList(){
		$sql = "
            SELECT * FROM tb_school
            ";
        
        return $this->getAll($sql);
	}

	/*
	*功能：php完美实现下载远程图片保存到本地
	*参数：文件url,保存文件目录,保存文件名称，使用的下载方式
	*当保存文件名称为空时则使用远程文件原来的名称
	*/
	function getImage($url,$save_dir='',$filename='',$type=true){
	    if(trim($url)==''){
			return array('file_name'=>'','save_path'=>'','error'=>1);
		}
		if(trim($save_dir)==''){
			$save_dir='./';
		}
	    if(trim($filename)==''){//保存文件名
	        $ext=strrchr($url,'.');
	        if($ext!='.gif'&&$ext!='.jpg'){
				return array('file_name'=>'','save_path'=>'','error'=>3);
			}
	        $filename=time().$ext;
	    }
		if(0!==strrpos($save_dir,'/')){
			$save_dir.='/';
		}
		//创建保存目录
		if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
			return array('file_name'=>'','save_path'=>'','error'=>5);
		}
	  //   //获取远程文件所采用的方法 
	  //   if($type){
			// $ch=curl_init();
			// $timeout=5;
			// curl_setopt($ch,CURLOPT_URL,$url);
			// curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			// curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
			// $img=curl_exec($ch);
			// curl_close($ch);
	  //   }else{
		 //    ob_start(); 
		 //    readfile($url);
		 //    $img=ob_get_contents(); 
		 //    ob_end_clean(); 
	  //   }
		$newfile = $save_dir.$filename; //必须有写入权限
		  if (file_exists($url) == false)
		  {
		   return '文件不在,无法复制';
		  }
		  $result = copy($url, $newfile);
		  if ($result == false)
		  {
		   return array('file_name'=>$filename,'save_path'=>$newfile,'error'=>0);
		  }
	    //$size=strlen($img);
	    //文件大小 
	 //    $fp2=@fopen($save_dir.$filename,'a');
	 //    fwrite($fp2,$img);
	 //    fclose($fp2);
		// unset($img,$url);
	    return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);
	}



	private static function folderToZip($folder, &$zipFile, $exclusiveLength) {
		$handle = opendir($folder);
		while (false !== $f = readdir($handle)) {
			if ($f != '.' && $f != '..') {
				$filePath = "$folder/$f";
				// Remove prefix from file path before add to zip.
				$localPath = substr($filePath, $exclusiveLength);
				if (is_file($filePath)) {
					$zipFile->addFile($filePath, $localPath);
				} elseif (is_dir($filePath)) {
					// 添加子文件夹
					$zipFile->addEmptyDir($localPath);
					self::folderToZip($filePath, $zipFile, $exclusiveLength);
				}
			}
		}
		closedir($handle);
	}

	public static function zipDir($sourcePath, $outZipPath)
	{
		$pathInfo = pathInfo($sourcePath);
		$parentPath = $pathInfo['dirname'];
		$dirName = $pathInfo['basename'];
		$sourcePath = $parentPath.'/'.$dirName;//防止传递'folder' 文件夹产生bug
		$z = new ZipArchive();
		$z -> open($outZipPath, ZIPARCHIVE::CREATE);//建立zip文件
		$z -> addEmptyDir($dirName);//建立文件夹
		self::folderToZip($sourcePath, $z, strlen("$parentPath/"));
		$z -> close();
	}






}