<?php
function connOpen(){
	$coid=mysqli_connect ('localhost', 'user', 'pass');
	if (!$coid) {
		echo "失敗";
	}
    mysqli_select_db($coid, 'db');
	return $coid;
}

function addForm() {
	echo <<<EOD
<table align="center">
<input type="hidden" name="mode" value="add" />
<tr>
 <th>作者名</th><td><input type="text" name="name" maxlength="100"/></td>
</tr>
<tr>
 <th>作品タイトル</th><td><input type="text" name="title" maxlength="100"/></td>
</tr>
<tr>
 <th>投票後のメッセージなど</th><td><input type="text" name="thx" maxlength="255"/></td>
</tr>
<tr>
 <th style="vertical-align: top;">感想の選択肢
 <select name="nums" onchange="opt(this.value);">
  <option value="1">1個</option>
  <option value="2">2個</option>
  <option value="3" selected>3個</option>
  <option value="4">4個</option>
  <option value="5">5個</option>
  <option value="6">6個</option>
  <option value="7">7個</option>
  <option value="8">8個</option>
  <option value="9">9個</option>
  <option value="10">10個</option>
 </select>
</th>
<td>
 <div id="impressions">
  <input type="text" name="kansou[]" maxlength="100"/><br />
  <input type="text" name="kansou[]" maxlength="100"/><br />
  <input type="text" name="kansou[]" maxlength="100"/><br />
 </div>
</td>
</tr>
<tr><th colspan="2" style="text-align:center;"><input type="submit" value="登録する"/></th></tr>
</table>
EOD;
}
?>
