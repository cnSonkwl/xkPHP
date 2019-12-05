<?php
include 'route.php';//載入路由文件
include 'config.php';//載入配置文件
//發佈模式
//config::release();
$_page='index';//默認頁面
if(isset($_GET["p"])){
	$_page=$_GET["p"];
}
//檢測路由是否定義
if(!isset($_route)||count($_route)==0){
	echo 'route:404';
	exit;
}
if(!isset($_route[$_page])){
    echo 'page:404';
    exit;
}
//POST处理
if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(!isset($_route[$_page]["post"])){
        echo 'post:404';
        exit;
    }
    if(!is_file($_route[$_page]["post"])){
        echo 'post:404';
        exit;
    }
    config::checkPost();//post檢測
    include $_route[$_page]["post"];
    exit;//处理POST完成退出
}
//默认GET处理
config::checkGet();//get過濾
$_get=false;
if(isset($_route[$_page]["get"])){
    if(!is_file($_route[$_page]["get"])){
        echo 'get:404';
        exit;
    }
    include $_route[$_page]["get"];
	$_get=true;
}
if(isset($_route[$_page]["html"])){
    if(!is_file($_route[$_page]["html"])){
        echo 'html:404';
        exit;
    }
    $html=file_get_contents($_route[$_page]["html"]);
    if($_get){
        foreach ($res as $key => $value){
            $html=str_replace("{{".$key."}}",$value,$html);
        }
    }
    echo $html;
}
?>
