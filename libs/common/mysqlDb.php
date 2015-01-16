<?php
class mysqlDb {
	private $db_host; //数据库主机
	private $db_user; //数据库用户名
	private $db_pwd; //数据库用户名密码
	private $db_database; //数据库名
	private $conn; //数据库连接标识;
	private $result; //执行query命令的结果资源标识
	private $sql; //sql执行语句
	private $row; //返回的条目数
	private $coding; //数据库编码，GBK,UTF8,gb2312

	/*构造函数 主机名,用户，密码，数据库，连接类型，编码*/
	function __construct() { //
		$this->db_host = DBHOST;
		$this->db_user = DBUSER;
		$this->db_pwd = DBPASS;
		$this->db_database = DBNAME;
		$this->conn = '';
		$this->coding = DBCHARSET;
        $this->connect();
	}

	/*数据库连接*/
	private function connect() {
		if ($this->conn == "pconn") {
			//永久链接
			$this->conn = mysql_pconnect($this->db_host, $this->db_user, $this->db_pwd);
		} else {
			//即使链接
			$this->conn = mysql_connect($this->db_host, $this->db_user, $this->db_pwd);
		}
		if (!mysql_select_db($this->db_database, $this->conn)) {		
				echo "数据库不可用!";			
		}
        mysql_query("SET NAMES $this->coding");
	}

	/*数据库执行语句，可执行查询添加修改删除等任何sql语句*/
	public function query($sql) {
		if ($sql == "") {
			print_r("SQL语句错误");
		}
		$this->sql = $sql;
		$result = mysql_query($this->sql, $this->conn);
		if (!$result) {
			print_r("SQL执行Query错误：".$this->sql);
		} else {
			$this->result = $result;
        }
        
        return $this->result;
	}


	/*查询数据库下所有的表*/
	public function show_tables($database_name) {
		$this->query("show tables");
		echo "现有数据库：" . $amount = $this->db_num_rows($rs);
		echo "<br />";
		$i = 1;
		while ($row = $this->fetch_array($rs)) {
			$columnName = "Tables_in_" . $database_name;
			echo "$i $row[$columnName]";
			echo "<br />";
			$i++;
		}
	}

	//简化查询select
	public function findall($table) {
		$this->query("SELECT * FROM $table");
	}

	//查询select
	public function select($table, $columnName = "*", $condition = '', $debug = '') {
		$condition = $condition ? ' Where ' . $condition : NULL;
		if ($debug) {
			echo "SELECT $columnName FROM $table $condition";
		} else {
			$this->query("SELECT $columnName FROM $table $condition");
		}
	}

	//简化删除del
	public function delete($table, $condition) {
		if ($this->query("DELETE FROM $table WHERE $condition")) {
		}
	}

	//简化插入insert
	public function insert($table, $columnName, $value) {
		$this->query("INSERT INTO $table ($columnName) VALUES ($value)");
	}

	//简化修改update
	public function update($table, $mod_content, $condition ) {	
        $this->query("UPDATE $table SET $mod_content WHERE $condition");
    }

	/*取得上一步 INSERT 操作产生的 ID*/
	public function insert_id() {
		return mysql_insert_id();
	}

	//指向确定的一条数据记录
	public function db_data_seek($id) {
		if ($id > 0) {
			$id = $id -1;
		}
		if (!@ mysql_data_seek($this->result, $id)) {
            
            echo "SQL语句有误db_data_seek";
		}
		return $this->result;
    }

    function fetch_array() { 
       if ($this->result == null) {
        echo "SQL语句有误或对象已释放.fetch_array";
		}else{
        return mysql_fetch_array($this->result);
        }
    }

	// 根据select查询结果计算结果集条数
	public function num_rows() {
		if ($this->result == null) {
        echo "SQL语句有误db_num_rows";
		} else {
            return mysql_num_rows($this->result);
            //$this->free();
		}
	}

	// 根据insert,update,delete执行结果取得影响行数
	public function db_affected_rows() {
		return mysql_affected_rows();
	}

	//查询字段数量
	public function num_fields($table_name) {
		//return mysql_num_fields($this->result);
		$this->query("select * from $table_name");
		echo "<br />";
		echo "字段数：" . $total = mysql_num_fields($this->result);
		echo "<pre>";
		for ($i = 0; $i < $total; $i++) {
			print_r(mysql_fetch_field($this->result, $i));
		}
		echo "</pre>";
		echo "<br />";
    }

    //清空私有属性
    public function clear($variable)
    {
        unset($this->$variable);
        return true;
    }

    function __toString(){}
	//释放结果集
	public function free() {
		@ mysql_free_result($this->result);
    }
    function __unset($c){
        unset ($this->$c);
    }

	function __destruct() {
		if (!empty ($this->result)) {
			$this->free();
		}
		@ mysql_close($this->conn);
	} 

}

?>
