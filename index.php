<?php
include 'route.php';//載入路由文件
include 'config.php';//載入配置文件
//發佈模式
//config::release();
$_page='index';//默認頁面
if(isset($_GET["p"])){
	$_page=$_GET["p"];
}
//檢測如有是否定義
if(!isset($_route)||count($_route)==0){
	echo 'route:404';
	exit;
}
//檢測路由是否存在
if(isset($_route[$_page])){
	//檢測是否為post
	if($_SERVER["REQUEST_METHOD"]=="POST"){
		//POST
		if(!isset($_route[$_page]["post"])){
			echo 'post:404';
			exit;
		}
		config::checkPost();//post檢測
		include $_route[$_page]["post"];
	}else{
		//GET
		config::checkGet();//get過濾
		//get检测
		$_get=false;
		if(isset($_route[$_page]["get"])){
			if(is_file($_route[$_page]["get"])){
				include $_route[$_page]["get"];
				$_get=true;
			}else{
				echo 'get:404';
				exit;
			}
		}
		//html检测
		if(isset($_route[$_page]["html"])){
			if(is_file($_route[$_page]["html"])){
				$html=file_get_contents($_route[$_page]["html"]);
				if($_get){
					foreach ($res as $key => $value){
						$html=str_replace("{{".$key."}}",$value,$html);
					}
				}
				echo $html;
			}else{
				echo 'html:404';
				exit;
			}
		}
	}
}else{
	echo 'route:404';
}
?>
