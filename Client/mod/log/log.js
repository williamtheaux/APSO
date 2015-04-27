/**
 * @version 0.6.0
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package log.log.js
 */

(function($, undefined) {
	
	$.extend( {
		
		log: {
			
			/**
			 * Funct setup. Init mod home.
			 */
			setup: function() {
				
				// Load model.
				$.m.load('log');
				
				// Event. Tmpl mod.
				$('#'+$.m.div.event).one($.m.event.setup, $.log.defautHtml);
			},
			
			/**
			 * Funct defautHtml. Menu in dock.
			 */
			defautHtml: function() {
				
				// Load tmpl.
				$.tmpl.load('log', function () {
					
					// Event. Tmpl mod.
					$('#'+$.m.div.event).on('login', $.log.loginHTML);
					
					// Event. Tmpl mod.
					$('#'+$.m.div.event).on('logout', $.log.logoutHTML);
				});
			},
			
			/**
			 * Funct loginHTML.
			 */
			loginHTML: function() {
				
				// add tmpl.
				$('#'+$.m.div.menu).mustache('mLog', $.m, {method:'prepend'});
				
				// Tooltip.
				$('#mLog button').tooltip();
			},
			
			/**
			 * Funct logoutHTML.
			 */
			logoutHTML: function() {
				
				// delete menu user.
				$('#mLog').remove();
			},
			
			/**
			 * Funct home.
			 */
			home: function() {
				
				// If btc adress exist
				if($.m.user.wallet.adr) {
					
					// Clean windows.
					$.tmpl.clean();
					
					// Anim complete.
					$('#'+$.m.div.content).fadeOut(300, function() {
						
						// add tmpl. DOC.
						$('#'+$.m.div.content).empty().mustache('homeLog', $.m);
						
						// Paginat tab news, send and hist.
						$('#myLogTab').paginateTable({ rowsPerPage: 11, pager: ".pagerMyLog" });
						
						// Anim complete.
						$('#'+$.m.div.content).fadeIn(300, function() {
							
							// Tooltip.
							$('#myLogTab i').tooltip();
							$('#myLogTab span').popover();
						});
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-ALREADY-NOT-CONNECTED');
			},
		}
	});
	
})(jQuery);