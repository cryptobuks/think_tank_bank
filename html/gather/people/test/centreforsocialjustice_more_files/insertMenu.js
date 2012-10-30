// JavaScript Document
<!--
		function TMGWrapItemAll(s_caption, n_state) {
		var strTbl1 = "<div class='sub" + n_state +"'>"
		var strTbl2 = "</div>"
			if (this.TMR == 0) 
				return ['', s_caption, ''].join('');
			else if (this.TMq.length > 3) 
				return [strTbl1, s_caption, strTbl2].join('');
			return '' + s_caption
		}
		
		var M =	new menu (COBENT_AON_DD_MENU, COBENT_AON_DD_MENU_CONFIG, {'wrapper' : TMGWrapItemAll});
		//-->