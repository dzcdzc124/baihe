<?php  
    //三分钟
    set_time_limit(180);
    //ini_set("memory_limit", "150M");
    $www_folder = "/var/www/baihe"; 
    //$git = "/usr/bin/git"; 
      
    //在这里获取到了用户提交的内容, 以及提交者等等, 可以记录到数据库中供以后使用  
    $raw_json = file_get_contents('php://input');  
    print_r(json_decode($raw_json, true));  
      
    echo shell_exec("cd $www_folder ; whoami ; git pull 2>&1 && echo yes;");  

    
/*
    error_reporting ( E_ALL );

	$dir = '/var/www/html/Maplestory/';//该目录为git检出目录

	$handle = popen('cd '.$dir.'; whoami ; git pull 2>&1 ; yes','r');

	$read = stream_get_contents($handle);

	printf($read);
    unset($read);
	pclose($handle);
*/

    @header("Content-type: text/html; charset=utf-8");
    /********************
    1、写入内容到文件,追加内容到文件
    2、打开并读取文件内容
    ********************/
    $file  = 'git.log';//要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个
    $content = "git pull:".@date("Y-m-d H:i:s")."\r\n";

    if($f  = file_put_contents($file, $content,FILE_APPEND)){// 这个函数支持版本(PHP 5) 
        echo "写入成功。<br />";
    }else{
        echo "写入fail.<br />";
    }

?>
