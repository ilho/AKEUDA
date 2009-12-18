/* 
------------------------------------------
 Flipbox written by CrappoMan
 simonpatterson@dsl.pipex.com
------------------------------------------
*/

//modified by Wooya
function flipBox(who) {
   var tmp;
   if (document.images['b_' + who].src.indexOf('_on') == -1) {
      tmp = document.images['b_' + who].src.replace('_off', '_on');
      document.getElementById('box_' + who).style.display = 'none';
      if (document.getElementById('box_' + who + '_diff')) {
         document.getElementById('box_' + who + '_diff').style.display = 'block';
      }
      document.images['b_' + who].src = tmp;
      disply = 'none';
      now = new Date();
      now.setTime(now.getTime()+1000*60*60*24*365);
      expire = (now.toGMTString());
      document.cookie = "fusion_box_"+who+"=" + escape(disply) + "; expires="+expire;
   } else {
      tmp = document.images['b_' + who].src.replace('_on', '_off');
      document.getElementById('box_' + who).style.display = 'block';
      if (document.getElementById('box_' + who + '_diff')) {
         document.getElementById('box_' + who + '_diff').style.display = 'none';
      }
      document.images['b_' + who].src = tmp;
      disply = 'block';
      now = new Date();
      now.setTime(now.getTime()+1000*60*60*24*365);
      expire = (now.toGMTString());
      document.cookie = "fusion_box_"+who+"=" + escape(disply) + "; expires="+expire;
   }
}

//modified by wooya
function addText(elname, strFore, strAft, formname) {
   if (formname == undefined) formname = 'inputform';
   if (elname == undefined) elname = 'message';
   element = document.forms[formname].elements[elname];
   element.focus();
   // for IE 
   if (document.selection) {
	   var oRange = document.selection.createRange();
	   var numLen = oRange.text.length;
	   oRange.text = strFore + oRange.text + strAft;
	   return false;
   // for FF and Opera
   } else if (element.setSelectionRange) {
      var selStart = element.selectionStart, selEnd = element.selectionEnd;
			var oldScrollTop = element.scrollTop;
      element.value = element.value.substring(0, selStart) + strFore + element.value.substring(selStart, selEnd) + strAft + element.value.substring(selEnd);
      element.setSelectionRange(selStart + strFore.length, selEnd + strFore.length);
			element.scrollTop = oldScrollTop;      
      element.focus();
   } else {
			var oldScrollTop = element.scrollTop;
      element.value += strFore + strAft;
			element.scrollTop = oldScrollTop;      
      element.focus();
	}
}

//modified by Wooya
function insertText(elname, what, formname) {
   if (formname == undefined) formname = 'inputform';
   if (document.forms[formname].elements[elname].createTextRange) {
       document.forms[formname].elements[elname].focus();
       document.selection.createRange().duplicate().text = what;
   } else if ((typeof document.forms[formname].elements[elname].selectionStart) != 'undefined') {
       // for Mozilla
       var tarea = document.forms[formname].elements[elname];
       var selEnd = tarea.selectionEnd;
       var txtLen = tarea.value.length;
       var txtbefore = tarea.value.substring(0,selEnd);
       var txtafter =  tarea.value.substring(selEnd, txtLen);
       var oldScrollTop = tarea.scrollTop;
       tarea.value = txtbefore + what + txtafter;
       tarea.selectionStart = txtbefore.length + what.length;
       tarea.selectionEnd = txtbefore.length + what.length;
       tarea.scrollTop = oldScrollTop;
       tarea.focus();
   } else {
       document.forms[formname].elements[elname].value += what;
       document.forms[formname].elements[elname].focus();
   }
}

//modified by Wooya to W3C standards
function show_hide(msg_id) {
   document.getElementById(msg_id).style.display = document.getElementById(msg_id).style.display == 'none' ? 'block' : 'none';
}

//modified by Wooya to work properly with Opera
function correctPNG() {
   // correctly handle PNG transparency in Win IE 5.5 or higher.
   if (navigator.appName=="Microsoft Internet Explorer" && navigator.userAgent.indexOf("Opera")==-1) {
      for(var i=0; i<document.images.length; i++) {
         var img = document.images[i]
         var imgName = img.src.toUpperCase()
         if (imgName.substring(imgName.length-3, imgName.length) == "PNG") {
            var imgID = (img.id) ? "id='" + img.id + "' " : ""
            var imgClass = (img.className) ? "class='" + img.className + "' " : ""
            var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' "
            var imgStyle = "display:inline-block;" + img.style.cssText
            if (img.align == "left") imgStyle = "float:left;" + imgStyle
            if (img.align == "right") imgStyle = "float:right;" + imgStyle
            if (img.parentElement.href) imgStyle = "cursor:hand;" + imgStyle
            var strNewHTML = "<span " + imgID + imgClass + imgTitle
            + " style=\"" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";"
            + "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
            + "(src=\'" + img.src + "\', sizingMethod='scale');\"></span>"
            img.outerHTML = strNewHTML
            i = i-1
         }
      }
   }
}

function getStyle(el,style)
{
	if(typeof el == "string")
		var element = document.getElementById(el);
	else
		var element = el;
	if (element.currentStyle)
		var value = element.currentStyle[style];
	else if (window.getComputedStyle)
		var value = document.defaultView.getComputedStyle(element,null).getPropertyValue(style);
	return value;
}

/***********************************************
* Drop Down/ Overlapping Content- © Dynamic Drive (www.dynamicdrive.com)
* This notice must stay intact for legal use.
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/
function getposOffset(overlay, offsettype){
   var totaloffset=(offsettype=='left')? overlay.offsetLeft : overlay.offsetTop;
   var parentEl=overlay.offsetParent;
   while (parentEl!=null) {
      if(getStyle(parentEl, "position") != "relative"){
	     totaloffset=(offsettype=='left')? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
      }
	  parentEl=parentEl.offsetParent;
   }
   return totaloffset;
}
   
function overlay(curobj, subobjstr, opt_position){
   if (document.getElementById){
      var subobj=document.getElementById(subobjstr)
      subobj.style.display=(subobj.style.display!='block')? 'block' : 'none'
      var xpos=getposOffset(curobj, 'left')+((typeof opt_position!='undefined' && opt_position.indexOf('right')!=-1)? -(subobj.offsetWidth-curobj.offsetWidth) : 0) 
      var ypos=getposOffset(curobj, 'top')+((typeof opt_position!='undefined' && opt_position.indexOf('bottom')!=-1)? curobj.offsetHeight : 0)
      subobj.style.left=xpos+'px'
      subobj.style.top=ypos+'px'
      return false
   }
   else
   return true
}

function overlayclose(subobj){
document.getElementById(subobj).style.display='none'
}

//written by Wooya
NewWindowPopUp = null;
function OpenWindow(src, wdth, hght, wcenter) {
   //close previous popup window
   if (NewWindowPopUp != null) {
        NewWindowPopUp.close();
        NewWindowPopUp = null;
   }
   //if center parameter given center opoup window 
   if (wcenter == false) { 
      wtop = 0;
      wleft = 0;
   } else {
        wtop = (screen.availHeight-hght)/2;
        wleft = (screen.availWidth-wdth)/2;
   }
   NewWindowPopUp = window.open(src, "","toolbar=no,menubar=no,location=no,personalbar=no,scrollbars=yes,status=no,directories=no,resizable=yes,height="+hght+",width="+wdth+",top="+wtop+",left="+wleft+"");
   NewWindowPopUp.focus();
}

//Image Resizer by Matonor
function resize_forum_imgs(){
	var max;
	var viewport_width;
	//Get the width of the viewport
	if(self.innerWidth)
		viewport_width = self.innerWidth;
	else if(document.documentElement && document.documentElement.clientWidth)
		viewport_width = document.documentElement.clientWidth;
	else if(document.body)
		viewport_width = document.body.clientWidth;
	else
		viewport_width = 1000;

	//Set the max width/height according to the viewport-width
	if(viewport_width <= 800)
		max = 200;
	else if(viewport_width < 1152)
		max = 300;
	else if(viewport_width >= 1152)
		max = 400;
	
	//loop through images that have the className forum-img
	for(var i=0; i<document.images.length; i++) {
		var image = document.images[i];
		if(image.className!="forum-img"){
			continue;
		}
		var height = image.height;
		var width = image.width;
		var resized = false;
		//resize the image with correct aspect ratio
		if(width <= height){
			if(height > max){
				image.height = max;
				image.width = width*(max/height);
				resized = true;
			}
		}else{
			if(width > max){
				image.width = max;
				image.height = height*(max/width);
				resized = true;
			}
		}
		
		//Find the div around the image and the next element around the div.
		var span = image.parentNode;
		var parent = span.parentNode;
		if(span.className != "forum-img-wrapper"){
			continue;
		}
		
		if(resized){ //Insert the link and make the span inline
			span.style.display = "inline";
			if(parent.tagName != "A"){
				span.onclick = new Function("OpenWindow('"+image.src+"', "+(width+40)+", "+(height+40)+", true)");
				span.onmouseover = "this.style.cursor='pointer'";
			}
		}else{ //only make the span inline
			span.style.display = "inline";
		}
	}
	return true;
}

function onload_events(){//Add function calls to be executed onload here
	resize_forum_imgs();
	correctPNG();
}

window.onload = onload_events;
