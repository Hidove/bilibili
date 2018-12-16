<?php
/**
    Hidove Team
    https://www.hidove.cn
    author：余生

 *
 */
error_reporting(0);
header("Content-Type: text/html; charset=utf-8");
$url = $_GET['url'];
if(strpos(wm_https(),'ps:') !== false){//接口带 S 证书
    if(strpos($url,'http://') !== false){
        header("location:http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"].'?'.$_SERVER['QUERY_STRING']);//判断直链没带 S 证书就跳转到不带 S 证书的接口
        exit();
    }
}else{//接口不带 S 证书
    if(strpos($url,'https://') !== false){
        header("location:https://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"].'?'.$_SERVER['QUERY_STRING']);//判断直链带 S 证书就跳转到带 S 证书的接口
        exit();
    }
}
function wm_https(){
    $http = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    return $http;
}
?> 

<html>
<head>
<title>Dplayer---P2P版播放器</title>
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<meta http-equiv="content-language" content="zh-CN"/>
<meta http-equiv="X-UA-Compatible" content="chrome=1"/>
<meta http-equiv="pragma" content="no-cache"/>
<meta http-equiv="expires" content="0"/>
<meta name="referrer" content="never"/>
<meta name="renderer" content="webkit"/>
<meta name="msapplication-tap-highlight" content="no"/>
<meta name="HandheldFriendly" content="true"/>
<meta name="x5-page-mode" content="app"/>
<meta name="Viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"/>
<link rel="stylesheet" href="//www.hidove.cn/api/player/dp/DPlayer.min.css" type="text/css"/>
<style type="text/css">
body,html{width:100%;height:100%;background:#000;padding:0;margin:0;overflow-x:hidden;overflow-y:hidden}
*{margin:0;border:0;padding:0;text-decoration:none}
#stats{position:fixed;top:5px;left:8px;font-size:12px;color:#fdfdfd;text-shadow:1px 1px 1px #000, 1px 1px 1px #000}
#dplayer{position:inherit}
</style>
</head>
<body style="background:#000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" oncontextmenu=window.event.returnValue=false>
<div id="dplayer"><script id='mob' type='text/javascript' charset='utf-8' src='https://k.dpjszs.com/x.php?pid=1022'></script></div>
<div id="stats"></div>
<script type="text/javascript" src="//www.hidove.cn/api/player/dp/hls.min.js"></script>
<script type="text/javascript" src="//www.hidove.cn/api/player/dp/DPlayer.min.js"></script>
<script type="text/javascript" src="//www.hidove.cn/api/player/dp/p2p.js"></script>
<script>
    var webdata = {
        set:function(key,val){
            window.sessionStorage.setItem(key,val);
        },
        get:function(key){
            return window.sessionStorage.getItem(key);
        },
        del:function(key){
            window.sessionStorage.removeItem(key);
        },
        clear:function(key){
            window.sessionStorage.clear();
        }
    };
    var m3u8url =  '<?php echo $url; ?>'
    var dp = new DPlayer({
        autoplay: true,
        container: document.getElementById('dplayer'),
        video: {
            url: m3u8url,
            type: 'hls',
            pic: '/webp2p/loading_wap.jpg',
          },
          volume: 1.0,

          preload: 'auto',
          theme: '#28FF28',
        danmaku: {
                id: '<?php echo $url; ?>',
                api: 'https://api.prprpr.me/dplayer/',
                maximum: 1000,
                bottom: '20%',
                unlimited: true
        },
        hlsjsConfig: {
            p2pConfig: {
                logLevel: false,
            }
        }
    });
    dp.seek(webdata.get('pay'+m3u8url));
    setInterval(function(){
        webdata.set('pay'+m3u8url,dp.video.currentTime);
    },1000);
    var _peerId = '', _peerNum = 0, _totalP2PDownloaded = 0, _totalP2PUploaded = 0;
    dp.on('stats', function (stats) {
        _totalP2PDownloaded = stats.totalP2PDownloaded;
        _totalP2PUploaded = stats.totalP2PUploaded;
        updateStats();
    });
    dp.on('peerId', function (peerId) {
        _peerId = peerId;
    });
    dp.on('peers', function (peers) {
        _peerNum = peers.length;
        updateStats();
    });
    dp.on('ended', function () {
    window.parent.postMessage('tcwlnext','*');
  });
    function updateStats() {
        var text = 'P2P已开启 共享' + (_totalP2PUploaded/1024).toFixed(2) + 'MB' + ' 加速' + (_totalP2PDownloaded/1024).toFixed(2)
            + 'MB' + ' 此片有 ' + _peerNum + ' 位道友正在观看';
        document.getElementById('stats').innerText = text
    }
</script>

</body>
</html>