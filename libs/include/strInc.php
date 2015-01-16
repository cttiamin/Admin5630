<?php 

/**
 * 字符处理类库
 * */
class strInc {

    /**
     * 截取utf-8字符串.
     * @$sourcestr :字符 
     * @cutlength	:载取长度
     * @etc		:结尾字符
     */
    function cut_str($sourcestr, $cutlength = 80, $etc = '...') 
    {
        $returnstr = '';
        $i = 0;
        $n = 0.0;
        $str_length = strlen($sourcestr); //字符串的字节数
        while ( ($n<$cutlength) and ($i<$str_length) )
        {
            $temp_str = substr($sourcestr, $i, 1);
            $ascnum = ord($temp_str); //得到字符串中第$i位字符的ASCII码
            if ( $ascnum >= 252) //如果ASCII位高与252
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 6); //根据UTF-8编码规范，将6个连续的字符计为单个字符
                $i = $i + 6; //实际Byte计为6
                $n++; //字串长度计1
            }
            elseif ( $ascnum >= 248 ) //如果ASCII位高与248
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 5); //根据UTF-8编码规范，将5个连续的字符计为单个字符
                $i = $i + 5; //实际Byte计为5
                $n++; //字串长度计1
            }
            elseif ( $ascnum >= 240 ) //如果ASCII位高与240
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 4); //根据UTF-8编码规范，将4个连续的字符计为单个字符
                $i = $i + 4; //实际Byte计为4
                $n++; //字串长度计1
            }
            elseif ( $ascnum >= 224 ) //如果ASCII位高与224
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
                $i = $i + 3 ; //实际Byte计为3
                $n++; //字串长度计1
            }
            elseif ( $ascnum >= 192 ) //如果ASCII位高与192
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
                $i = $i + 2; //实际Byte计为2
                $n++; //字串长度计1
            }
            elseif ( $ascnum>=65 and $ascnum<=90 and $ascnum!=73) //如果是大写字母 I除外
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 1);
                $i = $i + 1; //实际的Byte数仍计1个
                $n++; //但考虑整体美观，大写字母计成一个高位字符
            }
            elseif ( !(array_search($ascnum, array(37, 38, 64, 109 ,119)) === FALSE) ) //%,&,@,m,w 字符按１个字符宽
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 1);
                $i = $i + 1; //实际的Byte数仍计1个
                $n++; //但考虑整体美观，这些字条计成一个高位字符
            }
            else //其他情况下，包括小写字母和半角标点符号
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 1);
                $i = $i + 1; //实际的Byte数计1个
                $n = $n + 0.5; //其余的小写字母和半角标点等与半个高位字符宽...
            }
        }
        if ( $i < $str_length )
        {
            $returnstr = $returnstr . $etc; //超过长度时在尾处加上省略号
        }
        return $returnstr;
    }


    /*
    截取gb2312中文字符函数
     @str	: 字符
     @start : 开始
     @len	: 结束
     */
    function substr_gb2312($str,$start,$len) {   
        $tmpstr="";
        $strlen=$start+$len;
        for($i=0;$i<$strlen;$i++) {
            if(ord(substr($str,$i,1))>0xa0) {
                $tmpstr.=substr($str,$i,2);
                $i++;
            } else
                $tmpstr.=substr($str,$i,1);
        }
        return $tmpstr;
    }

    /* 字符加密
       @encrypt	:加密字符
       @key		:附加密钥
     */
    function encrypt($encrypt,$key){
        //初始化向量
        $iv=mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_DES,MCRYPT_MODE_ECB),MCRYPT_RAND);
        //对信息进行加密
        $passcrypt=mcrypt_encrypt(MCRYPT_DES,$key,$encrypt,MCRYPT_MODE_ECB,$iv);
        //对加密信息进行编码
        $encode=base64_encode($passcrypt);
        return $encode;
    }

    /* 字符解密
        @dectypt:解密字符
        @key	:附加密钥
     */
    function decrypt($decrypt,$key){
        //对加密信息进行解码
        $decoded=base64_decode($decrypt);
        //初始化向量
        $iv=mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_DES,MCRYPT_MODE_ECB),MCRYPT_RAND);
        //对信息进行解密
        $decrypted=mcrypt_decrypt(MCRYPT_DES,$key,$decoded,MCRYPT_MODE_ECB,$iv);
        return $decrypted;
    }

    /*获得客户端真实的IP地址*/
    function getip()
    { 
        if(getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        {
            $ip = getenv("HTTP_CLIENT_IP"); 
        }
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")){
            $ip = getenv("HTTP_X_FORWARDED_FOR"); 
        }
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        {
            $ip = getenv("REMOTE_ADDR"); 
        }
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")){
            $ip = $_SERVER['REMOTE_ADDR']; 
        }
        else{
            $ip = "unknown"; 		
        }
        return($ip);
    }

    /**
     * 检查来路
     */
    function checkurl() 
    { 
        $_SERVER['HTTP_HOST'] = isset($_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : false;
        $_SERVER['HTTP_REFERER'] = isset ($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] :false;
        if (preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) !== preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) {
            header("Location: http://www.tjxfjt.com.cn");
            exit();
        }
    }

    /* 获取来路地址 
     * */
    function getReferer()
    { 
        $_SERVER['HTTP_HOST'] = isset($_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : false;
        $_SERVER['HTTP_REFERER'] = isset ($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] :false;
        $se = 0; 
        $url = $_SERVER["HTTP_REFERER"]; //获取完整的来路URL 
        $str = str_replace("http://", "", $url); //去掉http:// 
        $str = str_replace(WEBURL, "", $url); //去掉 localhost / www.jm088.com
        //$strdomain = explode("/", $str); // 以“/”分开成数组
        if(strpos ( $str , 'users' ))
        {
            $str = '/';
        }

        return $str; 
    } 

    /* 用于 $_GET传值 防注入 
     * 用于单个值传递
     * */
    public function inject_check($sql_str) {
        $check=preg_match('/select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i',$sql_str);     // 进行过滤
        if($check){
            echo "输入非法内容";
            echo "<script> location.href='/404.html';  </script>";
            //exit();
        }else{
            return $sql_str;
        }
    }
    /*
     * 表单form验证,
     * 遍历提交表单的每个字段, 检测是否有非法信息
     */
    public function userFormCheck($str)
    {
        if( is_array($str) )
        { 
            foreach($str as $v){
                if($v=='')$v=0;
                $check = preg_match('/select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i', $v);
                if( $check ){ return 0; exit(); }
            }
            return $str;
        }
        else
        {
            $check = preg_match('/select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i', $str);// 进行过滤
            if($check){
                return 0;
            }else{
                return $str;
            }
        }
    }


    //权限验证
/*		function user_shell($uid,$shell,$m_id){ //权限
        $sql="select * from user_shop where uid = $uid";
        $query=mysql_query($sql);
        @$us= is_array($row=mysql_fetch_array($query));
        $shell=$us ? $shell == $row[u_name] :FALSE;

            if($shell){
                    if($row[m_id]<=$m_id){
                        return $row;
                    }else{
                        echo "你的权限不足";
                        exit();
                    }

                return $row;
            }else{

                echo "<script>alert('你无权限访问'); location.href='../../index.php';</script>";
                //echo "你无权限访问<a href='../../index.php'>返回</a>";
                exit();
            }
}*/

    //登录超时
/*	function user_mktime($onlinetime){
        $new_time = mktime();
        if($new_time-$onlinetime > '3600'){
            echo "登录超时";
            echo "<script>location.href='index.php'</script>";
            session_destroy();
            //exit();
        }else{
            $_SESSION[times]=mktime();
        }
}*/

    /*
        日期处理函数
     */				
/*	function differ($regx='/(-| |:)/',$outday,$today){	//日期相减的时间,以小时	, 至顶余时	
        $idate=preg_split($regx,$outday);
        $today=preg_split($regx,$today);
        $today_array=mktime(09,$today[4],$today[5],$today[1],$today[2],$today[0]);		//早九点为限
        $idate_array=mktime($idate[3],$idate[4],$idate[5],$idate[1],$idate[2],$idate[0]);
        //$sy=round(($today_array-$idate_array)/3600/24);  //天数
        $sy=round(($idate_array-$today_array)/3600); //小时
        return $sy;
    }
 */
    //获取产品类别名称
    public function ProductClass($qu=''){
        $query=mysql_query("select * from pro_class where pc_bid=0");
        while ($row_class=mysql_fetch_array($query)) {
            $selected= $row_class[pc_id]==$qu ? "selected" : NULL;
            $str.="<option value=\"$row_class[pc_id]\" $selected>$row_class[pc_title]</option>";
            $query_son=mysql_query("select * from pro_class where pc_bid='$row_class[pc_id]'");
            while ($row_son=mysql_fetch_array($query_son)) {
                $selected= $row_son[pc_id]==$qu ? "selected" : NULL;
                $str.="<option value=\"$row_son[pc_id]\" $selected>&nbsp;&nbsp;&nbsp;┗$row_son[pc_title]</option>";
            }
        }
        return  $str;
    }

    //获取新闻类别名称
    public function ClassTitle($qu=''){
        $query=mysql_query("select * from miscell_class where b_id=0");
        while ($row_class=mysql_fetch_array($query)) {
            $selected= $row_class[class_id]==$qu ? "selected" : NULL;
            $str.="<option value=\"$row_class[class_id]\" $selected>$row_class[class_title]</option>";
            $query_son=mysql_query("select * from miscell_class where b_id='$row_class[class_id]'");
            while ($row_son=mysql_fetch_array($query_son)) {
                $selected= $row_son[class_id]==$qu ? "selected" : NULL;
                $str.="<option value=\"$row_son[class_id]\" $selected>&nbsp;&nbsp;&nbsp;┗$row_son[class_title]</option>";
            }
        }
        return  $str;
    }

}

?>
