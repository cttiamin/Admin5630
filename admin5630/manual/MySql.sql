select T1 from sp_posts where ParentID = 48

UPDATE miscell_base set ParentID = 39 where ParentID=48
UPDATE miscell_base set ParentID = 48 where ParentID = 17;
UPDATE miscell_base set ParentID = 50 where ParentID = 52;
UPDATE miscell_base set ParentID = 51 where ParentID = 99;
UPDATE miscell_base set ParentID = 52 where ParentID = 47;



select * from miscell_base where ParentID = 40

select `a_title`,`class_id`,`ctime` 
from miscell_base 
where class_id in (39, 40, 41, 42, 48, 49, 51, 52)


select `a_title`,`class_id`,`ctime`,`file`,`updatetime`,`link_title`,
	`visits`,`content`, `displaytieme`
from miscell_base 
where class_id in (39, 40, 41, 42, 48, 49)
order by ctime DESC



TRUNCATE TABLE table


select * from sp_posts 
where ParentID in (5, 6, 7, 8, 9, 10)


insert into sp_posts (`T1`,`time001`,`time002`,`time003`) 
VALUES ("时间测试003", "2015-1-6 10:57:10", "2015-1-07"
    , '2015-01-06');

update sp_posts SET
time001 =   REPLACE(UpTime,'/','-')
where key_id = 1855

/****************************************************
 */
update sp_posts SET
time001 = REPLACE(UpTime,'/','-'),
time002 = REPLACE(T88,'/','-'),
time003 = REPLACE(T83,'/','-')
where 
ParentID in (48, 49, 22, 27, 17, 51, 52, 99, 45)
and CODE IS  NULL
and LENGTH(time002) > 1


/**/
select `T50` from sp_posts 
where ParentID in (2,3,4,5,6,7,8,9,10,11,12,13);


select T1 from sp_posts where term_id = 48 order by id desc;
UPDATE sp_posts set term_id = 11 where term_id=48;
/* 最新动态 : 501 条*/

select T1 from sp_posts where term_id = 49 order by id desc;
UPDATE sp_posts set term_id = 13 where term_id=49;
/* 媒体报道 : 191 条 */

select T1 from sp_posts where term_id = 22 order by id desc;
UPDATE sp_posts set term_id = 12 where term_id=22;
/* 协会要闻: 22条 */

select T1 from sp_posts where term_id = 27 order by id desc;
UPDATE sp_posts set term_id = 2 where term_id=27;
/* 友好往来: 94 条*/

select T1 from sp_posts where term_id = 17 order by id desc;
UPDATE sp_posts set term_id = 3 where term_id=17;
/* 协会概况: 9*/

select T1 from sp_posts where term_id = 51 order by id desc;
UPDATE sp_posts set term_id = 4 where term_id=51;
/*协会服务: 0*/

select T1 from sp_posts where term_id = 52 order by id desc;
UPDATE sp_posts set term_id = 7 where term_id=52;
/* 慈善公益: 6*/

select T1 from sp_posts where term_id = 99 order by id desc;
UPDATE sp_posts set term_id = 8 where term_id=99;
/* 活动剪影: 6*/

select T1 from sp_posts where term_id = 45 order by id desc;
UPDATE sp_posts set term_id = 10 where term_id=45;
/* 八方回音 0*/


select from sp_posts where T60=0
delete from sp_posts where T60=0
/* 删除不显示的文章 :375条 */


select `smeta`, `post_title`, `post_hits`, `post_content`,
`post_date`, `post_modified`, `term_id`, `display_date`
from sp_posts
where term_id in (2,3,4,5,6,7,8,9,10,11,12,13);
/*查询内容*/

update
sp_posts
set smeta = concat('{"thumb":"',smeta)
where id > 14
/*修改图片地址*/

update
sp_posts
set smeta = REPLACE(smeta,'"http:\/\/www.wecba.org\/'
,'"http:\/\/www.wecba.org\/uploadfile')
where id =17

update
sp_posts
set smeta = REPLACE(smeta,'\/'
,'\\/')
where id =17

select * FROM
sp_term_relationships as a 
INNER JOIN sp_posts as b
on a.object_id = b.id
where a.term_id = 13
and b.post_content = ''
/*媒体报道为空的内容*/

UPDATE sp_posts
set 
post_content = T50
WHERE 
term_id = 13
and post_content = ''
/**/


UPDATE
sp_posts
SET
post_keywords = post_title,
post_excerpt = post_title,
post_author = 1,
post_modified = display_date,
smeta = '{"thumb":""}'
where term_id = 13


UPDATE
sp_posts
SET
post_content = CONCAT('<a href="',post_content,'">',post_title,'</a>')
where term_id_002 = 13
and id = 663











