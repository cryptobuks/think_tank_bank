var COBENT_AON_DD_MENU_CONFIG =[
// Level 0 block configuration
{
	// Item's height in pixels
	'height'     :23,
	// Item's width in pixels
	'width'      : 100,
	// if Block Orientation is vertical
	'vertical'   : false,
	// Time Delay in milliseconds before subling block expands
	// after mouse pointer overs an item
	'expd_delay' : 200,
	// Style class names for the level
	'css': {
		// Block outing table class
		'table' : 'menuRoot',
		// Item outer tag style class for all item states or
		// classes for [<default state>, <hovered state>, <clicked state>]
		'outer' : ['menuRootOuter','menuRootOuterOver','menuRootOuterClick'],
		// Item inner tag style class for all item states or
		// classes for [<default state>, <hovered state>, <clicked state>]
		'inner' : ['menuRootInner','menuRootInnerOver','']
	}
},
// Level 1 block configuration
{
	'width'      : 175,
	'height'     : 23,
	// Vertical Offset between adjacent levels in pixels
	'block_top'  : 23,
	// Horizontal Offset between adjacent levels in pixels
	'block_left' : 0,
	// block behaviour if single frame:	
	// 1 - shift to the edge, 2 - flip relatively to left upper corner
	'wise_pos'   : 2,
	'vertical'   :true,
	// transition effects for the block 
	// [index on expand, duration on expand, index on collapse, duration on collapse]
	//'transition' : [0, 0.3, 0, 0.3],
	'transition': ['revealTrans(TRANSITION=5,DURATION=0.1)','progid:DXImageTransform.Microsoft.Fade(DURATION=0.5)'],
	//shadow
	// level shadow scope settings
	//'shadow' : {
		// color of the shadow
		'color' : '#3333333',
		// horisontal offset of the shadow in pixels
		'offX'  : 1,
		// vertical offset of the shadow in pixels
		'offY'  :1
	//}
	,
	// Time Delay in milliseconds before menu collapses after mouse
	// pointer lefts all items
	'opacity' :100,
	'hide_delay' : 300,
	'css' : {
		'table' : 'menuSub',
		'outer' : ['menuSubOuter','menuSubOuterOver',''],
		'inner' : ['menuSubInner','menuSubInnerOver','']
	}
},
// Level 2 block configuration
{	
	'block_top'  : 12,
	'block_left' : 175
}
// Level 3 configuration 

]

