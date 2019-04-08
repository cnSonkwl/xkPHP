<?php
class config{
	static $user='';//数据库用户
	static $pwd='';//数据库密码
	static $PDO="mysql:host=localhost;dbname=dbname";//PDO
	//获得PDO
	static public function getPDO(){
		$db=new PDO(self::$PDO,self::$user,self::$pwd);
		return $db;
	}
  //事件处理
	static public function transaction($sqls){
		$sqls=(array)$sqls;
		try
		{
			$db=new PDO(self::$PDO,self::$user,self::$pwd);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$db->beginTransaction();
			foreach($sqls as $sql)
			{
				$db->exec($sql);
			}
			$db->commit();
			return true;
		}
		catch(Exception $e)
		{
			$db->rollBack();
			echo "Failed: " . $e->getMessage()."<br>";
			return false;
		}
	}
	//发布模式，默认调试模式
	static public function release(){
		error_reporting(E_ALL);
        ini_set('display_errors','Off');
        ini_set('log_errors', 'On');
        ini_set('error_log','logs/error.log');
  }
  //内部提交驗證
	static public function checkPost(){
		//是否是POST
		if($_SERVER["REQUEST_METHOD"]=="POST"){
			//是否有上级提交页面
			if(!isset($_SERVER['HTTP_REFERER'])){
				echo 'post not pripage';
				exit;
			}
			$postPage=$_SERVER['HTTP_REFERER'];
			$server=$_SERVER['SERVER_NAME'];
			$tmp=explode("/",$postPage);
			//print_r($tmp);
			$tmp=$tmp[2];
			if($tmp==$server){
				return true;
			}else{
				echo 'post not validate';
				exit;
			}
		}
	}
	//GET过滤
	static public function checkGet(){
		foreach($_GET as $key=>$value){
			//echo $value;
			$regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\=|\\\|\|/";
			$str=preg_replace($regex,"",$value);
			if(strlen($str)!=strlen($value))
			{
				//header("location:404.html");
				echo 'get value error';
				exit;
			}
		}
	}
}
?>
