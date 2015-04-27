/**
 * @version 0.6.0
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package user.user.js
 */

(function($, undefined) {
	
	$.extend( {
		
		user: {
			
			/**
			 * Funct setup. Init mod home.
			 */
			setup: function() {
				
				// Load model.
				$.m.load('user');
				
				// Event. Tmpl mod.
				$('#'+$.m.div.event).one($.m.event.setup, $.user.defautHtml);
			},
			
			/**
			 * Funct defautHtml. Menu in dock.
			 */
			defautHtml: function() {
				
				// Load tmpl.
				$.tmpl.load('user', function () {
					
					// Wallet var.
					$.m.user.wallet = {};
					
					// add tmpl.
					$('#'+$.m.div.menu).mustache('mUser', $.m, {method:'prepend'});
					
					// Tooltip.
					$('#mUser button').tooltip();
					
					// Setup html.
					$.user.home();
				});
			},
			
			/**
			 * Funct home.
			 */
			home: function() {
				
				// Clean windows.
				$.tmpl.clean();
				
				// Anim complete.
				$('#'+$.m.div.content).fadeOut(300, function() {
					
					// If btc adress exist
					if($.m.user.wallet.adr) {
						
						// Vérifier la presence de $.m.user.wallet.info Si non afficher HTML sign.
						if($.m.user.wallet.info) {
							
							// Vérifier l'absence de $.m.user.wallet.guest Si non afficher HTML validation.
							if($.m.user.wallet.guest) {
								
								$('#'+$.m.div.content).empty().mustache('validation', $.m);
								
								// Anim complete.
								$('#'+$.m.div.content).fadeIn(300, function() {
									
								});
							}
							
							// Vérifier l'absence de $.m.user.wallet.banni Si non afficher HTML bannissement.
							else if($.m.user.wallet.banni) {
								
								$('#'+$.m.div.content).empty().mustache('bannissement', $.m);
								
								// Anim complete.
								$('#'+$.m.div.content).fadeIn(300, function() {
									
								});
							}
							
							// afficher HTML compte.
							else {
								
								// add tmpl. DOC.
								$('#'+$.m.div.content).empty().mustache('compte', $.m);
								
								// Anim complete.
								$('#'+$.m.div.content).fadeIn(300, function() {
									
									// Tooltip.
									$('.userTooltip').tooltip();
								});
							}
						}
						
						// Pas d'info d'utilisateur.
						else {
							
							// add tmpl. DOC.
							$('#'+$.m.div.content).empty().mustache('sign', $.m);
							
							// Anim complete.
							$('#'+$.m.div.content).fadeIn(300, function() {
								
								// Valid Form.
								$('#formSignUser').validate();
								
								// event. Form send. listen.
								$('#formSignUser').on('formSignUser', $.user.signUpFUNC);
							});
						}
					}
					
					// Else not btc adress
					else {
						
						// add tmpl. DOC.
						$('#'+$.m.div.content).empty().mustache('home', $.m);
						
						// Anim complete.
						$('#'+$.m.div.content).fadeIn(300, function() {
							
							// Caroussel.
							$("#owl-head").owlCarousel({
								items				: 1, //10 items above 1000px browser width
								itemsDesktop		: [1000,1], //5 items between 1000px and 901px
								itemsDesktopSmall	: [900,1], // betweem 900px and 601px
								itemsTablet			: [600,1], //2 items between 600 and 0
								itemsMobile			: false, // itemsMobile disabled - inherit from itemsTablet option
								autoPlay			: 15000
							});
							
							// Valid Form.
							$('#formLogin').validate();
							
							// event. Form send. listen.
							$('#formLogin').on('formLogin', $.user.sendLogin);
							
							// Tooltip.
							$('#conten i').tooltip();
						});
					}
				});
			},
			
			/**
			 * Funct signUpFUNC.
			 */
			signUpFUNC: function() {
				
				// If btc adress exist.
				if($.m.user.wallet.adr) {
					
					// disabled btn connect.
					$('.btnSingUp').attr("disabled", "disabled");
					
					// Cree hash of pass phrase.
					var hash = Crypto.util.hexToBytes($.crp.decrypte($.m.user.wallet.hash, $.sha1($('#formSignUser #password').val())));
					
					// Private Key+Address
					var sec = new Bitcoin.ECKey(hash);
					
					// Add address bitcoin.
					var adr = ''+sec.getBitcoinAddress();
					
					// Get private key.
					var key = ''+sec.getExportedPrivateKey();
					
					// Var for sign.
					var signBef = $.sha1($('#formSignUser #nom').val()+$('#formSignUser #prenom').val()+adr)
					
					// Compresse private key.
					var payload = Bitcoin.Base58.decode(key);
					var compressed = payload.length == 38;
					
					// Signer le message.
					var signCr = $.btc.sign_message(sec, signBef, compressed);
					
					// connexion serveur.
					$.jsonRPC.request('user_sign', {
						
						// Param send.
						params : [adr, $('#formSignUser #nom').val(), $('#formSignUser #prenom').val(), signCr],
						
						// succees.
						success : function(data) {
							
							// Return server.
							$.m.user.wallet.info = data.result;
							
							// L'utilisateur n'est pas encore validé.
							$.m.user.wallet.guest = 1;
							
							// Destruct var.
							sec = '';
							
							// Play sound.
							$.voix.play($.m.tmpl.sound.click);
							
							// Setup html.
							$.user.home();
						},
						
						// erreur serveur.
						error : function(data) {
							
							// remove atr disabled on btn connect.
							$('.btnSingUp').removeAttr("disabled");
							
							// Destruct var.
							sec = '';
							
							// erreur.
							$.tmpl.error(data.error);
						}
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-ALREADY-NOT-CONNECTED');
			},
			
			/**
			 * Funct signHTML.
			 */
			signHTML: function() {
				
				// If btc adress exist
				if($.m.user.wallet.adr) {
					
					// Clean windows.
					$.tmpl.clean();
					
					// Anim complete.
					$('#'+$.m.div.content).fadeOut(300, function() {
						
						// add tmpl. DOC.
						$('#'+$.m.div.content).empty().mustache('signHTML', $.m);
						
						// Anim complete.
						$('#'+$.m.div.content).fadeIn(300, function() {
							
							// Valid Form.
							$('#formSignMess').validate();
							
							// event. Form send. listen.
							$('#formSignMess').on('formSignMess', $.user.signFUNC);
						});
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-ALREADY-NOT-CONNECTED');
			},
			
			/**
			 * Funct signFUNC.
			 */
			signFUNC: function() {
				
				// If btc adress exist.
				if($.m.user.wallet.adr) {
					
					// Cree hash of pass phrase.
					var hash = Crypto.util.hexToBytes($.crp.decrypte($.m.user.wallet.hash, $.sha1($('#formSignMess #password').val())));
					
					// Private Key+Address
					var sec = new Bitcoin.ECKey(hash);
					var adr = ''+sec.getBitcoinAddress();
					var key = ''+sec.getExportedPrivateKey();
					var payload = Bitcoin.Base58.decode(key);
					var compressed = payload.length == 38;
					
					// If good adr bitcoin.
					if($.m.user.wallet.adr == adr) {
						
						// Message
						$.m.user.wallet.mess = $('#formSignMess #mess').val();
						
						// Signature of message
						$.m.user.wallet.sign = $.btc.sign_message(sec, $.m.user.wallet.mess, compressed);
						
						// If good adr bitcoin.
						if($.m.user.wallet.sign) {
							
							// Add key to html.
							$('#signMess').empty().mustache('goodSignPart', $.m);
							
							// Play sound.
							$.voix.play($.m.tmpl.sound.click);
							
							// Destruct var.
							sec = '';
						}
						
						// Else not sign
						else {
							
							// Destruct var.
							sec = '';
							
							// Error.
							$.tmpl.error('USER-SIGN-MESS-ERR');
						}
					}
					
					// If not btcAdr.
					else $.tmpl.error('ERR-BTC-ADR-INVALID');
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-ALREADY-NOT-CONNECTED');
			},
			
			/**
			 * Funct verifHTML.
			 */
			verifHTML: function() {
					
				// Clean windows.
				$.tmpl.clean();
				
				// Anim complete.
				$('#'+$.m.div.content).fadeOut(300, function() {
					
					// add tmpl. DOC.
					$('#'+$.m.div.content).empty().mustache('verifHTML', $.m);
					
					// Anim complete.
					$('#'+$.m.div.content).fadeIn(300, function() {
						
						// Valid Form.
						$('#formVerif').validate();
						
						// event. Form send. listen.
						$('#formVerif').on('formVerif', $.user.verifFUNC);
					});
				});
			},
			
			/**
			 * Funct verifFUNC.
			 */
			verifFUNC: function() {
						
				// If good adr bitcoin on sign.
				if($.btc.verify_message($('#formVerif #sign').val(), $('#formVerif #mess').val())==$('#formVerif #btcadr').val()) {
					
					// login body for modal.
					$('#verifMess').empty().mustache('valideSignPart', $.m);
					
					// Play sound.
					$.voix.play($.m.tmpl.sound.click);
					
					setTimeout(function() {
						
						// html add defaut Partials.
						$('#verifMess').empty().mustache('verifDefautPart', $.m);
					}, 3000);
				}
				
				// If not btcAdr.
				else $.tmpl.error('USER-NOT-SIGN-VALIDE');
			},
			
			/**
			 * Funct sendLogin.
			 */
			sendLogin: function() {
				
				// If btc adress exist.
				if(!$.m.user.wallet.adr) {
					
					// disabled btn connect.
					$('.btnSync').attr("disabled", "disabled");
					
					// Cree hash of pass phrase.
					var hash = Crypto.SHA256($('#formLogin #passPhrase').val(), { asBytes: true });
					
					// Private Key+Address
					var sec = new Bitcoin.ECKey(hash);
					
					// Add address bitcoin.
					var adr = ''+sec.getBitcoinAddress();
					
					// Get private key.
					var key = ''+sec.getExportedPrivateKey();
					
					// Timestamp.
					var time = moment().format('X');
					
					// Var for sign.
					var signBef = $.sha1(time+adr);
					
					// Compresse private key.
					var payload = Bitcoin.Base58.decode(key);
					var compressed = payload.length == 38;
					
					// Signer le message.
					var signCr = $.btc.sign_message(sec, signBef, compressed);
					
					// connexion serveur.
					$.jsonRPC.request('user_login', {
						
						// Param send.
						params : [adr, time, signCr],
						
						// succees.
						success : function(data) {
							
							// Return server.
							$.m.user.wallet = data.result;
							
							// Add address bitcoin.
							$.m.user.wallet.adr = adr;
							
							// Hash pass phrase bitcoin.
							$.m.user.wallet.hash = $.crp.crypte(Crypto.util.bytesToHex(hash), $.sha1($('#formLogin #password').val()));
							
							// signe secu hash.
							$.m.user.wallet.secuSigne = $.btc.sign_message(sec, $.sha1($.m.user.wallet.secu+adr), compressed);
							
							// sync time.
							$.m.user.wallet.syncTime = time;
							
							// Destruct var.
							sec = '';
							
							// Vérifier l'absence de $.m.user.wallet.guest et $.m.user.wallet.banni.
							if(!$.m.user.wallet.banni && !$.m.user.wallet.guest  && $.m.user.wallet.info) {
								
								// Envoyer l'évènement login.
								$('#'+$.m.div.event).trigger('login');
								
								// Init syncro api.
								$.m.user.wallet.interval = setInterval($.user.syncApi,60000);
							}
							
							// Setup html.
							$.user.home();
							
							// add btn logout to menu.
							$('#mUser').mustache('logoutBtnPart', $.m);
							
							// Tooltip.
							$('#mUser button').tooltip();
						},
						
						// erreur serveur.
						error : function(data) {
							
							// remove atr disabled on btn connect.
							$('.btnSync').removeAttr("disabled");
							
							// Destruct var.
							sec = '';
							
							// erreur.
							$.tmpl.error(data.error);
						}
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-ALREADY-CONNECTED');
			},
			
			/**
			 * Funct sendLogout.
			 */
			sendLogout: function() {
				
				// If btc adress exist.
				if($.m.user.wallet.adr) {
					
					// Stop news.
					clearInterval($.m.user.wallet.interval);
					
					// Add address bitcoin.
					$.m.user.wallet = {};
					
					// Setup html.
					$.user.home();
					
					// event. login. trigger.
					$('#'+$.m.div.event).trigger('logout');
					
					// remove btn logout to menu.
					$('#mIbtcLogoutSpin').remove();
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-ALREADY-NOT-CONNECTED');
			},
			
			/**
			 * Funct syncBlockchain.
			 */
			syncApi: function() {
				
				// If btc adress exist.
				if($.m.user.wallet.adr) {
					
					// connexion serveur.
					$.jsonRPC.request('user_upData', {
						
						// Param send.
						params : [$.m.user.wallet.adr, $.m.user.wallet.log.nb, $.m.user.wallet.secu, $.m.user.wallet.secuSigne],
						
						// succees.
						success : function(data) {
							
							// If btc adress exist.
							if(data.result.upData) {
								
								//L'élément existe.
								if($('#nbAct').length) {
									
									// Edite nuber action.
									$('#nbAct').text(data.result.log.nb);
								}
								
								// Return server.
								$.m.user.wallet.log = data.result.log;
								$.m.user.wallet.obs = data.result.obs;
								$.m.user.wallet.admin = data.result.admin;
							}
							
							// sync time.
							$.m.user.wallet.syncTime = moment().format('X');
							
							//L'élément existe.
							if($('#syncTime').length) {
								
								// Edite time in dom and add class.
								$('#syncTime').text(moment($.m.user.wallet.syncTime, 'X').format('LLLL')).removeClass('label-success').addClass('label-warning');
								
								// Remove class after 4s.
								setTimeout(function () { 
									$('#syncTime').removeClass('label-warning').addClass('label-success');
								}, 4000);
							}
						},
						
						// erreur serveur.
						error : function(data) {
							
							// erreur.
							$.tmpl.error(data.error);
						}
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('IBTC-ALREADY-NOT-CONNECTED');
			},
		}
	});
	
})(jQuery);