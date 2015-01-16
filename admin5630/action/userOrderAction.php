<?php
/** Lastchange : 2012-02-19
 *	Maintainer : 
   订单管理
 **/

class userOrderAction extends Action
{
    protected $db;
    public $str;
    public $class_arr;
    protected $table;

    function __construct()
    {
		$this->db = new mysqlDb();
		$this->str = '';
		$this->class_arr = '';
		$this->table = 'user_order';
    }

    /*总条数*/
    function userOrderSum(){
		$sql = "select oid from {$this->table}";
		$query = mysql_query($sql);
		$rows = mysql_num_rows($query);
		$this->db->free();
        return  $rows; 
    }    
	//完整内容 	 	 	
    /*列表 */
    function userOrderArr( $in=0, $to=20 ){
        $query = $this->db->select("`{$this->table}`",'`oid`,`obian`,`pid`, `money`, `time`, `name`, `state`, `type`, `zbian`', " 1=1 order by `time` desc limit {$in},{$to}");
        $rows = $this->db->num_rows();
        if($rows > 0){
            while( $row = $this->db->fetch_array( $query ) )
			{
                $this->arcArr[] = array($row['oid'], $row['obian'], $row['pid'],$row['money'],$row['time'],$row['name'],$row['state'], $row['type'], $row['zbian']);  
            }
			$this->db->free();	
			return $this->arcArr;
        }else{
			return false;
        }       
    }
	/* 订单单个查询 */
	function userOrderAll ($id)
	{
		$query = $this->db->select("`{$this->table}`",'*', "`oid`=".$id);
		$rs = $this->db->fetch_array($query);
		return $rs;
	}
	function userOrderUpdate($arr)
	{	
    	$arr['eoid'] = isset( $arr['eoid'] ) == 0 ? $arr['eoid']='' :  $arr['eoid'];        //订单编号
		$arr['emoney'] = isset( $arr['emoney'] ) == 0 ? $arr['emoney']='' :  $arr['emoney']; //金额
		$arr['epostage'] = isset($arr['epostage']) == 0 ? $arr['epostage']='' :  $arr['epostage'];//邮费
		$arr['ename'] = isset($arr['ename']) == 0 ? $arr['ename']='' :  $arr['ename'];      //收货人姓名
		$arr['eaddress'] = isset($arr['eaddress']) == 0 ? $arr['eaddress']='' :  $arr['eaddress'];//地址
		$arr['etel'] = isset($arr['etel']) == 0 ? $arr['etel']='' :  $arr['etel'];          //手机
		$arr['ephone'] = isset($arr['ephone']) == 0 ? $arr['ephone']='' :  $arr['ephone'];  //固定电话
		$arr['email'] = isset($arr['email']) == 0 ? $arr['email']='' :  $arr['email'];      //邮箱
		$arr['ezip'] = isset($arr['ezip']) == 0 ? $arr['ezip']='' :  $arr['ezip'];          //邮编
		$arr['type'] = isset($arr['type']) == 0 ? $arr['type']='' :  $arr['type'];          //支付方式
		$arr['econtent'] = isset($arr['econtent']) == 0 ? $arr['econtent']='' :  $arr['econtent'];//备注
		$arr['estate'] = isset($arr['estate']) == 0 ? $arr['estate']='' :  $arr['estate'];  //状态
		$arr['etype'] = isset($arr['etype']) == 0 ? $arr['etype']='' :  $arr['etype'];      //支付方式
		$arr['epid'] = isset($arr['epid']) == 0 ? $arr['epid']='' :  $arr['epid'];          //商品
		
        //$modCon = "`pid`='".$arr['epid']."', `money`={$arr['emoney']}, `state`={$arr['estate']}, `name`='".$arr['ename']."', `address`='".$arr['eaddress']."', `tel`='".$arr['etel']."', `phone`='".$arr['ephone']."', `mail`='".$arr['email']."', `type`='".$arr['etype']."', `postage`={$arr['epostage']}, `content`='".$arr['econtent']."'  ";
        $modCon = " `money`={$arr['emoney']}, `state`={$arr['estate']}, `name`='".$arr['ename']."', `address`='".$arr['eaddress']."', `tel`='".$arr['etel']."', `phone`='".$arr['ephone']."', `mail`='".$arr['email']."', `type`='".$arr['etype']."', `postage`={$arr['epostage']}, `content`='".$arr['econtent']."'  ";
        
        $condition = '`oid` ='.$arr['eoid'];

		$query = $this->db->update( $this->table, $modCon, $condition );
		return 1;
	}


    function __toString(){}
    function __isset($var){ }
    function __unset($c){
        unset ($this->$c); 
    }
    function __destruct() {

    }

}


?>

