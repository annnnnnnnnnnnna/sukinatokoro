<?php
require('../common.php');
	echo <<<EOD
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<link rel="stylesheet" href="../common.css" media="screen"/>
<link rel="stylesheet" href="../small.css" media="screen and (max-width:960px)"/>
<link rel="stylesheet" href="../large.css" media="screen and (min-width:960px)"/>
<script type="text/javascript" src="../add.js"></script>
</head>
<body>

<div align="center">
<div class="head">
 <div class="pagetitle">作品登録</div>
</div>
</div>

<div align="center">
<div class="main" align="center">
<form action="../" method="post">
EOD;
addForm();
echo <<<EOD
</form>
</div>
</div>
</body>

</html>
EOD;
?>
