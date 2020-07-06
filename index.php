<?php
require('common.php');

$u    = preg_split("/\?/", $_SERVER['REQUEST_URI']);
if (count($u) > 1) {
  $identifier = $u[1];
} else {
  $identifier = '';
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<link rel="stylesheet" href="./common.css" media="screen"/>
<link rel="stylesheet" href="./small.css" media="screen and (max-width:960px)"/>
<link rel="stylesheet" href="./large.css" media="screen and (min-width:960px)"/>
<script type="text/javascript" src="main.js"></script>
</head>
<html>
<?php
if (isset($_POST['mode'])) {
 switch($_POST['mode']) {
   case 'add':
     add($_POST['name'], $_POST['title'], $_POST['thx'],  $_POST['kansou']);
     break;
   case 'vote':
     vote($_POST['sakuhinId'], $_POST['kansou']);
     break;
 }
 exit();
} else if ($identifier == '') {
 toppage();
} else {
 $access = typeCheck($identifier);
 switch($access[1]) {
   case 0:
     root($access[0]);
     break;
   case 1:
     answer($access[0]);
     break;
   case 2:
     result($access[0]);
     break;
 }
}

function typeCheck($identifier) {
    $coid = connOpen();
    $sql = "select sakuhin_id, type ";
	$sql.= "from url ";
	$sql.= "where uri_key = '".$identifier."'; ";
    if ($result = mysqli_query($coid, $sql)) {
        $ret = mysqli_fetch_row($result);
        mysqli_free_result($result);
    }
    mysqli_close($coid);
    return $ret;
}

function root($sakuhinId) {
    $coid = connOpen();
    $sql = "select uri_key, type ";
	$sql.= "from url ";
	$sql.= "where sakuhin_id = '".$sakuhinId."'; ";
    if ($result = mysqli_query($coid, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
          switch($row[1]) {
          case 0:
             $rootPage = $row[0];
             break;
          case 1:
             $answerPage = $row[0];
             break;
          case 2:
             $resultPage = $row[0];
             break;
          }
        }
        mysqli_free_result($result);
    }
    mysqli_close($coid);

$svr = "http://" . $_SERVER['HTTP_HOST'] . preg_split("/\?/", $_SERVER['REQUEST_URI'])[0];
$qrCode = $svr . "?" . $answerPage;

	echo <<<EOD
<div align="center">
<div class="main" align="center">
<table align="center">
<tr><th>このページ</th></tr>
<tr><td>
ここをブックマークしておいてください。<br/>
ここから離れると、ブックマーク以外に戻ってくる方法はありません。
</td></tr>
<tr><td><a href="$svr?$rootPage">$svr?$rootPage</a></td></tr>
<tr><td><hr/></td></tr>
<tr><th>感想入力ページ</th></tr>
<tr><td>このURLかQRコードを告知して感想を選択してもらってください。</td></tr>
<tr><td>
<a href="$svr?$answerPage">$svr?$answerPage</a> <br/>
<img src="./qrcode.php?uri=$qrCode" /><br/>
</td>
</tr>

<tr><td><hr/></td></tr>

<tr><th>感想確認ページ</th></tr>
<tr><td>ここで感想の投票結果が確認できます。</td></tr>
<tr><td><a href="$svr?$resultPage">$svr?$resultPage</a></td></tr>

</table>
</div>
</div>
EOD;
}

function answer($sakuhinId) {
    $coid = connOpen();
    $sql = "select sakuhin_id, sakuhin_title, sakusha_name ";
	$sql.= "from sakuhin ";
	$sql.= "where sakuhin_id = '".$sakuhinId."'; ";
    if ($result = mysqli_query($coid, $sql)) {
        $row = mysqli_fetch_row($result);
        $sakuhinTitle = $row[1];
        $sakushaName  = $row[2];
        mysqli_free_result($result);
    }

	echo <<<EOD
<div align="center">
<div class="head">
 <div class="pagetitle" style="display: inline; float:left;">感想送信</div>
</div>
</div>

<div align="center">
<div class="main" align="center">
<form action="./" method="post">
<input type="hidden" name="mode" value="vote" />
<input type="hidden" name="sakuhinId" value="$sakuhinId" />
<table align="center" class="wide">
<tr>
 <th width="15%">作者</th><td>$sakushaName</td>
</tr>
<tr>
 <th width="15%">作品</th><td>$sakuhinTitle</td>
</tr>
EOD;

    $sql = "select kansou_id, kansou_text ";
	$sql.= "from kansou ";
	$sql.= "where sakuhin_id = '".$sakuhinId."'; ";
    if ($result = mysqli_query($coid, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
	echo <<<EOD
<tr>
 <th width="10%"><input type="checkbox" name="kansou[]", value="$row[0]" /></th><th>$row[1]</th>
</tr>
EOD;
          
        }
        mysqli_free_result($result);
    }
    mysqli_close($coid);
	echo <<<EOD
<tr><th colspan="2" style="text-align:center;"><input type="submit" value="感想を送る" /></th></tr>
</table>
</form>
</div>
</div>
EOD;

}

function result($sakuhinId) {

    $coid = connOpen();
    $sql = "select sakuhin_id, sakuhin_title, sakusha_name ";
	$sql.= "from sakuhin ";
	$sql.= "where sakuhin_id = '".$sakuhinId."'; ";
    if ($result = mysqli_query($coid, $sql)) {
        $row = mysqli_fetch_row($result);
        $sakuhinTitle = $row[1];
        $sakushaName  = $row[2];
        mysqli_free_result($result);
    }

    $sql = "select uri_key ";
	$sql.= "from url ";
	$sql.= "where sakuhin_id = '".$sakuhinId."' ";
	$sql.= "and   type = '0' ";
    if ($result = mysqli_query($coid, $sql)) {
        $row = mysqli_fetch_row($result);
        $uriKey = $row[0];
        mysqli_free_result($result);
    }
    $rootPage = "http://" . $_SERVER['HTTP_HOST'] . preg_split("/\?/", $_SERVER['REQUEST_URI'])[0] . "?" . $uriKey;

	echo <<<EOD
<div align="center">
<div class="head">
 <div class="pagetitle" style="display: inline; float:left;">投票結果</div>
 <div class="action"><a href="$rootPage">戻る</a></div>
</div>
</div>

<div align="center">
<div class="main" align="center">
<table align="center" class="wide">
<tr>
 <th width="15%">作者</th><td>$sakushaName</td>
</tr>
<tr>
 <th width="15%">作品</th><td>$sakuhinTitle</td>
</tr>
EOD;

    $sql = "select kansou_text, vote_num ";
	$sql.= "from kansou ";
	$sql.= "where sakuhin_id = '".$sakuhinId."' ";
	$sql.= "order by vote_num desc ";
	$max = 0;
    if ($result = mysqli_query($coid, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
        if ($row[1] > $max) $max = $row[1];
        $w = 100;
        if ($max != 0) $w = $row[1]/$max*100;
        $ww = 100-$w;
	echo <<<EOD
<tr><td colspan="2">$row[0]</td></tr>
<tr><td colspan="2"><img class="bar" width="$w%" src="graph.png"><br/>
<span class="num" style="position:relative; top:-25px; visibility: hidden;" width="100%">$row[1]票</span></td></tr>
EOD;
          
        }
        mysqli_free_result($result);
    }
    mysqli_close($coid);
	echo <<<EOD
<tr><th colspan="2" style="text-align:center;"><input type="button" value="票数を表示" onclick="numToggle()"/></th></tr>
</table>
</div>
</div>
EOD;
}
function add($sakuhinTitle, $sakushaName, $thx, $kansouArr) {

    $coid = connOpen();
    $sql  = "insert into sakuhin (sakuhin_title, sakusha_name, thx_page) ";
    $sql .= "values ('". $sakuhinTitle . "', '". $sakushaName ."', '". $thx ."')";
    mysqli_query($coid, $sql);
    $sakuhinId = mysqli_insert_id($coid);
    
    foreach ($kansouArr as $kansou) {
        if ($kansou != "") {
            $sql  = "insert into kansou (sakuhin_id, kansou_text) ";
            $sql .= "values ('". $sakuhinId . "', '". $kansou ."')";
            mysqli_query($coid, $sql);
        }
    }

    // 感想登録ページ
    $randomStr = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 20);
    $sql  = "insert into url (uri_key, sakuhin_id, type) ";
    $sql .= "values ('". $randomStr . "', '". $sakuhinId ."', '1')";
    while (!mysqli_query($coid, $sql)) {
        $randomStr = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 20);
        $sql  = "insert into url (uri_key, sakuhin_id, type) ";
        $sql .= "values ('". $randomStr . "', '". $sakuhinId ."', '1')";
    }
    // 感想確認ページ
    $randomStr = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 20);
    $sql  = "insert into url (uri_key, sakuhin_id, type) ";
    $sql .= "values ('". $randomStr . "', '". $sakuhinId ."', '2')";
    while (!mysqli_query($coid, $sql)) {
        $randomStr = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 20);
        $sql  = "insert into url (uri_key, sakuhin_id, type) ";
        $sql .= "values ('". $randomStr . "', '". $sakuhinId ."', '2')";
    }
    // 作品ページ
    $randomStr = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 20);
    $sql  = "insert into url (uri_key, sakuhin_id, type) ";
    $sql .= "values ('". $randomStr . "', '". $sakuhinId ."', '0')";
    while (!mysqli_query($coid, $sql)) {
        $randomStr = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 20);
        $sql  = "insert into url (uri_key, sakuhin_id, type) ";
        $sql .= "values ('". $randomStr . "', '". $sakuhinId ."', '0')";
    }

    mysqli_close($coid);

    //?$randomStr へリダイレクト
    $rootPage = "http://" . $_SERVER['HTTP_HOST'] . preg_split("/\?/", $_SERVER['REQUEST_URI'])[0] . "?" . $randomStr;
    header('Location: '. $rootPage);
    exit();
}

function vote($sakuhinId, $kansouArr) {

    $coid = connOpen();
    foreach ($kansouArr as $kansou) {
        $sql  = "select vote_num from kansou ";
        $sql .= "where kansou_id = '". $kansou . "'";
        if ($result = mysqli_query($coid, $sql)) {
            $row = mysqli_fetch_row($result);
            $num = $row[0] + 1;
            $sql  = "update kansou set vote_num = '". $num ."' ";
            $sql .= "where kansou_id = '". $kansou . "'";
            mysqli_query($coid, $sql);
        }

    }

    $thx = "thx.php";
    $sql  = "select thx_page from sakuhin ";
    $sql .= "where sakuhin_id = '". $sakuhinId . "'";
    if ($result = mysqli_query($coid, $sql)) {
        $row = mysqli_fetch_row($result);
        $thx .= "?thx=" . urlencode($row[0]);
        mysqli_free_result($result);
    }

    mysqli_close($coid);

    //?$thxPage へリダイレクト
    $thxPage = "http://" . $_SERVER['HTTP_HOST'] . preg_split("/\?/", $_SERVER['REQUEST_URI'])[0] . $thx;
    header('Location: '. $thxPage);
    exit();
}

function toppage() {
	echo <<<EOD
<div align="center" >
<div class="head">
 <div class="pagetitle">好きなところ…</div>
 <div class="action"><a href="./new/">作品を登録する</a></div>
</div>
</div>
<div align="center">
<div class="main" align="center" style="text-align: left">
<p>
「好きなところ…」は、内容を制限した感想を送ってもらうためのサービスです。<br/>
「感想は欲しいけど、何を書かれるか不安」<br/>
「感想を送りたいけど、失礼な内容にならないだろうか」<br/>
「おこがましいかもしれないけど、感想によって作品の方向性に影響が出るようなことはしたくない」<br/>
といった方々の架け橋になれれば幸いです。
</p>

<hr>
<h3><a href="javascript:void(0);" id="t_sa" onclick="toggle('sa')">▶感想をもらいたい人へ</a></h3>
<p id="sa" style="display:none;">
作者の方には予め、感想をもらいたい作品を登録し、作品に対して欲しいコメントのリストを登録していただきます。
準備ができたらURLとQRコードが発行されるので、作品の後書きなどに添付し、感想を送るページへ誘導してください。
紙の同人誌を想定したサービスですが、SNSで公開するマンガやイラスト小説や、ゲームやWebサービス（ここのような）にもご利用可能です。
<br/><br/>
<b>メリット</b><br/>
感想をもらう側は、不測の暴言などを受け取る可能性が完全に0%になり、自信のある点や頑張った点などをアピールして的確に褒めてもらうことができます。
<br/>
<b>デメリット</b><br/>
自由入力欄が存在しないため、自分でも気付いていない自分の長所などを見つけてもらうことはできなくなります。<br/>
また、いくら褒められても、その褒め言葉を考えたのは自分です…
</p>
<hr>

<h3><a href="javascript:void(0);" id="t_do" onclick="toggle('do')">▶感想を送りたい人へ</a></h3>
<p id="do" style="display:none;">
感想を送る方は、作品に添付されたURLやQRコードから、感想送信ページに進んでいただき、作者の方が用意した感想から選んで（複数選択可）送信してください。
本サービスでは、どの選択肢に何票集まったか以外は全く記録しておらず、完全に匿名です（お名前を含め、自由記述欄が存在しないので、名乗ることもできません）。
<br/><br/>
<b>メリット</b><br/>
感想を送る側は、作者の方が不快に思うようなコメントをうっかり送ってしまう可能性がなくなり、気軽に感想を送れると思います。
<br/>
<b>デメリット</b><br/>
作者がここに登録していないと感想は送れません…
</p>
</div>
</div>
EOD;
}
?>

</html>
