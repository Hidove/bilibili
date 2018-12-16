<?php
error_reporting(0);
$网站名='哔哩哔哩 (゜-゜)つロ 干杯~-bilibili官网';
$地址='v.abcyun.ooo';
$邮箱='admin@admin.com';
$统计='';
$资源接口='http://www.zdziyuan.com/inc/s_api_zuidam3u8.php';
$资源分类='<ty id="3">综艺</ty><ty id="4">动漫</ty><ty id="5">动作片</ty><ty id="6">喜剧片</ty><ty id="7">爱情片</ty><ty id="8">科幻片</ty><ty id="9">恐怖片</ty><ty id="10">剧情片</ty><ty id="11">战争片</ty><ty id="12">国产剧</ty><ty id="13">港台剧</ty><ty id="14">日韩剧</ty><ty id="15">欧美剧</ty><ty id="16">伦理片</ty><ty id="17">美女写真</ty>';
$资源替换=array('<script>'=>'');

$展示时效='';//展示 &h=24 &h=48 &h=168 或者留空
$倒序='no';//也可以是no，一般资源站资源是倒排的，建议填yes
$解析='https://api.lv9.xyz/player/?url=';
$数据缓存='1';//单位小时
$模板='default';
$标题['list']="yk[当前分类名] - yk[网站名]";
$标题['search']="yk[搜索词]的搜索结果 - yk[网站名]";
$标题['info']="yz[name] - yk[网站名]";

$关键词['list']="yk[当前分类名],最新电影,最新电视,最新综艺,最新动漫";
$关键词['search']="yk[搜索词],最新电影,最新电视,最新综艺,最新动漫";
$关键词['info']="yz[name],最新电影,最新电视,最新综艺,最新动漫";

$描述['list']="yk[网站名]提供最新的电影、电视、综艺、动漫在线播放服务";
$描述['search']="yk[网站名]提供yk[搜索词]的在线播放服务";
$描述['info']="yk[网站名]提供yz[name]的在线播放服务";

include 'global.php';

include mb('indexs.html');