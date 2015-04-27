/**
 * @version 0.5.0
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package boot.js
 */

$(function() {
	
	// Load model.
	$.getJSON( "js/boot.json", function(e) {
		
		// Setup model.
		$.extend({m : e});
		
		// Init le JSON RPC url & NameSpace.
		$.jsonRPC.setup({ endPoint : $.m.api, namespace : '' });
		
		// initialization establishes path and the base translate.
		$.linguaInit($.m.lng.url, $.m.lng.file);
		
		// try loading the default language.
		$.linguaLoad($.lng.get());
		
		// Langue actu for date
		moment.lang($.lng.get());
		
		//Gestion notification. not add buton if erreur.
		$.jGrowl.defaults.closerTemplate = '';
		
		// New func load json. @param 1-name json file 2-callback function.
		$.m.load = function(e, callback) {
			
			// Load model.
			$.getJSON( $.m.plug.url+e+'/'+e+'.json', function(data) {
				
				// add loading model.
				$.m[e]=data;
				
				// If call back.
				if(callback) callback();
			});
		}
		
		// Extend model : Func translate return html.
		$.m.T = function(){ return function(text, render){ return $.lng.tr(render(text), true)}};
		
		// Extend model : Func translate return text.
		$.m.TX = function(){ return function(text, render){ return $.lng.tr(render(text))}};
		
		// Extend model : Func date return from now.
		$.m.FROMNOW = function(){ return function(text, render){ return moment(render(text), 'X').fromNow()}};
		
		// Extend model : Func date return LLLL.
		$.m.LLLL = function(){ return function(text, render){ return moment(render(text), 'X').format('LLLL')}};
		
		// Extend model : Func date return YMD.
		$.m.YMD = function(){ return function(text, render){ return moment(render(text), 'X').format('YYYY/MM/DD')}};
		
		// Extend model : Func date return H.
		$.m.H = function(){ return function(text, render){ return moment(render(text), 'X').format('HH:mm')}};
		
		// Setup plugin.
		$.each($.m.plug.list, function(key, val) {
			
			//setup module.
			$[val].setup();
		});
	});
});