/**
 * @version 0.6.0
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package lois.lois.js
 */

(function($, undefined) {
	
	$.extend( {
		
		lois: {
			
			/**
			 * Funct setup. Init mod home.
			 */
			setup: function() {
				
				// Load model.
				$.m.load('lois');
				
				// Event. Tmpl mod.
				$('#'+$.m.div.event).one($.m.event.setup, $.lois.defautHtml);
			},
			
			/**
			 * Funct defautHtml. Menu in dock.
			 */
			defautHtml: function() {
				
				// Load tmpl.
				$.tmpl.load('lois', function () {
					
					// Event. Tmpl mod.
					$('#'+$.m.div.event).on('login', $.lois.loginHTML);
					
					// Event. Tmpl mod.
					$('#'+$.m.div.event).on('logout', $.lois.logoutHTML);
				});
			},
			
			/**
			 * Funct loginHTML.
			 */
			loginHTML: function() {
				
				// add tmpl.
				$('#'+$.m.div.menu).mustache('mLois', $.m, {method:'prepend'});
				
				// Tooltip.
				$('#mLois button').tooltip();
			},
			
			/**
			 * Funct logoutHTML.
			 */
			logoutHTML: function() {
				
				// delete menu user.
				$('#mLois').remove();
			},
			
			/**
			 * Funct home.
			 */
			home: function() {
				
				// If btc adress exist
				if($.m.user.wallet.adr) {
					
					// Si les lois son present dans la liste.
					if($.m.user.wallet.obs.lois.nb) {
						
						// Si pas de type d'ordre alphabétique ou chronologique. Le type d'order par defaut, alphabétique
						if(!$.m.lois.order) $.m.lois.order = 'alphabetique';
						
						// Si order est alphabetique.
						if($.m.lois.order == 'alphabetique') {
							
							// Fonction de tri.
							function compareA(a,b) {
								if (a.loi < b.loi) return -1;
								if (a.loi > b.loi) return 1;
								else return 0;
							}
							
							// Tri des lois.	
							$.m.user.wallet.obs.lois.list.sort(compareA);
						}
						
						// Si order est chronologique.
						if($.m.lois.order == 'chronologique') {
							
							// Fonction de tri.
							function compareC(a,b) {
								if (a.id < b.id) return 1;
								if (a.id > b.id) return -1;
								else return 0;
							}
							
							// Tri des lois.	
							$.m.user.wallet.obs.lois.list.sort(compareC);
						}
					}
					
					// Clean windows.
					$.tmpl.clean();
					
					// Anim complete.
					$('#'+$.m.div.content).fadeOut(300, function() {
						
						// add tmpl. DOC.
						$('#'+$.m.div.content).empty().mustache('homeLois', $.m);
						
						// Paginat tab news, send and hist.
						$('#myLoisTab').paginateTable({ rowsPerPage: 5, pager: ".pagerMyLois" });
						
						// Anim complete.
						$('#'+$.m.div.content).fadeIn(300, function() {
							
							// Tooltip.
							$('.loiTooltip').tooltip();
						});
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-ALREADY-NOT-CONNECTED');
			},
			
			/**
			 * Funct sortLois.
			 */
			sortLois: function(param) {
				
				// Si order est alphabetique.
				if(param == 'alphabetique') {
					
					// Insc order for tri		
					$.m.lois.order = 'alphabetique';
				}
						
				// Si order est chronologique.
				if(param == 'chronologique') {
							
					// Insc order for tri		
					$.m.lois.order = 'chronologique';
				}
				
				// Setup html.
				$.lois.home();
			},
			
			/**
			 * Funct ficheLoisHTML.
			 */
			ficheLoisHTML: function(param) {
				
				// If btc adress exist
				if($.m.user.wallet.adr) {
					
					// Lancer une boucle pour récupérer la loi demander.
					$.each($.m.user.wallet.obs.lois.list, function(k, v) {
						
						// Si le id corespend a la loi.
						if(v.id == param) {
							
							// Enregistrait dans la variable $.m.lois.fiche.
							$.m.lois.fiche = v;
							return false;
						}
					});
					
					// Empty the lois var.
					$.m.lois.amdFiche = '';
					$.m.lois.vote = '';
					
					// Clean windows.
					$.tmpl.clean();
					
					// Anim complete.
					$('#'+$.m.div.content).fadeOut(300, function() {
						
						// add tmpl. DOC.
						$('#'+$.m.div.content).empty().mustache('loisFiche', $.m);
						
						// Paginat tab news, send and hist.
						$('#myAmdTab').paginateTable({ rowsPerPage: 4, pager: ".pagerMyAmd" });
						
						// Anim complete.
						$('#'+$.m.div.content).fadeIn(300, function() {
							
							// Tooltip.
							$('.loiTooltip').tooltip();
						});
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-ALREADY-NOT-CONNECTED');
			},
			
			/**
			 * Funct addLoisHTML.
			 */
			addLoisHTML: function() {
				
				// If btc adress exist
				if($.m.user.wallet.citoyen) {
					
					// Clean windows.
					$.tmpl.clean();
					
					// Anim complete.
					$('#'+$.m.div.content).fadeOut(300, function() {
						
						// add tmpl. DOC.
						$('#'+$.m.div.content).empty().mustache('addLoisHTML', $.m);
						
						// Anim complete.
						$('#'+$.m.div.content).fadeIn(300, function() {
							
							// Valid Form.
							$('#formAddLois').validate();
							
							// event. Form send. listen.
							$('#formAddLois').on('formAddLois', $.lois.addLoisFUNC);
						});
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-USER-NOT-ACCESS');
			},
			
			/**
			 * Funct addLoisFUNC.
			 */
			addLoisFUNC: function() {
				
				// If btc adress exist.
				if($.m.user.wallet.citoyen) {
					
					// disabled btn connect.
					$('.btnAddLoi').attr("disabled", "disabled");
					
					// Cree hash of pass phrase.
					var hash = Crypto.util.hexToBytes($.crp.decrypte($.m.user.wallet.hash, $.sha1($('#formAddLois #password').val())));
					
					// Private Key+Address
					var sec = new Bitcoin.ECKey(hash);
					
					// Add address bitcoin.
					var adr = ''+sec.getBitcoinAddress();
					
					// Get private key.
					var key = ''+sec.getExportedPrivateKey();
					
					// Var for sign.
					var signBef = $.sha1($('#formAddLois #loi').val()+$('#formAddLois #amd').val()+adr)
					
					// Compresse private key.
					var payload = Bitcoin.Base58.decode(key);
					var compressed = payload.length == 38;
					
					// Signer le message.
					var signCr = $.btc.sign_message(sec, signBef, compressed);
					
					// connexion serveur.
					$.jsonRPC.request('lois_addLois', {
						
						// Param send.
						params : [adr, $('#formAddLois #loi').val(), $('#formAddLois #amd').val(), signCr],
						
						// succees.
						success : function(data) {
							
							// Incrémenter le nombre de lois et log.
							$.m.user.wallet.obs.lois.nb ++;
							$.m.user.wallet.log.nb ++;
							
							// Ajouter au tableau les infos retourner.
							$.m.user.wallet.obs.lois.list.unshift(data.result.lois);
							$.m.user.wallet.log.list.unshift(data.result.log);
							
							// Destruct var.
							sec = '';
							
							// Lancer un message dans la console.
							$.tmpl.msg('LOIS-ADD-SUCCES-LABEL');
							
							// Setup html.
							$.lois.ficheLoisHTML(data.result.lois.id);
						},
						
						// erreur serveur.
						error : function(data) {
							
							// remove atr disabled on btn connect.
							$('.btnAddLoi').removeAttr("disabled");
							
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
			 * Funct addAmdHTML.
			 */
			addAmdHTML: function() {
				
				// If btc adress exist
				if($.m.user.wallet.citoyen) {
					
					// Clean windows.
					$.tmpl.clean();
					
					// Anim complete.
					$('#'+$.m.div.content).fadeOut(300, function() {
						
						// add tmpl. DOC.
						$('#'+$.m.div.content).empty().mustache('addAmdHTML', $.m);
						
						// Paginat tab news, send and hist.
						$('#myAmdTab').paginateTable({ rowsPerPage: 7, pager: ".pagerMyAmd" });
						
						// Anim complete.
						$('#'+$.m.div.content).fadeIn(300, function() {
							
							// Valid Form.
							$('#formAddAmd').validate();
							
							// event. Form send. listen.
							$('#formAddAmd').on('formAddAmd', $.lois.addAmdFUNC);
						});
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-USER-NOT-ACCESS');
			},
			
			/**
			 * Funct addAmdFUNC.
			 */
			addAmdFUNC: function() {
				
				// If btc adress exist.
				if($.m.user.wallet.citoyen) {
					
					// disabled btn connect.
					$('.btnAddAmd').attr("disabled", "disabled");
					
					// Cree hash of pass phrase.
					var hash = Crypto.util.hexToBytes($.crp.decrypte($.m.user.wallet.hash, $.sha1($('#formAddAmd #password').val())));
					
					// Private Key+Address
					var sec = new Bitcoin.ECKey(hash);
					
					// Add address bitcoin.
					var adr = ''+sec.getBitcoinAddress();
					
					// Get private key.
					var key = ''+sec.getExportedPrivateKey();
					
					// Var for sign.
					var signBef = $.sha1($.m.lois.fiche.id+$('#formAddAmd #amd').val()+adr)
					
					// Compresse private key.
					var payload = Bitcoin.Base58.decode(key);
					var compressed = payload.length == 38;
					
					// Signer le message.
					var signCr = $.btc.sign_message(sec, signBef, compressed);
					
					// connexion serveur.
					$.jsonRPC.request('lois_addAmd', {
						
						// Param send.
						params : [adr, $.m.lois.fiche.id, $('#formAddAmd #amd').val(), signCr],
						
						// succees.
						success : function(data) {
							
							// Lancer une boucle pour récupérer la loi demander.
							$.each($.m.user.wallet.obs.lois.list, function(k, v) {
								
								// Si le id corespend a la loi.
								if(v.id == data.result.id_loi) {
									
									// Incrémenter le nombre d'amd.
									$.m.user.wallet.obs.lois.list[k].nbAmd ++;
									
									// Ajouter au tableau les infos retourner.
									$.m.user.wallet.obs.lois.list[k].amd.unshift(data.result.amd);
									return false;
								}
							});
							
							// Incrémenter le nombre de log.
							$.m.user.wallet.log.nb ++;
							
							// Ajouter au tableau les infos retourner.
							$.m.user.wallet.log.list.unshift(data.result.log);
							
							// Destruct var.
							sec = '';
							
							// Lancer un message dans la console.
							$.tmpl.msg('LOIS-ADD-AMD-SUCCES-LABEL');
							
							// Setup html.
							$.lois.ficheLoisHTML(data.result.id_loi);
						},
						
						// erreur serveur.
						error : function(data) {
							
							// remove atr disabled on btn connect.
							$('.btnAddAmd').removeAttr("disabled");
							
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
			 * Funct editeLoisHTML.
			 */
			editeLoisHTML: function() {
				
				// If btc adress exist
				if($.m.user.wallet.admin.editeLois) {
					
					// Clean windows.
					$.tmpl.clean();
					
					// Anim complete.
					$('#'+$.m.div.content).fadeOut(300, function() {
						
						// add tmpl. DOC.
						$('#'+$.m.div.content).empty().mustache('editeLoisHTML', $.m);
						
						// Anim complete.
						$('#'+$.m.div.content).fadeIn(300, function() {
							
							// Valid Form.
							$('#formEditeLois').validate();
							
							// event. Form send. listen.
							$('#formEditeLois').on('formEditeLois', $.lois.editeLoisFUNC);
						});
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-USER-NOT-ACCESS');
			},
			
			/**
			 * Funct editeLoisFUNC.
			 */
			editeLoisFUNC: function() {
				
				// If btc adress exist.
				if($.m.user.wallet.admin.editeLois) {
					
					// disabled btn connect.
					$('.btnEditeLoi').attr("disabled", "disabled");
					
					// Cree hash of pass phrase.
					var hash = Crypto.util.hexToBytes($.crp.decrypte($.m.user.wallet.hash, $.sha1($('#formEditeLois #password').val())));
					
					// Private Key+Address
					var sec = new Bitcoin.ECKey(hash);
					
					// Add address bitcoin.
					var adr = ''+sec.getBitcoinAddress();
					
					// Get private key.
					var key = ''+sec.getExportedPrivateKey();
					
					// Var for sign.
					var signBef = $.sha1($('#formEditeLois #loi').val()+$.m.lois.fiche.id+adr)
					
					// Compresse private key.
					var payload = Bitcoin.Base58.decode(key);
					var compressed = payload.length == 38;
					
					// Signer le message.
					var signCr = $.btc.sign_message(sec, signBef, compressed);
					
					// connexion serveur.
					$.jsonRPC.request('lois_editeLois', {
						
						// Param send.
						params : [adr, $('#formEditeLois #loi').val(), $.m.lois.fiche.id, signCr],
						
						// succees.
						success : function(data) {
							
							// Lancer une boucle pour récupérer la loi demander.
							$.each($.m.user.wallet.obs.lois.list, function(k, v) {
								
								// Si le id corespend a la loi.
								if(v.id == data.result.id_loi) {
									
									// Ajouter au tableau les infos retourner.
									$.m.user.wallet.obs.lois.list[k].loi = data.result.loi;
									return false;
								}
							});
							
							// Incrémenter le nombre de log.
							$.m.user.wallet.log.nb ++;
							
							// Ajouter au tableau les infos retourner.
							$.m.user.wallet.log.list.unshift(data.result.log);
							
							// Destruct var.
							sec = '';
							
							// Lancer un message dans la console.
							$.tmpl.msg('LOIS-EDIT-SUCCES-LABEL');
							
							// Setup html.
							$.lois.ficheLoisHTML(data.result.id_loi);
						},
						
						// erreur serveur.
						error : function(data) {
							
							// remove atr disabled on btn connect.
							$('.btnEditeLoi').removeAttr("disabled");
							
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
			 * Funct deleteLoisHTML.
			 */
			deleteLoisHTML: function() {
				
				// If btc adress exist
				if($.m.user.wallet.admin.deleteLois) {
					
					// Clean windows.
					$.tmpl.clean();
					
					// Anim complete.
					$('#'+$.m.div.content).fadeOut(300, function() {
						
						// add tmpl. DOC.
						$('#'+$.m.div.content).empty().mustache('deleteLoisHTML', $.m);
						
						// Anim complete.
						$('#'+$.m.div.content).fadeIn(300, function() {
							
							// Valid Form.
							$('#formDeleteLois').validate();
							
							// event. Form send. listen.
							$('#formDeleteLois').on('formDeleteLois', $.lois.deleteLoisFUNC);
						});
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-USER-NOT-ACCESS');
			},
			
			/**
			 * Funct deleteLoisFUNC.
			 */
			deleteLoisFUNC: function() {
				
				// If btc adress exist.
				if($.m.user.wallet.admin.deleteLois) {
					
					// disabled btn connect.
					$('.btnDeleteLoi').attr("disabled", "disabled");
					
					// Cree hash of pass phrase.
					var hash = Crypto.util.hexToBytes($.crp.decrypte($.m.user.wallet.hash, $.sha1($('#formDeleteLois #password').val())));
					
					// Private Key+Address
					var sec = new Bitcoin.ECKey(hash);
					
					// Add address bitcoin.
					var adr = ''+sec.getBitcoinAddress();
					
					// Get private key.
					var key = ''+sec.getExportedPrivateKey();
					
					// Var for sign.
					var signBef = $.sha1($.m.lois.fiche.id+adr)
					
					// Compresse private key.
					var payload = Bitcoin.Base58.decode(key);
					var compressed = payload.length == 38;
					
					// Signer le message.
					var signCr = $.btc.sign_message(sec, signBef, compressed);
					
					// connexion serveur.
					$.jsonRPC.request('lois_deleteLoi', {
						
						// Param send.
						params : [adr, $.m.lois.fiche.id, signCr],
						
						// succees.
						success : function(data) {
							
							// Lancer une boucle pour récupérer la loi demander.
							$.each($.m.user.wallet.obs.lois.list, function(k, v) {
								
								// Si le id corespend a la loi.
								if(v.id == data.result.id_loi) {
									
									// delete au tableau les infos retourner.
									$.m.user.wallet.obs.lois.list.splice(k, 1);
									return false;
								}
							});
							
							// Incrémenter le nombre de lois.
							$.m.user.wallet.obs.lois.nb --;
							
							// Incrémenter le nombre de log.
							$.m.user.wallet.log.nb ++;
							
							// Ajouter au tableau les infos retourner.
							$.m.user.wallet.log.list.unshift(data.result.log);
							
							// Destruct var.
							sec = '';
							
							// Lancer un message dans la console.
							$.tmpl.msg('LOIS-DELETE-MSG');
							
							// Setup html.
							$.lois.home();
						},
						
						// erreur serveur.
						error : function(data) {
							
							// remove atr disabled on btn connect.
							$('.btnDeleteLoi').removeAttr("disabled");
							
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
			 * Funct editeAmdHTML.
			 */
			editeAmdHTML: function(param) {
				
				// If admin exist
				if($.m.user.wallet.admin.editeAmd) {
					
					// Lancer une boucle pour récupérer la loi demander.
					$.each($.m.lois.fiche.amd, function(k, v) {
						
						// Si le id corespend a la loi.
						if(v.id == param) {
							
							// Enregistrait dans la variable $.m.lois.fiche.
							$.m.lois.amdFiche = v;
							return false;
						}
					});
					
					// Clean windows.
					$.tmpl.clean();
					
					// Anim complete.
					$('#'+$.m.div.content).fadeOut(300, function() {
						
						// add tmpl. DOC.
						$('#'+$.m.div.content).empty().mustache('editeAmdHTML', $.m);
						
						// Anim complete.
						$('#'+$.m.div.content).fadeIn(300, function() {
							
							// Valid Form.
							$('#formEditeAmd').validate();
							
							// event. Form send. listen.
							$('#formEditeAmd').on('formEditeAmd', $.lois.editeAmdFUNC);
						});
					});
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-USER-NOT-ACCESS');
			},
			
			/**
			 * Funct editeAmdFUNC.
			 */
			editeAmdFUNC: function() {
				
				// If btc adress exist.
				if($.m.user.wallet.admin.editeLois) {
					
					// disabled btn connect.
					$('.btnEditeAmd').attr("disabled", "disabled");
					
					// Cree hash of pass phrase.
					var hash = Crypto.util.hexToBytes($.crp.decrypte($.m.user.wallet.hash, $.sha1($('#formEditeAmd #password').val())));
					
					// Private Key+Address
					var sec = new Bitcoin.ECKey(hash);
					
					// Add address bitcoin.
					var adr = ''+sec.getBitcoinAddress();
					
					// Get private key.
					var key = ''+sec.getExportedPrivateKey();
					
					// Var for sign.
					var signBef = $.sha1($('#formEditeAmd #amd').val()+$.m.lois.amdFiche.id+adr)
					
					// Compresse private key.
					var payload = Bitcoin.Base58.decode(key);
					var compressed = payload.length == 38;
					
					// Signer le message.
					var signCr = $.btc.sign_message(sec, signBef, compressed);
					
					// connexion serveur.
					$.jsonRPC.request('lois_editeAmd', {
						
						// Param send.
						params : [adr, $('#formEditeAmd #amd').val(), $.m.lois.amdFiche.id, signCr],
						
						// succees.
						success : function(data) {
							
							// Lancer une boucle pour récupérer la loi demander.
							$.each($.m.user.wallet.obs.lois.list, function(k, v) {
								
								// Si le id corespend a la loi.
								if(v.id == $.m.lois.fiche.id) {
									
									// Lancer une boucle pour récupérer la loi demander.
									$.each($.m.user.wallet.obs.lois.list[k].amd, function(k1, v1) {
										
										// Si le id corespend a l'amd.
										if(v1.id == data.result.id_amd) {
											
											// Si l'amd corespend a l'amd élu.
											if(v.amdElu == $.m.lois.amdFiche.desc) {
												
												// Modifier l'amd élu.
												$.m.user.wallet.obs.lois.list[k].amdElu = data.result.amd;
											}
											
											// Ajouter au tableau les infos retourner.
											$.m.user.wallet.obs.lois.list[k].amd[k1].desc = data.result.amd;
											return false;
										}
									});	
								}
							});
							
							// Incrémenter le nombre de log.
							$.m.user.wallet.log.nb ++;
							
							// Ajouter au tableau les infos retourner.
							$.m.user.wallet.log.list.unshift(data.result.log);
							
							// Destruct var.
							sec = '';
							
							// Lancer un message dans la console.
							$.tmpl.msg('LOIS-EDIT-AMD-SUCCES');
							
							// Setup html.
							$.lois.ficheLoisHTML($.m.lois.fiche.id);
						},
						
						// erreur serveur.
						error : function(data) {
							
							// remove atr disabled on btn connect.
							$('.btnEditeAmd').removeAttr("disabled");
							
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
			 * Funct deleteAmdHTML.
			 */
			deleteAmdHTML: function(param) {
				
				// If btc adress exist
				if($.m.user.wallet.admin.deleteAmd) {
					
					// Conteneur du nombre d'amd.
					var i = 0;
					
					// Lancer une boucle pour récupérer la loi demander.
					$.each($.m.lois.fiche.amd, function(k, v) {
						
						// Incrémenter le nombre d'amd.
						i++;
						
						// Si le id corespend a la loi.
						if(v.id == param) {
							
							// Enregistrait dans la variable $.m.lois.fiche.
							$.m.lois.amdFiche = v;
						}
					});
					
					// Si plus d'un amd, alors poursuivre.
					if(i > 1) {
						
						// Clean windows.
						$.tmpl.clean();
						
						// Anim complete.
						$('#'+$.m.div.content).fadeOut(300, function() {
							
							// add tmpl. DOC.
							$('#'+$.m.div.content).empty().mustache('deleteAmdHTML', $.m);
							
							// Anim complete.
							$('#'+$.m.div.content).fadeIn(300, function() {
								
								// Valid Form.
								$('#formDeleteAmd').validate();
								
								// event. Form send. listen.
								$('#formDeleteAmd').on('formDeleteAmd', $.lois.deleteAmdFUNC);
							});
						});
					}
					
					// If not btcAdr.
					else $.tmpl.error('LOIS-NOT-DELETE-LAST-AMD');
				}
				
				// If not btcAdr.
				else $.tmpl.error('ERR-USER-NOT-ACCESS');
			},
			
			/**
			 * Funct deleteAmdFUNC.
			 */
			deleteAmdFUNC: function() {
				
				// If btc adress exist.
				if($.m.user.wallet.admin.deleteAmd) {
					
					// disabled btn connect.
					$('.btnDeleteAmd').attr("disabled", "disabled");
					
					// Cree hash of pass phrase.
					var hash = Crypto.util.hexToBytes($.crp.decrypte($.m.user.wallet.hash, $.sha1($('#formDeleteAmd #password').val())));
					
					// Private Key+Address
					var sec = new Bitcoin.ECKey(hash);
					
					// Add address bitcoin.
					var adr = ''+sec.getBitcoinAddress();
					
					// Get private key.
					var key = ''+sec.getExportedPrivateKey();
					
					// Var for sign.
					var signBef = $.sha1($.m.lois.amdFiche.id+adr)
					
					// Compresse private key.
					var payload = Bitcoin.Base58.decode(key);
					var compressed = payload.length == 38;
					
					// Signer le message.
					var signCr = $.btc.sign_message(sec, signBef, compressed);
					
					// connexion serveur.
					$.jsonRPC.request('lois_deleteAmd', {
						
						// Param send.
						params : [adr, $.m.lois.amdFiche.id, signCr],
						
						// succees.
						success : function(data) {
							
							// Lancer une boucle pour récupérer la loi demander.
							$.each($.m.user.wallet.obs.lois.list, function(k, v) {
								
								// Si le id corespend a la loi.
								if(v.id == $.m.lois.fiche.id) {
									
									// Lancer une boucle pour récupérer l'amd demander.
									$.each($.m.user.wallet.obs.lois.list[k].amd, function(k1, v1) {
										
										// Si le id corespend a l'amd.
										if(v1.id == data.result.id_amd) {
											
											// Ajouter au tableau les infos retourner.
											$.m.user.wallet.obs.lois.list[k].amd.splice(k1, 1);
											
											// Si l'amd corespend a l'amd élu.
											if(v.amdElu == $.m.lois.amdFiche.desc) {
												
												// Modifier l'amd élu.
												$.m.user.wallet.obs.lois.list[k].amdElu = $.m.user.wallet.obs.lois.list[k].amd[0].desc;
												
												// Conteneur de vote.
												var i = $.m.user.wallet.obs.lois.list[k].amd[0].px;
												
												// Lancer une boucle pour récupérer l'amd demander.
												$.each($.m.user.wallet.obs.lois.list[k].amd, function(k2, v2) {
													
													// Si i est inferieur au nouveau score.
													if(i < v2.px) {
														
														// Modifier l'amd élu.
														$.m.user.wallet.obs.lois.list[k].amdElu = v2.desc;
													}
												});
											}
											
											// Incrémenter le nombre d'amd.
											$.m.user.wallet.obs.lois.list[k].nbAmd --;
											return false;
										}
									});	
								}
							});
							
							// Incrémenter le nombre de log.
							$.m.user.wallet.log.nb ++;
							
							// Ajouter au tableau les infos retourner.
							$.m.user.wallet.log.list.unshift(data.result.log);
							
							// Destruct var.
							sec = '';
							
							// Lancer un message dans la console.
							$.tmpl.msg('LOIS-DELETE-AMD-DESC');
							
							// Setup html.
							$.lois.ficheLoisHTML($.m.lois.fiche.id);
						},
						
						// erreur serveur.
						error : function(data) {
							
							// remove atr disabled on btn connect.
							$('.btnDeleteAmd').removeAttr("disabled");
							
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
			 * Funct sendVote.
			 */
			sendVote: function(param) {
				
				// If btc adress exist
				if($.m.user.wallet.citoyen) {
					
					// Lancer une boucle pour récupérer la loi demander.
					$.each($.m.lois.fiche.amd, function(k, v) {
						
						// Si le id corespend a la loi.
						if(v.id == param) {
							
							// Enregistrait dans la variable $.m.lois.fiche.
							$.m.lois.amdFiche = v;
						}
					});
					
					// connexion serveur.
					$.jsonRPC.request('vote_sending', {
						
						// Param send.
						params : [$.m.user.wallet.adr, $.m.lois.fiche.id, $.m.lois.amdFiche.id, 'LOS'],
						
						// succees.
						success : function(data) {
							
							// Enregistrait le hash et l'id du vote dans la variable $.m.lois.vote.
							$.m.lois.vote = data.result;
							
							// Clean windows.
							$.tmpl.clean();
							
							// Anim complete.
							$('#'+$.m.div.content).fadeOut(300, function() {
								
								// add tmpl. DOC.
								$('#'+$.m.div.content).empty().mustache('fixVoteLoiHTML', $.m);
								
								// Anim complete.
								$('#'+$.m.div.content).fadeIn(300, function() {
									
									// Valid Form.
									$('#formFixVoteLoi').validate();
									
									// event. Form send. listen.
									$('#formFixVoteLoi').on('formFixVoteLoi', $.lois.FixVote);
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
					$('.btnVoteLoi').attr("disabled", "disabled");
					
					// Cree hash of pass phrase.
					var hash = Crypto.util.hexToBytes($.crp.decrypte($.m.user.wallet.hash, $.sha1($('#formFixVoteLoi #password').val())));
					
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
					var signCr = $.btc.sign_message(sec, $.m.lois.vote.hash, compressed);
					
					// connexion serveur.
					$.jsonRPC.request('vote_fix', {
						
						// Param send.
						params : [adr, $.m.lois.vote.id, signCr],
						
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
							$.lois.ficheLoisHTML($.m.lois.fiche.id);
						},
						
						// erreur serveur.
						error : function(data) {
							
							// remove atr disabled on btn connect.
							$('.btnVoteLoi').removeAttr("disabled");
							
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