<!--

  
  //flash detection script

var requiredVersion = 7;   // Version the user needs to view site (max 9, min 2)
var useRedirect = false;    // Flag indicating whether or not to load a separate
                           // page based on detection results. Set to true to
                           // load a separate page. Set to false to embed the
                           // movie or alternate html directly into this page.

var flashPage   = "movie.html"    // The location of the flash movie page
var noFlashPage = "noflash.html"  // Page displayed if the user doesn't have the
                                  // plugin or we can't detect it.
var upgradePage = "upgrade.html"  // Page displayed if we detect an old plugin
// =============================================================================

// System globals
var flash2Installed = false;    // boolean. true if flash 2 is installed
var flash3Installed = false;    // boolean. true if flash 3 is installed
var flash4Installed = false;    // boolean. true if flash 4 is installed
var flash5Installed = false;    // boolean. true if flash 5 is installed
var flash6Installed = false;    // boolean. true if flash 6 is installed
var flash7Installed = false;    // boolean. true if flash 7 is installed
var flash8Installed = false;    // boolean. true if flash 8 is installed
var flash9Installed = false;    // boolean. true if flash 9 is installed
var maxVersion = 9;             // highest version we can actually detect
var actualVersion = 0;          // version the user really has
var hasRightVersion = false;    // boolean. true if it's safe to embed the flash movie in the page
var jsVersion = 1.0;            // the version of javascript supported


// Check the browser...we're looking for ie/win
var isIE  = (navigator.appVersion.indexOf("MSIE") != -1) ? true : false;    // true if we're on ie
var isWin = (navigator.appVersion.toLowerCase().indexOf("win") != -1) ? true : false; // true if we're on windows


// This is a js1.1 code block, so make note that js1.1 is supported.
jsVersion = 1.1;

// Write vbscript detection on ie win. IE on Windows doesn't support regular
// JavaScript plugins array detection.
if(isIE && isWin){
  document.write('<SCR' + 'IPT LANGUAGE=VBScript\> \n');
  document.write('on error resume next \n');
  document.write('flash2Installed = (IsObject(CreateObject("ShockwaveFlash.ShockwaveFlash.2"))) \n');
  document.write('flash3Installed = (IsObject(CreateObject("ShockwaveFlash.ShockwaveFlash.3"))) \n');
  document.write('flash4Installed = (IsObject(CreateObject("ShockwaveFlash.ShockwaveFlash.4"))) \n');
  document.write('flash5Installed = (IsObject(CreateObject("ShockwaveFlash.ShockwaveFlash.5"))) \n');  
  document.write('flash6Installed = (IsObject(CreateObject("ShockwaveFlash.ShockwaveFlash.6"))) \n');  
  document.write('flash7Installed = (IsObject(CreateObject("ShockwaveFlash.ShockwaveFlash.7"))) \n');
  document.write('flash8Installed = (IsObject(CreateObject("ShockwaveFlash.ShockwaveFlash.8"))) \n');
  document.write('flash9Installed = (IsObject(CreateObject("ShockwaveFlash.ShockwaveFlash.9"))) \n');
  document.write('<\/SCR' + 'IPT\> \n'); // break up end tag so it doesn't end our script
}


// Next comes the standard javascript detection that uses the 
// navigator.plugins array. We pack the detector into a function so that 
// it preloads before being run.

function detectFlash() {  
  // If navigator.plugins exists...
  if (navigator.plugins) {
    // ...then check for flash 2 or flash 3+.
    if (navigator.plugins["Shockwave Flash 2.0"]
        || navigator.plugins["Shockwave Flash"]) {

      // Some version of Flash was found. Time to figure out which.
      
      // Set convenient references to flash 2 and the plugin description.
      var isVersion2 = navigator.plugins["Shockwave Flash 2.0"] ? " 2.0" : "";
      var flashDescription = navigator.plugins["Shockwave Flash" + isVersion2].description;

      // DEBUGGING: uncomment next line to see the actual description.
      // alert("Flash plugin description: " + flashDescription);
      
      // A flash plugin-description looks like this: Shockwave Flash 4.0 r5
      // We can get the major version by grabbing the character before the period
      // note that we don't bother with minor version detection. 
      // Do that in your movie with $version or getVersion().
      var flashVersion = parseInt(flashDescription.substring(16));

      // We found the version, now set appropriate version flags. Make sure
      // to use >= on the highest version so we don't prevent future version
      // users from entering the site.
      flash2Installed = flashVersion == 2;    
      flash3Installed = flashVersion == 3;
      flash4Installed = flashVersion == 4;
      flash5Installed = flashVersion == 5;
      flash6Installed = flashVersion == 6;
      flash7Installed = flashVersion == 7;
      flash8Installed = flashVersion == 8;
      flash9Installed = flashVersion >= 9;
    }
  }
  
  // Loop through all versions we're checking, and
  // set actualVersion to highest detected version.
  for (var i = 2; i <= maxVersion; i++) {  
    if (eval("flash" + i + "Installed") == true) actualVersion = i;
  }
  
  // If we're on msntv (formerly webtv), the version supported is 4 (as of
  // January 1, 2004). Note that we don't bother sniffing varieties
  // of msntv. You could if you were sadistic...
  if(navigator.userAgent.indexOf("WebTV") != -1) actualVersion = 4;  
  
  // DEBUGGING: uncomment next line to display flash version
  //alert("version detected: " + actualVersion);


  // We're finished getting the version on all browsers that support detection.
  // Time to take the appropriate action.

  // If the user has a new enough version...
  if (actualVersion >= requiredVersion) {
    // ...then we'll redirect them to the flash page, unless we've
    // been told not to redirect.
    if (useRedirect) {
      // Need javascript1.1 to do location.replace
      if(jsVersion > 1.0) {
        // It's safe to use replace(). Good...we won't break the back button.
        window.location.replace(flashPage);  
      } else {
        // JavaScript version is too old, so use .location to load
        // the flash page.
        window.location = flashPage;
      }
    }

    // If we got here, we didn't redirect. So we make a note that we should
    // write out the object/embed tags later.
    hasRightVersion = true;                
  } else {  
    // The user doesn't have a new enough version.
    // If the redirection option is on, load the appropriate alternate page.
    if (useRedirect) {
      // Do the same .replace() call only if js1.1+ is available.
      if(jsVersion > 1.0) {
        window.location.replace((actualVersion >= 2) ? upgradePage : noFlashPage);
      } else {
        window.location = (actualVersion >= 2) ? upgradePage : noFlashPage;
      }
    }
  }
}

detectFlash();  // call our detector now that it's safely loaded. 

function writeFlash(sSrc,sWidth,sHeight,sAltSrc,sAltWidth,sAltHeight,sAltParams) {

	if (!useRedirect) {    // if dynamic embedding is turned on
	  if(hasRightVersion) {  // if we've detected an acceptable version
		var oeTags = '<OBJECT CLASSID="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"'
		+ 'WIDTH="' + sWidth + '" HEIGHT="' + sHeight + '"'
		+ 'CODEBASE="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab">'
		+ '<PARAM NAME="MOVIE" VALUE="' + sSrc + '">'
		+ '<PARAM NAME="PLAY" VALUE="true">'
		+ '<PARAM NAME="LOOP" VALUE="true">'
		+ '<PARAM NAME="QUALITY" VALUE="high">'
		+ '<PARAM NAME="MENU" VALUE="false">'
		+ '<param name="wmode" value="transparent">'
		+ '<EMBED SRC="' + sSrc + '"'
		+ 'WIDTH="' + sWidth + '" HEIGHT="' + sHeight + '"'
		+ 'PLAY="true"'
		+ 'LOOP="true"'
		+ 'QUALITY="high"'
		+ 'MENU="false"'
		+ 'wmode="transparent"'
		+ 'TYPE="application/x-shockwave-flash"'
		+ 'PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">'
		+ '<\/EMBED>'

		+ '<\/OBJECT>';
	
		document.write(oeTags);   // embed the flash movie
	  } else {  // flash is too old or we can't detect the plugin
		// NOTE: height, width are required!
		if (sAltSrc !='') {
		var alternateContent = '<IMG SRC="'+ sAltSrc + '" HEIGHT="' + sAltHeight + '" WIDTH="' + sAltWidth + '" ' + sAltParams + '>';
		document.write(alternateContent);  // insert non-flash content
		}
	  }
	}
}

  <!--

  // #########################################################################  
  // menu functions
  function MM_jumpMenu(targ,selObj,restore)
  { 
      if (selObj.options[selObj.selectedIndex].value.substring(0,7) == 'http://')
      {
          window.open(selObj.options[selObj.selectedIndex].value,"ExternalLink", "toolbar=yes,directories=yes,status=yes,resizable=yes,width=640,height=480,menubar=yes,scrollbars=yes,location=yes,locationbar=yes").focus();
      }
      else if (selObj.options[selObj.selectedIndex].value != '')
      {
          eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
          if (restore) selObj.selectedIndex=0;
      }
  }

  function MM_findObj(n, d)
  { //v3.0
    var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
      d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
    if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
    for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
  }

  function goto_URL(object)
  {
      window.location.href = object.options[object.selectedIndex].value;
  }
  // /menu functions
  // #########################################################################  

  // #########################################################################  
  // popups
  function OpenKandoWin(url)
  {
    browserName =navigator.appName;
    if (browserName=="Microsoft Internet Explorer") {
      window.open(url,"newWindow", "toolbar=no,directories=yes,status=yes,resizable=yes,width=310,height=450,menubar=yes,scrollbars=no,locationbar=yes").focus();
    } else {
      window.open(url,"newWindow", "toolbar=no,directories=yes,status=yes,resizable=yes,InnerWidth=310,InnerHeight=450,menubar=yes,scrollbars=no,locationbar=yes").focus();
    }
  }

  function externalWin(url)
  {
    window.open(url,"newWindow", "location=yes,toolbar=yes,directories=yes,status=yes,resizable=yes,width=640,height=480,menubar=yes,scrollbars=yes,locationbar=yes").focus();
  }

  function popupWin(URL,xsize,ysize)
  {
    window.open(URL,"newWindow", "width="+xsize+",height="+ysize+",location=no,toolbar=no,directories=no,status=no,resizable=no,menubar=no,scrollbars=yes,locationbar=no").focus();
  }
  // /popups
  // #########################################################################  

  // #########################################################################  
  // form functions
  function submitsearch()
  {
      document.forms.htsearch.submit();
  }
  // /form functions
  // #########################################################################  


  // ######################################################################
  // object checkBrowser  
  function checkBrowser()
  {
      this.ver=navigator.appVersion
      this.dom=document.getElementById?1:0
      this.ie5=( (this.ver.indexOf("MSIE 5")>-1 || 
                  this.ver.indexOf("MSIE 6")>-1) && this.dom)?1:0
      this.ie4=(document.all && !this.dom)?1:0
      this.ns5=(this.dom && parseInt(this.ver) >= 5)?1:0
      this.ns4=(document.layers && !this.dom)?1:0
      this.bw=(this.ie5 || this.ie4 || this.ns4 || this.ns5)
      return this
  }
  // the bw object must exist before the page ist loaded completely
  bw = new checkBrowser()

  // General Skripts for layer handling
  function divExists(myName)
  {
      return ( bw.dom?document.getElementById(myName):
               bw.ie4?document.all[myName+'Sub']:
               bw.ns4?eval('document.'+myName):false ) 
  }

  function getLayerByName(name)
  {
      i = 0
      while (i < layers.length)
      {
          if (layers[i].name == name)
          {
              return layers[i]
          }
          i++
      }
      return false
  }
  // /object checkBrowser  
  // ######################################################################

  // ######################################################################
  // classdef
  // layer object to handle the <div>s

  function layer(name)
  {
      if (! divExists(name))
      {
          this.name=false
          return (this)
      }
      // Zugriff auf DOM oder Layer-Attribute
      this.css=bw.dom?document.getElementById(name).style:
          bw.ie4?document.all[name].style:
          bw.ns4?eval("document.layers."+name):0
      // LayerObject
      this.el=bw.dom?document.getElementById(name):
          bw.ie4?document.all[name]:
          bw.ns4?eval('document.'+name):0
      // DocumentObject - ist bei NS4 im Layer-Object verschachtelt
      this.ref=bw.dom || bw.ie4?document:bw.ns4?eval("document.layers."
              +name+".document"):0
      this.write=layerWrite
      this.x=0
      this.y=0
      this.name=name
      this.Width=bw.ns4?this.ref.width:this.el.offsetWidth
      this.Height=bw.ns4?this.ref.height:this.el.offsetHeight
      this.setX=layerSetX
      this.setY=layerSetY
      this.hide=layerHide
      this.unhide=layerUnhide
      this.vis=layerVis
      this.moveIt=layerMoveTo
      return(this)
  }

      // methods for class layer
      function layerHide()
      {
          this.css.visibility="hidden"
          return true
      }

      function layerUnhide()
      {
          this.css.visibility="visible"
          return true
      }
      function layerVis()
      {
          if(this.css.visibility=="hidden" || this.css.visibility=="hide") 
          {
              return false
          }
          return true
      }

      function layerMoveTo(x,y)
      {
          this.setX(x)
          this.setY(y)
          return true
      }

      function layerSetY(y)
      {
          this.y=y; this.css.top=y
          return true
      }
      function layerSetX(x)
      {
          this.x=x; this.css.left=x
          return true
      }
    
      function layerWrite(text)
      {
          if (bw.dom) 
          {
              this.el.firstChild.nodeValue = text
          }

          if (bw.ns4)
          {
              toEval = "document.layers." + this.name +
                       ".document.write('" + text + "')"
              eval(toEval);
              toEval = "document.layers." + this.name + ".document.close()"
              eval(toEval);
          }
          return true
      }
  // /Classdef Layer
  // ######################################################################

  // ######################################################################
  // Event Capture - captures mousemoves and sets mouse position in 
  // globals mousePosX and mousePosY
  function mouseGetPos(e) 
  {
      mousePosX=(bw.ns4||bw.ns5)?e.pageX:event.clientX;
      mousePosY=(bw.ns4||bw.ns5)?e.pageY:event.clientY;
      if (toolTipLayer)
      {
          toolTipLayer.moveIt(mousePosX + ttOffX, mousePosY + ttOffY)
      }
      return true;
  }

  function setMouseCapture()
  {
      if (bw.ns4) {
          document.captureEvents(Event.MOUSEMOVE)
      }
      document.onmousemove=mouseGetPos
  }
  // /Event Capture
  // ######################################################################

  // ######################################################################
  // Clock functions

  function prenull(value)
  {
      if (String(value).length > 1)
      {
          return (value);
      }
      value = '0' + value;
      return (value.substr(0,2));
  }

  function getDate()
  {
      var now = new Date();
      var year = now.getFullYear();
      var month = prenull(now.getMonth() + 1);
      var dom = prenull(now.getDate());
      var hour = prenull(now.getHours());
      var min = prenull(now.getMinutes());
      var sec = prenull(now.getSeconds());

      date = dom + '/' + month + '/' + year + ', ' +
             hour + ':' + min + ':' + sec;
      return date;
  }

  function writeDate(layer) 
  {
     if (layer) {
         layer.write(getDate())
         return true;
     } else {
         return false;
     }
  }

  // /Clock functions
  // ######################################################################


  // Globals
  var bw                     // browser capabilities object
  var initialized=false      // layers inititialized?
  var layers = new Array()   // Array of layers
  var clockLayer             // The clock layer
  var toolTipLayer           // the tooltip layer
  var mousePosX              // mouse position
  var mousePosY
  var ttOffX = 10            // offset of the tooltip
  var ttOffY = 10            // relative to mouseposition
  var capturemouse=false     // set this to 'true' inside the html source
                             // in order to let the mouse to be captured:
                             // <script language="JavaScript">
                             //     capturemouse = true
                             // </script>
 
  // ######################################################################
  // function to be called from within the body

  // Layer initialization
  function initLayers()
  {
      if (initialized) return 0
      initialized = true
      if (capturemouse == true)
      {
          setMouseCapture()
      }
      return true
  }

  function showClock(layerName)
  {
      if (! clockLayer)
      { 
        clockLayer = new layer(layerName)
        layers[layers.length++] = clockLayer
        if (!clockLayer.name) {
            return false
        }
      }
      writeDate(clockLayer);
      eval("setTimeout('showClock()', 1000)");
      return true
  }
 
  function showToolTip(layerName, text)
  {
      if (! capturemouse)
      {
          return false
      }
      if (! toolTipLayer)
      {
          toolTipLayer = new layer(layerName)
      }
      toolTipLayer.write(text)
      toolTipLayer.unhide()
  }

  function hideToolTip(layerName)
  {
      if (! toolTipLayer)
      {
          toolTipLayer = new layer(layerName)
      }
      toolTipLayer.hide()
  }
   
 function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}

function checkSearchFormA()
{ 
if (topSearch.keywords.value != '')
{
topSearch.submit();
}
else {alert("Please enter a search term.");
return false;
}
}
function showHide(Item)
{
var obj= document.getElementById(Item)
obj.style.visibility =(obj.style.visibility=='hidden') ? 'visible' : 'hidden'; 

}
function clearValue(i)
{
	if (i.value =='Site Search') {i.value=''}
}
function findPosX(obj)
{
	var curleft = 0;
	if (obj.offsetParent)
	{
		while (obj.offsetParent)
		{
			curleft += obj.offsetLeft
			obj = obj.offsetParent;
		}
	}
	else if (obj.x)
		curleft += obj.x;
	return curleft;
}

function findPosY(obj)
{
	var curtop = 0;
	if (obj.offsetParent)
	{
		while (obj.offsetParent)
		{
			curtop += obj.offsetTop
			obj = obj.offsetParent;
		}
	}
	else if (obj.y)
		curtop += obj.y;
	return curtop;
}
function highlight(o,state) {

		var str = new String
		var x = findPosX(o)
		var y = findPosY(o)
		var w = o.offsetWidth
		var h = o.offsetHeight
	
		if (state == 'show') {
		var overlay = document.createElement('div');
		document.getElementsByTagName('body')[0].appendChild(overlay);
		overlay.style.top = y;
		overlay.style.left = x;
		overlay.style.width = w;
		overlay.style.height = h;
		overlay.id = 'overlay_' + o.id;
		overlay.className = 'editOverlay';
		}

		if (state=='hide') {
		if (document.getElementById('overlay_' + o.id)) {
		document.getElementById('overlay_' + o.id).removeNode(true);
			}
		}
}

function showSlideShow(galleryID) {

	try { 
	//modal dialog window - nice and neat but supported by IE only
		window.showModalDialog('/slideshow.asp?galleryID=' + galleryID,'slideshow','dialogHeight:590px;dialogWidth:780px;status:yes;center:yes;minimise:no;help:no;maximise:no;resizable:no')
	}
	catch (e) {
	//normal window opener if the modal method fails - may be stopped by pop-up blocker
	window.open('/slideshow.asp?galleryID=' + galleryID, 'slideshow', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbar=no,resizable=no,width=780px,height=590px');
	}
}

	document.onkeydown = function keyPress(evt) 
		{ 
		 if (event.ctrlKey) 
		 { 
		  var keyCode = 
		  document.layers ? evt.which : 
		  document.all ? event.keyCode : 
		  document.getElementById ? evt.keyCode : 0; 
		  if (keyCode == 18) 
		   {
			   window.location.href='/aon/login.asp?redir='+window.location.pathname + window.location.search}   
			 
		  } 
		}
 
// -->
