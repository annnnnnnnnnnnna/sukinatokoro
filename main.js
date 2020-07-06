function toggle(id) {
  var oelm = document.getElementById('t_'+id);
  var txt = oelm.innerHTML;
  if(txt.indexOf("▶") != -1) oelm.innerHTML = txt.replace("▶", "▼");
  else oelm.innerHTML = txt.replace("▼", "▶");
  
  var elm = document.getElementById(id);
  var v = elm.style.display;
  var next = "none";
  if (v == "none") next = "block";
  elm.style.display = next;
}
function numToggle() {
  var elms = document.getElementsByClassName('num');
  var v = elms[0].style.visibility;
  var next = "hidden";
  if (v == "hidden") next = "visible";
  for(var i=0;i<elms.length;i++){
    elms[i].style.visibility = next;
  }
}
