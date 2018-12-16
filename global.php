<?php
/*
哔哩哔哩自动采集影视

author 余生

blog.hidove.cn

超精简两个PHP文件

留一个注释，谢谢

*/
//处理资源分类
$资源分类=strtr($资源分类,array('\''=>'"','<ty id="'=>',{"分类号":"','">'=>'","分类名":"','</ty>'=>'"}'));
$资源分类='[{"分类号":"","分类名":"最近更新"}'.$资源分类.']';$分类=json_decode($资源分类,TRUE);

$get=$_GET;if($get[page]<1){$get[page]=1;}
if(!empty($get[info])){$sort='info';$get[id]=$get[info];}elseif(!empty($get[key])){$sort='search';$get[id]=urldecode($get[key]);}else{$sort='list';$get[id]=$get['sort'];}
mkdir('./data/');mkdir('./data/html/');mkdir('./data/list/');mkdir('./data/info/');mkdir('./data/search/');mkdir('./data/dat/');

function curlgets($url){
$ip_long = array(
            array('607649792', '608174079'), //36.56.0.0-36.63.255.255
            array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
            array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
            array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
            array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
            array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
            array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
            array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
            array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
            array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255
        );
        $rand_key = mt_rand(0, 9);
$ips= long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));
global $head;
$head[]='X-forwarded-for:'.$ips; 
$head[]='User-Agent:'.$_SERVER['HTTP_USER_AGENT'];
$head[]='Accept-Encoding:';
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_ENCODING, "gzip");
$f = curl_exec($ch); 
curl_close($ch);
return $f;}
function 数据缓存($sort,$id,$page){
global $数据缓存;
if(!file_exists('./data/'.$sort.'/'.$id.'-'.$page.'.txt')){$gxpd='yes';}
if($_POST['sjgx']=="yes" and time()-filemtime('./data/'.$sort.'/'.$id.'-'.$page.'.txt') > $数据缓存*60*60){$gxpd='yes';}
if($gxpd=='yes'){
    global $资源接口;global $资源替换;global $展示时效;
if($sort=='info'){$url=$资源接口.'?ac=videolist&ids='.$id;}elseif($sort=='search'){$url=$资源接口.'?ac=list&wd='.$id.'&pg='.$page;}else{
    $pagz=file_get_contents('./data/dat/'.$id.'-pagz.txt');
    if($pagz<1){$pagz=curlgets($资源接口.'?ac=videolist&&t='.$id.'&pg='.$page.$展示时效);
    $pagz=explode('pagecount="',$pagz);$pagz=explode('"',$pagz[1]);
    $pagz=$pagz[0];file_put_contents('./data/dat/'.$id.'-pagz.txt',$pagz);}
    global $倒序; if($倒序=='yes'){$page=$pagz-$page+1;}
    $url=$资源接口.'?ac=videolist&&t='.$id.'&pg='.$page;$sort='list';}
$数据=curlgets($url.$展示时效);
$数据=preg_replace(array('#<script(.*?)</script>#i','#<span([^>]*?)>#i','#</span>#i','#<p ([^>]*?)>#i','#<p>#i','#</p>#i'),'',$数据);
$数据=strtr($数据,$资源替换);
$数据=json_encode(simplexml_load_string($数据,'SimpleXMLElement',LIBXML_NOCDATA),320);
$数据=preg_replace('#,\{"@attributes":\{"flag":"([^"]*?)"\}\}#','',$数据);
$数据=preg_replace('#\{"@attributes":\{"flag":"([^"]*?)"\}\},#','',$数据);
$数据=str_replace('@attributes','attributes',$数据);
$数据=str_replace('" "','""',$数据);
$数据=preg_replace('#,"([^"]*?)":\{"0":""\}#','',$数据);
$数据=preg_replace('#,"([^"]*?)":\{\}#','',$数据);
if(strlen($数据)>50){file_put_contents('./data/'.$sort.'/'.$id.'-'.$page.'.txt',$数据);
if($sort=='list'){$过度=json_decode($数据,TRUE);$过度=$过度['list'][video];
for($i=0;$i<count($过度);$i++){$过度2['list'][video]=$过度[$i];
file_put_contents('./data/info/'.$过度[$i][id].'-1.txt',json_encode($过度2,320));}
}
}
}
if($_POST['sjgx']=="yes"){exit('var doc="数据更新";');}
return json_decode(file_get_contents('./data/'.$sort.'/'.$id.'-'.$page.'.txt'),TRUE);
}
function mb($str){global $模板;$str='./moban/'.$模板.'/'.$str;
if(!file_exists('./data/html/'.md5_file($str))){
$fcon=file_get_contents($str);
$fcon=preg_replace('#yz\[([^\]]*?)\]\{(.*?)\}#is','<?php $ykss=$yks;unset($yks);foreach(\$$1 as $yks){?>$2<?php }$yks=$ykss;?>',$fcon);
$fcon=preg_replace('#yu\(([^\)]*?)\)#','<?php echo yu($1);?>',$fcon);
$fcon=preg_replace('#yu\((.*?)yz\[([^\]]*?)\](.*?)\)#','yu($1$yks[$2]$3)',$fcon);
$fcon=preg_replace('#yu\((.*?)yk\[([^\]]*?)\](.*?)\)#','yu($1\$$2$3)',$fcon);
$fcon=preg_replace('#yz\[([^\]]*?)\]#','<?php echo $yks[$1];?>',$fcon);
$fcon=preg_replace('#yk\[([^\]]*?)\]#','<?php echo \$$1;?>',$fcon);
//echo $fcon;exit;
file_put_contents('./data/html/'.md5_file($str),$fcon);}
return './data/html/'.md5_file($str);
}
function yu($sort,$id=false,$page=false){
if($sort=='list'){$re='?';
if(!empty($id)){$re.='&sort='.$id;}
if(!empty($page)){$re.='&page='.$page;}
return str_replace('?&','?',$re);
}
elseif($sort=='search'){$re='?';
if(!empty($id)){$re.='&key='.$id;}
if(!empty($page)){$re.='&page='.$page;}
return str_replace('?&','?',$re);
}
elseif($sort=='info'){return '?info='.$id;}
}

$数据s=数据缓存($sort,$get[id],$get[page]);$数据=$数据s['list'][video];
if($sort=='list'){
    for($i=0;$i<count($分类);$i++){if($分类[$i][分类号]==$get[id]){$当前分类名=$分类[$i][分类名];$当前分类号=$分类[$i][分类号];break;}}
    if(empty($当前分类名)){$当前分类名='最近更新';$当前分类号='0';}
    $上一页=$get[page]-1;$下一页=$get[page]+1;$尾页=$数据s['list'][attributes][pagecount]-1;
    if($上一页<=1){$上一页=1;}if($下一页>=$尾页){$下一页=$尾页;}
    $首页=yu('list',$get[id]);$上一页=yu('list',$get[id],$上一页);$下一页=yu('list',$get[id],$下一页);$尾页=yu('list',$get[id],$尾页);
}
elseif($sort=='search'){
    $搜索词=$get[id];if(empty($数据[0])){$数据ss=$数据;unset($数据);$数据[0]=$数据ss;}
    $上一页=$get[page]-1;$下一页=$get[page]+1;$尾页=$数据s['list'][attributes][pagecount];
    if($上一页<=1){$上一页=1;}if($下一页>=$尾页){$下一页=$尾页;}
    $首页=yu('search',$get[id]);$上一页=yu('search',$get[id],$上一页);$下一页=yu('search',$get[id],$下一页);$尾页=yu('search',$get[id],$尾页);

}
elseif($sort=='info'){$yks=$数据;$yks[dl]=json_encode($yks[dl],320);}//print_r($yks);exit;

$标题=$标题[$sort];
$标题='$标题=\''.str_replace("'","\\'",$标题).'\';';
$标题=preg_replace('#yk\[([^\]]*?)\]#',"'.\$$1.'",$标题);
$标题=preg_replace('#yz\[([^\]]*?)\]#',"'.\$yks[$1].'",$标题);
eval($标题);

$关键词=$关键词[$sort];
$关键词='$关键词=\''.str_replace("'","\\'",$关键词).'\';';
$关键词=preg_replace('#yk\[([^\]]*?)\]#',"'.\$$1.'",$关键词);
$关键词=preg_replace('#yz\[([^\]]*?)\]#',"'.\$yks[$1].'",$关键词);
eval($关键词);

$描述=$描述[$sort];
$描述='$描述=\''.str_replace("'","\\'",$描述).'\';';
$描述=preg_replace('#yk\[([^\]]*?)\]#',"'.\$$1.'",$描述);
$描述=preg_replace('#yz\[([^\]]*?)\]#',"'.\$yks[$1].'",$描述);
eval($描述);
//print_r($资源分类);
//print_r($数据);exit;