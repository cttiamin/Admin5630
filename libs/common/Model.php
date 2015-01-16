<?php
/**
 * 20141024, Friday.
 * 保存数据库表名, 以及每个字段名子.
 */
class Model
{
    protected $db_ad_session;
    protected $db_ad_user;
    protected $db_config_dept;
    protected $db_miscell_class;
    protected $db_miscell_base;
    protected $db_miscell_content;
    protected $db_tag_keyword;
    protected $db_tag_tmp;

	function __construct ()
    {
        $this->db_ad_session = 'ad_session';
        $this->db_ad_user = 'ad_user';
        $this->db_config_dept = 'db_config_dept';
        $this->db_miscell_class = 'db_miscell_class';
        $this->db_miscell_base = 'db_miscell_base';
        $this->db_miscell_content = 'db_miscell_content'; 
        $this->db_tag_keyword = 'db_tag_keyword';
        $this->db_tag_tmp = 'db_tag_keyword';
    }

	public function test()
	{
	}
}
?>
