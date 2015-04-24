<?php
  
	class SQL{ 
		protected $address;		//数据库地址
		protected $username;	//登录用户名
		protected $password;	//登录密码
		
		protected $con=null;	//建立的连接

		protected $res;			//sql语句缓存

		protected $transaction=array("mode"=>false,"error"=>false);//事务状态
		
		//构造函数
		function __construct($address,$username,$password){ 
			$this->address=$address;
			$this->username=$username;
			$this->password=$password;
		}
		//建立连接并选择数据库
		public function open($database){ 
			if(null==$this->con){
				$this->con=mysql_connect($this->address,$this->username,$this->password);
			}
			mysql_select_db($database,$this->con);
		}
		//执行SQL语句
		public function query($sql){ 
			$rw=mysql_query($sql);
			$this->res=$rw;
			if( $this->transaction['mode'] && ! $rw)  $this->transaction['error']=true;
		}
		//获取SQL语句的结果
		public function fetch(){ 
			return mysql_fetch_array($this->res);
		}
		//关闭数据库连接
		public function close(){ 
			mysql_close($this->con);
		}
		//Innodb事务相关
		public function begin(){ 
			mysql_query("begin;");
			$this->transaction['mode']=true;
		}
		public function getError(){ 
			return $this->transaction['error'];
		}
		public function rollback(){ 
			mysql_query("rollback;");
		}
		public function commit(){ 
			mysql_query("commit");
		}
	}
?>
