function opt(num) {
  var tbl = document.getElementById("impressions");
  var old = document.getElementsByName("kansou[]");
  var html = '';
  if (old.length < num) {
    for(i = 0; i < old.length; i++) {
      html = html + '<input type="text" name="kansou[]" maxlength="100" value="' + old[i].value +'"/><br />';
    }
    for(i = old.length; i < num; i++) {
      html = html + '<input type="text" name="kansou[]" maxlength="100"/><br />';
    }
  } else {
    for(i = 0; i < num; i++) {
      html = html + '<input type="text" name="kansou[]" maxlength="100" value="' + old[i].value +'"/><br />';
    }
  }
  tbl.innerHTML = html;
}
