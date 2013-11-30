/* * * * * * * * * * * * * * * * * * * * * *
** findFile JS
** Author: Jorge Chaclán - http://kukugt.com
** Twitter: @kukugt 
**
** JS library: Mootools 1.4.5 - http://mootools.net
*/
var arraySections = [];
var scrollingMove = null;

/*
**
*/
function resizeViewport () {
	var ScrollView		= window.getSize().x;
	var widthSections	= (arraySections.length*151);
	var widthOverflow	= (ScrollView <= widthSections);
	var activeView		= arraySections.getLast().hasClass('view');
	var navSections		= [];
	var nav_url			= null;
	$('scrolling').setStyles({
		width:		(window.getSize().x) + 'px',
		height:		(window.getSize().y - 50) + 'px',
		maxHeight:	(window.getSize().y - 50) + 'px'
	});
	arraySections.each(function(el){
		el.setStyle('height', (window.getSize().y-50) + 'px');
	});

	// resize and scrolling
	if (widthOverflow)	ScrollView = widthSections;
	if (activeView)		ScrollView = widthSections+(arraySections.getLast().getSize().x-153);
	$('content').morph({ 'width': ScrollView+'px' });
	if (widthOverflow || activeView) scrollingMove.start(ScrollView, 0);

	// StatusBar 
	nav_url = arraySections.getLast();
	nav_url = ((activeView)?nav_url.getElement('iframe').get('src'):nav_url.getElement('a').get('href'));
	if ( nav_url!=null && nav_url.length>0 ) {
		navSections = nav_url.substr(2).split("/");
		navSections.pop();
	}
	$('status').set('html', "> <span>" + navSections.join('</span> > <span>') + "</span>" ); 

}

/*
**
*/
function deleteElement (idIndex) {
	var flagRoot	= false;
	var delElements	= [];
	arraySections.each(function(el){
		if ( flagRoot || el.hasClass('index'+idIndex) || el.hasClass(idIndex) ){
			flagRoot = true;
			el.destroy();
			delElements.push(el);
		}
	});
	delElements.each(function(el){ arraySections.erase(el); });

}

/*
**
*/
function viewElement (dir,idIndex) {
	deleteElement(idIndex);
	
	var divSection = new Element('div#section.view.index'+idIndex,{
		children:[
		new Element('div#headview',{
			children: [
			new Element('a',{
				text:	'X',
				href:	"#",
				events:{
					click: function(){
						deleteElement( arraySections.getLast().get('Class')  );
						window.fireEvent('resize');
						return false;
					}
				}
			}),
			new Element('a',{
				target:	'_blank',
				text:	'Open',
				href:	dir[1]
			}),
			new Element('span',{text: 'Zoom 50%'})
			]
		}),
		
		new IFrame({src: ((Cookie.read('highlight')==1)?'findFile/findFile.php?dir='+dir[1]:dir[1]) }),

		new Element('div#fileDescription',{
			children:[
			new Element('span',{html:'<b>Name: </b>' + dir[0].split('.').shift() + '<br/>' }),
			new Element('span',{html:'<b>Type: </b>' + dir[0].split('.').pop()   + '<br/>' }),
			new Element('span',{html:'<b>Size: </b>' + dir[4] + '<br/>' }),
			new Element('span',{html:'<b>Last Modified: </b>' + (new Date(dir[3]*1000).format('rfc822')) + '<br/>' })
			]
		})
		]
	});
	arraySections.push( divSection );
	$('content').adopt(divSection);
	
}

/*
**
*/
function choiceElement (val,idIndex) {
	var el;
	if ( val[2]=='D' ) {
		el = new Element('div#item.dir',{
			children:[
			new Element('a',{
				text:	val[0],
				href:	val[1], 
				events: {
					click: function(){
						getElements(val[1],idIndex+1);
						return false;
					}
				}
			})
			]
		});
	} else if ( val[2]=='F' ) {
		el = new Element('div#item',{
			children:[
			new Element('a',{
				text:	val[0],
				href:	val[1], 
				events: {
					click: function(){
						viewElement(val,idIndex+1);
						window.fireEvent('resize');
						return false;
					}
				}
			})
			]
		});
	} else {
		el = new Element('div#item',{ children:[ new Element('a.title',{ text: val[0] }) ] });
	}

	return el;

}

/*
**
*/
function getElements (dir,idIndex) {
	var arrayItems	= new Array();
	new Request.JSON({
		onComplete: function(json) {
			Object.each(json,function(val,key){
				arrayItems.push( choiceElement(val,idIndex) );
			});
			deleteElement(idIndex);
			var divSection = new Element('div#section.index'+idIndex);
			arraySections.push( divSection );
			Array.each(arrayItems, function(obj,index){ divSection.adopt(obj); });
			$('content').adopt(divSection);
		},
		onSuccess: function(){ window.fireEvent('resize'); }
	}).post({ 'dir': dir });

}

/*
**
*/
window.addEvent('domready', function(){
	//
	if (Browser.ie) $(document.html).addClass("oldie ie"+Browser.version);
	
	new Element('div#view',{
		children: [
		new Element('div#topbar',{
			children:[ 
				new Element('h4',{text:'findFile'}),
				new Element('input#find',{
					events:{
						keyup:function(e){
							if (e.key=='enter') $('submit').fireEvent('click');
						},
						keydown:function(e){
							if (e.shift && e.key=='backspace') this.value = '';
						}
					}
				}),
				new Element('button#submit',{
					text:'search',
					events: {
						click: function(){
							if ( $('find').get('value').trim()!=="" )
							getElements('findFile¤¤¤'+$('find').get('value'),1);
							return false;
						}
					}
				})
			]
		}),
		new Element('div#scrolling',{
			children:[ new Element('div#content') ]
		}),
		new Element('div',{id:'status'})
		]
	}).inject(document.body);

	scrollingMove 	=	new Fx.Scroll('scrolling',{transition:'quad:in:out'});
	getElements('.',0);
	window.addEvent('resize',resizeViewport);

});
