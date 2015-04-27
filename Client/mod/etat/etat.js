/**
 * @version 0.6.0
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package etat.etat.js
 */

(function($, undefined) {
	
	$.extend( {
		
		etat: {
			
			/**
			 * Funct setup. Init mod home.
			 */
			setup: function() {
				
				// Load model.
				$.m.load('etat');
				
				// Event. Tmpl mod.
				$('#'+$.m.div.event).one($.m.event.setup, $.etat.defautHtml);
			},
			
			/**
			 * Funct defautHtml. Menu in dock.
			 */
			defautHtml: function() {
				
				// Load tmpl.
				$.tmpl.load('etat', function () {
					
					// Event. Tmpl mod.
					$('#'+$.m.div.event).on('login', $.etat.loginHTML);
					
					// Event. Tmpl mod.
					$('#'+$.m.div.event).on('logout', $.etat.logoutHTML);
				});
			},
			
			/**
			 * Funct loginHTML.
			 */
			loginHTML: function() {
				
				// add tmpl.
				$('#'+$.m.div.menu).mustache('mEtat', $.m, {method:'prepend'});
				
				// Tooltip.
				$('#mEtat button').tooltip();
			},
			
			/**
			 * Funct logoutHTML.
			 */
			logoutHTML: function() {
				
				// delete menu user.
				$('#mEtat').remove();
			},
			
			/**
			 * Funct home.
			 */
			home: function() {
				
				// If btc adress exist
				if($.m.user.wallet.adr) {
					
					// Add liste to model.
					$.m.etat.printList = $.m.user.wallet.obs.CITOYEN.list;
					
					// Clean windows.
					$.tmpl.clean();
					
					// Anim complete.
					$('#'+$.m.div.content).fadeOut(300, function() {
						
						// add tmpl. DOC.
						$('#'+$.m.div.content).empty().mustache('homeEtat', $.m);
						
						// Paginat tab news, send and hist.
						$('#myUserTab').paginateTable({ rowsPerPage: 7, pager: ".pagerMyUser" });
						
						// Anim complete.
						$('#'+$.m.div.content).fadeIn(300, function() {
							
							// Caroussel.
							$("#owl-head").owlCarousel({
								items				: 4, //10 items above 1000px browser width
								itemsDesktop		: [1000,4], //5 items between 1000px and 901px
								itemsDesktopSmall	: [900,3], // betweem 900px and 601px
								itemsTablet			: [600,2], //2 items between 600 and 0
								itemsMobile			: false, // itemsMobile disabled - inherit from itemsTablet option
								autoPlay			: false
							});
							
							// Tooltip.
							$('.toolHome').tooltip();
							
							// If citoyen.
							if($.m.user.wallet.citoyen) {
								
								// Valid Form.
								$('#formVotePoste').validate();
								
								// event. Form send. listen.
								$('#formVotePoste').on('formVotePoste', $.etat.sendVote);
							}
						});
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-ALREADY-NOT-CONNECTED');
			},
			
			/**
			 * Funct sendVote.
			 */
			sendVote: function(param) {
				
				// If btc adress exist
				if($.m.user.wallet.citoyen) {
					
					// connexion serveur.
					$.jsonRPC.request('vote_sending', {
						
						// Param send.
						params : [$.m.user.wallet.adr, $('#formVotePoste #userVote').val(), $('#formVotePoste #posteVote').val(), 'CTN'],
						
						// succees.
						success : function(data) {
							
							// Enregistrait le hash et l'id du vote dans la variable $.m.lois.vote.
							$.m.etat.vote = data.result;
							
							// Clean windows.
							$.tmpl.clean();
							
							// Anim complete.
							$('#'+$.m.div.content).fadeOut(300, function() {
								
								// add tmpl. DOC.
								$('#'+$.m.div.content).empty().mustache('fixVotePosteHTML', $.m);
								
								// Anim complete.
								$('#'+$.m.div.content).fadeIn(300, function() {
									
									// Valid Form.
									$('#formFixVotePoste').validate();
									
									// event. Form send. listen.
									$('#formFixVotePoste').on('formFixVotePoste', $.etat.FixVote);
								});
							});
						},
						
						// erreur serveur.
						error : function(data) {
							
							// erreur.
							$.tmpl.error(data.error);
						}
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-USER-NOT-ACCESS');
			},
			
			/**
			 * Funct FixVote.
			 */
			FixVote: function() {
				
				// If btc adress exist.
				if($.m.user.wallet.citoyen) {
					
					// disabled btn connect.
					$('.btnVotePoste').attr("disabled", "disabled");
					
					// Cree hash of pass phrase.
					var hash = Crypto.util.hexToBytes($.crp.decrypte($.m.user.wallet.hash, $.sha1($('#formFixVotePoste #password').val())));
					
					// Private Key+Address
					var sec = new Bitcoin.ECKey(hash);
					
					// Add address bitcoin.
					var adr = ''+sec.getBitcoinAddress();
					
					// Get private key.
					var key = ''+sec.getExportedPrivateKey();
					
					// Compresse private key.
					var payload = Bitcoin.Base58.decode(key);
					var compressed = payload.length == 38;
					
					// Signer le message.
					var signCr = $.btc.sign_message(sec, $.m.etat.vote.hash, compressed);
					
					// connexion serveur.
					$.jsonRPC.request('vote_fix', {
						
						// Param send.
						params : [adr, $.m.etat.vote.id, signCr],
						
						// succees.
						success : function(data) {
							
							// Return server.
							$.m.user.wallet.log = data.result.log;
							$.m.user.wallet.obs = data.result.obs;
							
							// Destruct var.
							sec = '';
							
							// Lancer un message dans la console.
							$.tmpl.msg('DEF-CONFIRM-VOTE');
							
							// Setup html.
							$.etat.home();
						},
						
						// erreur serveur.
						error : function(data) {
							
							// remove atr disabled on btn connect.
							$('.btnVotePoste').removeAttr("disabled");
							
							// Destruct var.
							sec = '';
							
							// erreur.
							$.tmpl.error(data.error);
						}
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-USER-NOT-ACCESS');
			},
			
			/**
			 * Funct addPosteHTML.
			 */
			addPosteHTML: function() {
				
				// If btc adress exist
				if($.m.user.wallet.admin.addPoste) {
					
					// Clean windows.
					$.tmpl.clean();
					
					// Anim complete.
					$('#'+$.m.div.content).fadeOut(300, function() {
						
						// add tmpl. DOC.
						$('#'+$.m.div.content).empty().mustache('addPosteHTML', $.m);
						
						// Anim complete.
						$('#'+$.m.div.content).fadeIn(300, function() {
							
							// Valid Form.
							$('#formAddPoste').validate();
							
							// event. Form send. listen.
							$('#formAddPoste').on('formAddPoste', $.etat.addPosteFUNC);
						});
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-USER-NOT-ACCESS');
			},
			
			/**
			 * Funct addPosteFUNC.
			 */
			addPosteFUNC: function() {
				
				// If btc adress exist.
				if($.m.user.wallet.admin.addPoste) {
					
					// disabled btn connect.
					$('.btnAddPoste').attr("disabled", "disabled");
					
					// Cree hash of pass phrase.
					var hash = Crypto.util.hexToBytes($.crp.decrypte($.m.user.wallet.hash, $.sha1($('#formAddPoste #password').val())));
					
					// Private Key+Address
					var sec = new Bitcoin.ECKey(hash);
					
					// Add address bitcoin.
					var adr = ''+sec.getBitcoinAddress();
					
					// Get private key.
					var key = ''+sec.getExportedPrivateKey();
					
					// Var for sign.
					var signBef = $.sha1($('#formAddPoste #loi').val()+adr)
					
					// Compresse private key.
					var payload = Bitcoin.Base58.decode(key);
					var compressed = payload.length == 38;
					
					// Signer le message.
					var signCr = $.btc.sign_message(sec, signBef, compressed);
					
					// connexion serveur.
					$.jsonRPC.request('etat_addPoste', {
						
						// Param send.
						params : [adr, $('#formAddPoste #loi').val(), signCr],
						
						// succees.
						success : function(data) {
							
							// Incrémenter le nombre de lois et log.
							$.m.user.wallet.obs.postes.nb ++;
							$.m.user.wallet.log.nb ++;
							
							// Ajouter au tableau les infos retourner.
							$.m.user.wallet.obs.postes.list.push(data.result.poste);
							$.m.user.wallet.log.list.unshift(data.result.log);
							
							// Destruct var.
							sec = '';
							
							// Lancer un message dans la console.
							$.tmpl.msg('ETAT-ADD-POSTE-SUCCES-DESC');
							
							// Setup html.
							$.etat.home();
						},
						
						// erreur serveur.
						error : function(data) {
							
							// remove atr disabled on btn connect.
							$('.btnAddPoste').removeAttr("disabled");
							
							// Destruct var.
							sec = '';
							
							// erreur.
							$.tmpl.error(data.error);
						}
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-USER-NOT-ACCESS');
			},
			
			/**
			 * Funct deletePosteHTML.
			 */
			deletePosteHTML: function(param) {
				
				// If btc adress exist
				if($.m.user.wallet.admin.deletePoste) {
					
					// Lancer une boucle pour récupérer le poste demander.
					$.each($.m.user.wallet.obs.postes.list, function(k, v) {
						
						// Si le id corespend au poste.
						if(v.id == param) {
							
							// Enregistrait dans la variable $.m.etat.poste.
							$.m.etat.poste = v;
							return false;
						}
					});
					
					// Clean windows.
					$.tmpl.clean();
					
					// Anim complete.
					$('#'+$.m.div.content).fadeOut(300, function() {
						
						// add tmpl. DOC.
						$('#'+$.m.div.content).empty().mustache('deletePosteHTML', $.m);
						
						// Anim complete.
						$('#'+$.m.div.content).fadeIn(300, function() {
							
							// Valid Form.
							$('#formDeletePoste').validate();
							
							// event. Form send. listen.
							$('#formDeletePoste').on('formDeletePoste', $.etat.deletePosteFUNC);
						});
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-USER-NOT-ACCESS');
			},
			
			/**
			 * Funct deletePosteFUNC.
			 */
			deletePosteFUNC: function() {
				
				// If btc adress exist.
				if($.m.user.wallet.admin.deletePoste) {
					
					// disabled btn connect.
					$('.btnDeletePoste').attr("disabled", "disabled");
					
					// Cree hash of pass phrase.
					var hash = Crypto.util.hexToBytes($.crp.decrypte($.m.user.wallet.hash, $.sha1($('#formDeletePoste #password').val())));
					
					// Private Key+Address
					var sec = new Bitcoin.ECKey(hash);
					
					// Add address bitcoin.
					var adr = ''+sec.getBitcoinAddress();
					
					// Get private key.
					var key = ''+sec.getExportedPrivateKey();
					
					// Var for sign.
					var signBef = $.sha1($.m.etat.poste.id+adr)
					
					// Compresse private key.
					var payload = Bitcoin.Base58.decode(key);
					var compressed = payload.length == 38;
					
					// Signer le message.
					var signCr = $.btc.sign_message(sec, signBef, compressed);
					
					// connexion serveur.
					$.jsonRPC.request('etat_deletePoste', {
						
						// Param send.
						params : [adr, $.m.etat.poste.id, signCr],
						
						// succees.
						success : function(data) {
							
							// Lancer une boucle pour récupérer le poste demander.
							$.each($.m.user.wallet.obs.postes.list, function(k, v) {
								
								// Si le id corespend au poste.
								if(v.id == data.result.poste) {
									
									// delete au tableau les infos retourner.
									$.m.user.wallet.obs.postes.list.splice(k, 1);
									return false;
								}
							});
							
							// Incrémenter le nombre de poste.
							$.m.user.wallet.obs.postes.nb --;
							
							// Incrémenter le nombre de log.
							$.m.user.wallet.log.nb ++;
							
							// Ajouter au tableau les infos retourner.
							$.m.user.wallet.log.list.unshift(data.result.log);
							
							// Destruct var.
							sec = '';
							
							// Lancer un message dans la console.
							$.tmpl.msg('ETAT-DELETE-POSTE-MSG-DESC');
							
							// Setup html.
							$.etat.home();
						},
						
						// erreur serveur.
						error : function(data) {
							
							// remove atr disabled on btn connect.
							$('.btnDeletePoste').removeAttr("disabled");
							
							// Destruct var.
							sec = '';
							
							// erreur.
							$.tmpl.error(data.error);
						}
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-USER-NOT-ACCESS');
			},
			
			/**
			 * Funct listUser.
			 */
			listUser: function(param) {
				
				// Add liste to model.
				$.m.etat.printList = $.m.user.wallet.obs[param].list;
					
				// Clean windows.
				$('#listTile').text($.lng.tr(param+'S'));
					
				// Anim complete.
				$('#liteUser').fadeOut(300, function() {
						
					// add tmpl. DOC.
					$('#liteUser').empty().mustache('listeUserPart', $.m);
					
					// Paginat tab news, send and hist.
					$('#myUserTab').paginateTable({ rowsPerPage: 7, pager: ".pagerMyUser" });
					
					// Anim complete.
					$('#liteUser').fadeIn(300, function() {
						
						// Tooltip.
						$('.toolHome').tooltip();
					});
				});
			},
			
			/**
			 * Funct editeRoleHTML.
			 */
			editeRoleHTML: function(param) {
				
				// If btc adress exist
				if($.m.user.wallet.admin.editeRole) {
					
					// Lancer une boucle pour récupérer le poste demander.
					$.each($.m.etat.printList, function(k, v) {
						
						// Si le id corespend au poste.
						if(v.id == param) {
							
							// Enregistrait dans la variable $.m.etat.poste.
							$.m.etat.user = v;
							return false;
						}
					});
					
					// Si le role_user est ADMIN.
					if($.m.etat.user.role == 'ADMIN') {
						
						// Lever une exception.
						$.tmpl.error('ERR-NOT-CHANGE-ADMIN');
					}
					
					// Si le id est user id.
					else if($.m.etat.user.id == $.m.user.wallet.info.id) {
						
						// Lever une exception.
						$.tmpl.error('ETAT-NOT-EDIT-YOUR-ROLE');
					}
					
					else {
						
						// Clean windows.
						$.tmpl.clean();
						
						// Anim complete.
						$('#'+$.m.div.content).fadeOut(300, function() {
							
							// add tmpl. DOC.
							$('#'+$.m.div.content).empty().mustache('editeRoleHTML', $.m);
							
							// Anim complete.
							$('#'+$.m.div.content).fadeIn(300, function() {
								
								// Valid Form.
								$('#formEditeRole').validate();
								
								// event. Form send. listen.
								$('#formEditeRole').on('formEditeRole', $.etat.editeRoleFUNC);
							});
						});
					}
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-USER-NOT-ACCESS');
			},
			
			/**
			 * Funct editeRoleFUNC.
			 */
			editeRoleFUNC: function() {
				
				// If btc adress exist.
				if($.m.user.wallet.admin.editeRole) {
					
					// disabled btn connect.
					$('.btnEditeRole').attr("disabled", "disabled");
					
					// Cree hash of pass phrase.
					var hash = Crypto.util.hexToBytes($.crp.decrypte($.m.user.wallet.hash, $.sha1($('#formEditeRole #password').val())));
					
					// Private Key+Address
					var sec = new Bitcoin.ECKey(hash);
					
					// Add address bitcoin.
					var adr = ''+sec.getBitcoinAddress();
					
					// Get private key.
					var key = ''+sec.getExportedPrivateKey();
					
					// Var for sign.
					var signBef = $.sha1($('#formEditeRole #role').val()+$.m.etat.user.id+adr)
					
					// Compresse private key.
					var payload = Bitcoin.Base58.decode(key);
					var compressed = payload.length == 38;
					
					// Signer le message.
					var signCr = $.btc.sign_message(sec, signBef, compressed);
					
					// connexion serveur.
					$.jsonRPC.request('etat_editeRole', {
						
						// Param send.
						params : [adr, $('#formEditeRole #role').val(), $.m.etat.user.id, signCr],
						
						// succees.
						success : function(data) {
							
							// Return server.
							$.m.user.wallet.log = data.result.log;
							$.m.user.wallet.obs = data.result.obs;
							
							// Destruct var.
							sec = '';
							
							// Lancer un message dans la console.
							$.tmpl.msg('ETAT-SUCC-ROLE-DESC');
							
							// Setup html.
							$.etat.home();
						},
						
						// erreur serveur.
						error : function(data) {
							
							// remove atr disabled on btn connect.
							$('.btnEditeRole').removeAttr("disabled");
							
							// Destruct var.
							sec = '';
							
							// erreur.
							$.tmpl.error(data.error);
						}
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-USER-NOT-ACCESS');
			},
		}
	});
	
})(jQuery);