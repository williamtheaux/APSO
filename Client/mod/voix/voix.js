/*
 * @version 0.5.0
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package	voix.voix.js
 */

(function($, undefined) {

	$.extend( {
	
		voix: {
			
			/**
			 * Funct setup.
			 */
			setup: function() {
				
				// Load model.
				$.m.load('voix', function() {
					
					// event. login. listen
					$('#'+$.m.div.event).on('login', function() {
						
						// if sound is on.
						if($.m.voix.is) $.voix.init();
					});
					
					// event. logout. listen
					$('#'+$.m.div.event).on('logout', function() {
						
						// if sound is on.
						if($.m.voix.is) $.voix.init();
					});
				});
				
				// event. setup. listen one
				$('#'+$.m.div.event).one($.m.event.setup, $.voix.defautHtml);
			},
			
			/**
			 * Funct defautHtml. Menu brique in dock.
			 */
			defautHtml: function() {
				
				// Load tmpl.
				$.tmpl.load('voix', function() {
					
					// look Cookie.
					var cook = $.cookie('voixSound');
					
					// if cookie.
					if(cook!=undefined && cook==1) {
						
						// edit model.
						$.m.voix.is = true;
					}
					
					// Init plugin.
					$.voix.init();
					
					// add tmpl.
					$('#'+$.m.div.menu).mustache('mVoix', $.m, {method:'prepend'});
					
					// Tooltip.
					$('#mVoix button').tooltip();
				});
			},
			
			/**
			 * Funct init.
			 */
			init: function() {
				
				// if sound is on.
				if($.m.voix.is) {
					
					// if not load sound.
					if(!$.m.voix.load.sound) {
						
						// Load sound for api.
						$.ionSound({
							sounds: $.m.voix.sound.list,
							path: $.m.voix.sound.url,
							multiPlay: true
						});
						
						// sound load on.
						$.m.voix.load.sound = true;
					}
					
					// Add hover event btn sound.
					setTimeout(function() { $.voix.btnSound(); }, 3000);
				}
			},
			
			/**
			 * Funct play.
			 * @param str -> sound name
			 */
			play: function(str) {
				
				// if sound is on.
				if($.m.voix.is) {
					
					// Play sound.
					$.ionSound.play(str);
				}
			},
			
			/**
			 * Funct toggle. 
			 */
			toggle: function() {
				
				// if toggle is on.
				if($.m.voix.is) {
						
					// off toogle.
					$.m.voix.is=false;
					
					// New cookie.
					$.cookie('voixSound', 0);
					
					// Change btn color.
					$('#mSound').removeClass('btn-success').addClass('btn-success');
					
					// Change btn icon.
					$('#mSound i').removeClass('fa-volume-up').addClass('fa-volume-off');
				}
				
				// If off
				else {
					
					// on toogle.
					$.m.voix.is=true;
					
					// Init plugin.
					$.voix.init();
					
					// New cookie.
					$.cookie('voixSound', 1);
					
					// Play sound.
					$.voix.play($.m.voix.sound.setup);
					
					// Change btn color.
					$('#mSound').removeClass('btn-success').addClass('btn-success');
					
					// Change btn icon.
					$('#mSound i').removeClass('fa-volume-off').addClass('fa-volume-up');
				}
			},
			
			/**
			 * Funct btnSound.
			 */
			btnSound: function() {
					
				// Boucle btn body.
				$('#'+$.m.div.menu+' .btn').each(function() {
					
					// hover btn
					$(this).mouseenter(function() {
						
						// Play sound.
						$.voix.play($.m.voix.sound.btnOver);
					});
				});
			},
		}
	});
	
})(jQuery);