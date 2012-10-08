$(document).ready(function(){
  
  var tabContainers = $("div.tabs > div.tab-content");
  $('div.tabs ul.tabNavigation a').click(function() {
    var $tabs = $(this).parents('div.tabs');
    $tabs.find('div.tab-content').hide().filter(this.hash).show();
    $tabs.find('ul.tabNavigation a').removeClass('selected');
    $(this).addClass('selected');      
    return false;
  });

  if (typeof selectedTab == "undefined"){
    selectedTab = $(document).getUrlParam('tab') ;
  }
  $('div.tabs ul.tabNavigation li:first-child a').click();
  if(selectedTab){
    $('div.tabs ul.tabNavigation a').filter('[href=#' + selectedTab + ']').click();
  };  
  // Clear the search box when someone clicks in it
  $(":input#q")
  .focus(function(){
    if ($(this).val() == 'SEARCH'){ $(this).val('') };
  })
  .blur(function(){
    if ($(this).val() == ''){ $(this).val('SEARCH') }
  })
  
  // Clear the mailing list when someone clicks in it
  $(":input#oltyku-oltyku").val('JOIN OUR MAILING LIST...')
  .focus(function(){
    if ($(this).val() == 'JOIN OUR MAILING LIST...'){ $(this).val('') };
  })
  .blur(function(){
    if ($(this).val() == ''){ $(this).val('JOIN OUR MAILING LIST...') }
  });


  
  
  // Cycle the quotes
	$('.quote-area').show().cycle({
	  fx:      'fade', 
    speed:    2000, 
    timeout:  15000
	});
	
	$('body#home .logo').hide();
	$('body#home .logo-rotate').show().cycle({
	  fx:      'fade', 
    speed:    2000, 
    timeout:  15000
	});
  

  // Sortable front page tabs
  if($('#fp-tabs').length){
    $('#fp-tabs').sortable({items:'.tab',  containment:'parent', axis:'y', update: function() {
      $.post('/admin/front_page_tabs/sort', '_method=put&'+$(this).sortable('serialize'));
    }});
  }


  //spam check
  var spam = $('p#spam-check').hide();
  $('input', spam).val('human');
  
  //$('ul.navlist a').sifr({
  //  //debug: true
  //  build: '436',
  //  version: 3,
  //  path: "/javascripts/",
  //  font: "gothamrounded-medium",
  //  forceSingleLine: true,
  //  width: "150"
  //});
  

  
  //var font = {
  //	src: '/javascripts/fgjayneprint.swf'
  //};
  //
  //sIFR.activate(font);
  //
  //sIFR.replace(font, { 
  //	selector: 'ul.navlist a',
  //	css: [
  //	'.sIFR-root { color: #cf2838; background-color: #000000; font-weight: normal; letter-spacing: .5; }',
  //	'a { text-decoration: none; color: #cf2838; }',
  //	'a:hover { text-decoration: underline; color: #cf2838; }'
  //	]
  //	,wmode: 'transparent'
  //	,transparent: true	
  //	,selectable: false
  //	,ratios: [8, 1.41, 10, 1.33, 14, 1.31, 16, 1.26, 20, 1.27, 24, 1.26, 25, 1.24, 26, 1.25, 35, 1.24, 49, 1.23, 74, 1.22, 75, 1.21, 76, 1.22, 77, 1.21, 79, 1.22, 80, 1.21, 81, 1.22, 1.21]
  //});

  //sIFR.replace(font, { 
  //	selector: '#topbar h1',
  //	css: [
  //	'.sIFR-root { color: #cf2838; background-color: #000000; font-weight: normal; letter-spacing: .5; }',
  //	'a { text-decoration: none; color: #cf2838; }',
  //	'a:hover { text-decoration: underline; color: #cf2838; }'
  //	]
  //	,wmode: 'transparent'
  //	,transparent: true	
  //	,selectable: false
  //	,ratios: [8, 1.41, 10, 1.33, 14, 1.31, 16, 1.26, 20, 1.27, 24, 1.26, 25, 1.24, 26, 1.25, 35, 1.24, 49, 1.23, 74, 1.22, 75, 1.21, 76, 1.22, 77, 1.21, 79, 1.22, 80, 1.21, 81, 1.22, 1.21]
  //});
  //sIFR.replace(font, { 
  //	selector: '#text h2',
  //	css: '.sIFR-root { color: #831824; background-color: #E1E0DD; letter-spacing: .5;}'
  //	,wmode: 'transparent'
  //	,transparent: true	
  //	,selectable: false
  //	,ratios: [8, 1.41, 10, 1.33, 14, 1.31, 16, 1.26, 20, 1.27, 24, 1.26, 25, 1.24, 26, 1.25, 35, 1.24, 49, 1.23, 74, 1.22, 75, 1.21, 76, 1.22, 77, 1.21, 79, 1.22, 80, 1.21, 81, 1.22, 1.21]
  //});
  //sIFR.replace(font, { 
  //	selector: 'h3',
  //	css: '.sIFR-root { color: #831824; background-color: #E1E0DD; letter-spacing: .5;}'
  //	,wmode: 'transparent'
  //	,transparent: true	
  //	,selectable: false
  //	,ratios: [8, 1.41, 10, 1.33, 14, 1.31, 16, 1.26, 20, 1.27, 24, 1.26, 25, 1.24, 26, 1.25, 35, 1.24, 49, 1.23, 74, 1.22, 75, 1.21, 76, 1.22, 77, 1.21, 79, 1.22, 80, 1.21, 81, 1.22, 1.21]
  //});

});
