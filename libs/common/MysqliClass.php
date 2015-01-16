
<?php
/**
 *  zjm edit mysql class 2011-09-01
 **/
class mysqliClass
{
    private $db_host; //数据库主机
	private $db_user; //数据库用户名
	private $db_pwd; //数据库用户名密码
    private $db_database; //数据库名
    private $coding;
    private $tableName;
    private $conn;  //数据库连接标识,指针;

	/*构造函数 主机名,用户，密码，数据库，连接类型，编码*/
	function __construct($host='', $user='', $pass='', $database='', $lang='') { //
		$this->db_host = DBHOST;	
		$this->db_user = DBUSER;
		$this->db_pwd = DBPASS;
		$this->db_database = DBNAME;
        $this->coding = DBCHARSET;
        $this->tableName = "pro_base";
		$this->connect();
    }

    /*数据库连接*/
	private function connect() {
        $this->conn = new mysqli($this->db_host, $this->db_user, $this->db_pwd, $this->db_database);
        $this->conn->query("SET CHARSET $this->coding");      
        if($this->conn){
            //echo "连接成功！";
        }else{
            echo "连接失败！";
        }
    }
    
	function query($sql)
	{
	 	$result = $this->mysqli->query( $sql );
		
		$rows = $array();
		while ( $row = $result->fetch_assoc() )
		{
			$rows[] = $row;
		}
		return $rows;
	}
	
    function select() { 
        $this->mysqli->query('SET CHARSET UTF8');
		$sql = "SELECT p_id,p_title FROM pro_base";
		$result = $this->mysqli
			->query ( $sql );
		$rows = array ();
		while ( $row = $result->fetch_assoc () ) {
			$rows [] = $row;
		}
		ECHO "<PRE>";
		print_r ( $rows );
    }

    static function get_vars(){ //获取方法名称
        return get_class_vars(__CLASS__);
    }
	
    /*魔术方法*/
    function __wakeup(){	//反序列化调用函数
        $this->connect();
    }
	
    function __set($k,$v){  //private
        if($trim($_SESSION['type'])=='admin'){
        $this->$k=$v;
        }else{
        die('你没权限操作！');
        }
    }
	
    function __get( $varName ) {
/*		if (trim ( $_SESSION ['utype'] ) == 'teacher') {
			return $this->$varName;
		} else {
			return "保密";
		}*/
	}

    /*关闭*/
    private function close() {
		$this->conn
            ->close();  
    }
	
    function __destruct() {    
		$this->close();
	}

}

//$aa= new JmMysql('localhost','root' ,'123456','jm088_m','UTF8');


?>


