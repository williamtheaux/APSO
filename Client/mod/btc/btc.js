/*
 * @version 0.6.0
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package	btc.btc.js
 */

(function($, undefined) {
	
	$.extend( {
		
		btc: {
			
			/**
			 * Funct setup.
			 */
			setup: function() {
				
				// Load model.
				$.m.load('btc');
				
				// event. setup listen.
				$('#'+$.m.div.event).one($.m.event.setup, $.btc.defautHtml);
			},
			
			/**
			 * html defaut.
			 */
			defautHtml: function() {
				
				// Load tmpl.
				$.tmpl.load('btc', function () {
					
					// Upload infos btc.
					if($.m.btc.off) $.btc.uploadInfos();
					
					// Extend model : Func date return price chose.
					$.m.btc.PRIX = function() { 
						return function(text, render) {
							
							var tmp = '<span class="btcPrice" data-prix="'+Number(render(text)).toFixed(8)+'"></span>';
							if($.m.btc.v.price) tmp = '<span class="btcPrice" data-prix="'+Number(render(text)).toFixed(8)+'">'+(Number(render(text)).toFixed(8)*$.m.btc.v.price).toFixed(2)+'</span>';
							
							// Return result.
							return tmp;
						}
					};
					
					// Extend model : Func date return price btc.
					$.m.btc.STHBTC = function() { 
						return function(text, render) {
							
							// Return result.
							return (Number(render(text))/100000000).toFixed(8);
						}
					};
					
					// Extend model : Func STHPRIX return price chose in satishi.
					$.m.btc.STHPRIX = function() { 
						return function(text, render) {
							
							var tmp = '<span class="btcPrice" data-prix="'+(Number(render(text))/100000000).toFixed(8)+'"></span>';
							if($.m.btc.v.price) tmp = '<span class="btcPrice" data-prix="'+(Number(render(text))/100000000).toFixed(8)+'">'+(Number(render(text)/100000000).toFixed(8)*$.m.btc.v.price).toFixed(2)+'</span>';
							
							// Return result.
							return tmp;
						}
					};
					
					// Extend model : Func date return devise + code. CODE ICO NAME
					$.m.btc.DEVISE = function() { 
						return function(text, render) {
							
							var tmp = '';
							
							if(render(text)=='CODE') {
								
								var tmp = '<span class="codePrice"></span>';
								if($.m.btc.v.devise) tmp = '<span class="codePrice">'+$.m.btc.v.devise+'</span>';
							}
							
							if(render(text)=='ICO') {
								
								tmp = '<span class="icoPrice"></span>';
								if($.m.btc.v.devise) tmp = '<span class="icoPrice"><img src="img/val/'+$.m.btc.v.devise+'.gif" alt="'+$.m.btc.v.devise+'"></span>';
							}
							
							if(render(text)=='NAME') {
								
								tmp = '<span class="namePrice"></span>';
								if($.m.btc.v.devise) tmp = '<span class="namePrice">'+$.lng.tr($.m.btc.v.devise, true)+'</span>';
							}
							
							// Return result.
							return tmp;
						}
					};
				});
			},
			
			/**
			 * html uploadInfos.
			 */
			uploadInfos: function() {
				
				// connexion serveur.
				$.jsonRPC.request('btc_news', {
					
					// Param send.
					params : [],
					
					// succees.
					success : function(data) {
						
						// add the infos.
						$.m.btc.v.prices = data.result;
						$.m.btc.v.menu = Array();
						
						// Creat array menu devise code.
						$.each($.m.btc.v.prices, function(key, val) {
							
							// add result tab log.
							$.m.btc.v.menu.push(key);
						});
						
						// Cookie devise.
						var btcCookie = $.cookie('devise');
						
						// if cookie.
						if(btcCookie!=undefined) {
							
							// if code cookie in array.
							if($.inArray(btcCookie, $.m.btc.v.menu) > -1) $.m.btc.v.devise=btcCookie;
							
							// if not code in array.
							else {
								
								// first code in array.
								$.m.btc.v.devise=$.m.btc.v.menu[0];
								
								// New cookie.
								$.cookie($.m.lng.cookie, $.m.btc.v.devise);
							}
						}
						
						// if not cookie.
						else {
							
							// if not code in array. first.
							$.m.btc.v.devise=$.m.btc.v.menu[0];
							
							// New cookie.
							$.cookie('devise', $.m.btc.v.devise);
						}
						
						// Price actu for devise.
						$.m.btc.v.price = $.m.btc.v.prices[$.m.btc.v.devise].last;
						
						// Begin news price interval.
						setInterval($.btc.newPrice,300000);
						
						// Edit html devise
						$.btc.editDevise();
						
						// Edit html Price
						$.btc.editPrice();
						
						// add menu btc.
						$('#'+$.m.div.menu).mustache('mBtc', $.m, {method:'prepend'});
						
						// Init popup sur les lien.
						$('#mBtc button').tooltip();
					},
					
					// erreur serveur.
					error : function(data) {
						
						// erreur.
						$.tmpl.error(data.error);
						
						// Upload infos btc. after 5s
						setTimeout($.btc.uploadInfos, 10000);
					}
				});
			},
			
			/**
			 * html newPrice.
			 */
			newPrice: function() {
				
				// connexion serveur.
				$.jsonRPC.request('btc_news', {
					
					// Param send.
					params : [],
					
					// succees.
					success : function(data) {
						
						// add the infos.
						$.m.btc.v.prices = data.result;
						
						// Price actu for devise.
						$.m.btc.v.price = $.m.btc.v.prices[$.m.btc.v.devise].last;
						
						// Edit html Price
						$.btc.editPrice();
					},
					
					// erreur serveur.
					error : function(data) {
						
						// erreur.
						$.tmpl.error(data.error);
					}
				});
			},
			
			/**
			 * Function changePrices.
			 * @param   string $param code devise "USD".
			 */
			changePrices: function(param) {
				
				// if code in array devise dispo.
				if($.inArray(param, $.m.btc.v.menu) > -1) {
					
					// if devise != devise actu.
					if($.m.btc.v.devise!=param) {
						
						// Modif devise in objet.
						$.m.btc.v.devise=param;
						
						// New cookie.
						$.cookie('devise', $.m.btc.v.devise);
						
						// Price actu for devise.
						$.m.btc.v.price = $.m.btc.v.prices[$.m.btc.v.devise].last;
						
						// Edit html devise
						$.btc.editDevise();
						
						// Edit html Price
						$.btc.editPrice();
					}
				}
			},
			
			/**
			 * Funct editDevise.
			 */
			editDevise: function() {
				
				// edit code price in page.
				$('.codePrice').each(function() {
					
					// Animation complete.
					$(this).fadeOut(400, function() {
					
						// code texte.
						$(this).text($.m.btc.v.devise);
						
						// add contenue.
						$(this).fadeIn(400);
					});
				});
				
				// edit icon price in page.
				$('.icoPrice').each(function() {
					
					// Animation complete.
					$(this).fadeOut(400, function() {
					
						// img devise.
						$(this).html('<img src="img/val/'+$.m.btc.v.devise+'.gif" alt="'+$.m.btc.v.devise+'">');
						
						// add contenue.
						$(this).fadeIn(400);
					});
				});
				
				// edit name price in page.
				$('.namePrice').each(function() {
					
					// Animation complete.
					$(this).fadeOut(400, function() {
					
						// name html.
						$(this).html($.lng.tr($.m.btc.v.devise, true));
						
						// add contenue.
						$(this).fadeIn(400);
					});
				});
			},
			
			/**
			 * Funct editPrice.
			 */
			editPrice: function() {
				
				// edit code price in page.
				$('.btcPrice').each(function() {
					
					// If price html > new price
					if((Number($(this).data('prix'))*$.m.btc.v.price).toFixed(2)<(Number($(this).text()))) {
					
						// Animation complete.
						$(this).fadeOut(400, function() {
								
							// code texte.
							$(this).text((Number($(this).data('prix'))*$.m.btc.v.price).toFixed(2)).addClass('text-danger');
							
							// Sign menu btc.
							$('#menuBtcSign i').removeClass().addClass('fa fa-arrow-circle-down');
							
							// add contenue.
							$(this).fadeIn(400);
						});
					}
					
					// If price html < new price
					if((Number($(this).data('prix'))*$.m.btc.v.price).toFixed(2)>(Number($(this).text()))) {
					
						// Animation complete.
						$(this).fadeOut(400, function() {
							
							// code texte.
							$(this).text((Number($(this).data('prix'))*$.m.btc.v.price).toFixed(2)).addClass('text-success');
							
							// Sign menu btc.
							$('#menuBtcSign i').removeClass().addClass('fa fa-arrow-circle-up');
							
							// add contenue.
							$(this).fadeIn(400);
						});
					}
				});
				
				// Remove class after 4s.
				setTimeout(function () { 
					$('.btcPrice').removeClass('text-success text-danger');
				}, 4000);
			},
			
			/**
			 * html home.
			 */
			home: function() {
				
				// Clean windows.
				$.tmpl.clean();
				
				// Animation complete.
				$('#'+$.m.div.content).fadeOut(300, function() {
					
					// add tmpl. home.
					$('#'+$.m.div.content).empty().mustache('btcHome', $.m);
					
					// Animation complete.
					$('#'+$.m.div.content).fadeIn(300, function() {
						
						// Add video bitcoin.
						$('#videoBtc').tubeplayer({
							initialVideo	: $.lng.tx($.m.btc.vid),
							protocol		: $.m.protocol
						});
					});
				});
			},
			
			/**
			 * Function sign_message.
			 * @param   ...
			 */
			sign_message: function(private_key, message, compressed, addrtype) {
				
				function msg_numToVarInt(i) {
					if (i < 0xfd) {
						return [i];
					} else if (i <= 0xffff) {
						// can't use numToVarInt from bitcoinjs, BitcoinQT wants big endian here (!)
						return [0xfd, i & 255, i >>> 8];
					} else {
						throw ("message too large");
					}
				}
				
				function msg_bytes(message) {
					var b = Crypto.charenc.UTF8.stringToBytes(message);
					return msg_numToVarInt(b.length).concat(b);
				}

				function msg_digest(message) {
					var b = msg_bytes("Bitcoin Signed Message:\n").concat(msg_bytes(message));
					return Crypto.SHA256(Crypto.SHA256(b, {asBytes:true}), {asBytes:true});
				}
				
				if (!private_key) return false;
					
				var signature = private_key.sign(msg_digest(message));
				var address = new Bitcoin.Address(private_key.getPubKeyHash());
				address.version = addrtype ? addrtype : 0;
				
				//convert ASN.1-serialized signature to bitcoin-qt format
				var obj = Bitcoin.ECDSA.parseSig(signature);
				var sequence = [0];
				sequence = sequence.concat(obj.r.toByteArrayUnsigned());
				sequence = sequence.concat(obj.s.toByteArrayUnsigned());
				
				for (var i = 0; i < 4; i++) {
					var nV = 27 + i;
					if (compressed)
						nV += 4;
					sequence[0] = nV;
					var sig = Crypto.util.bytesToBase64(sequence);
					if ($.btc.verify_message(sig, message, addrtype) == address) return sig;
				}
				return false;
			},
			
			/**
			 * Funct verify_message.
			 */
			verify_message: function(signature, message, addrtype) {
				
				function msg_numToVarInt(i) {
					if (i < 0xfd) {
						return [i];
					} else if (i <= 0xffff) {
						// can't use numToVarInt from bitcoinjs, BitcoinQT wants big endian here (!)
						return [0xfd, i & 255, i >>> 8];
					} else {
						throw ("message too large");
					}
				}
				
				function msg_bytes(message) {
					var b = Crypto.charenc.UTF8.stringToBytes(message);
					return msg_numToVarInt(b.length).concat(b);
				}
				
				function msg_digest(message) {
					var b = msg_bytes("Bitcoin Signed Message:\n").concat(msg_bytes(message));
					return Crypto.SHA256(Crypto.SHA256(b, {asBytes:true}), {asBytes:true});
				}
				
				try {
					var sig = Crypto.util.base64ToBytes(signature);
				} catch(err) {
					return false;
				}
				if (sig.length != 65) return false;
				// extract r,s from signature
				var r = BigInteger.fromByteArrayUnsigned(sig.slice(1,1+32));
				var s = BigInteger.fromByteArrayUnsigned(sig.slice(33,33+32));
				// get recid
				var compressed = false;
				var nV = sig[0];
				if (nV < 27 || nV >= 35) return false;
				if (nV >= 31) {
					compressed = true;
					nV -= 4;
				}
				var recid = BigInteger.valueOf(nV - 27);
				var ecparams = getSECCurveByName("secp256k1");
				var curve = ecparams.getCurve();
				var a = curve.getA().toBigInteger();
				var b = curve.getB().toBigInteger();
				var p = curve.getQ();
				var G = ecparams.getG();
				var order = ecparams.getN();
				var x = r.add(order.multiply(recid.divide(BigInteger.valueOf(2))));
				var alpha = x.multiply(x).multiply(x).add(a.multiply(x)).add(b).mod(p);
				var beta = alpha.modPow(p.add(BigInteger.ONE).divide(BigInteger.valueOf(4)), p);
				var y = beta.subtract(recid).isEven() ? beta : p.subtract(beta);
				var R = new ECPointFp(curve, curve.fromBigInteger(x), curve.fromBigInteger(y));
				var e = BigInteger.fromByteArrayUnsigned(msg_digest(message));
				var minus_e = e.negate().mod(order);
				var inv_r = r.modInverse(order);
				var Q = (R.multiply(s).add(G.multiply(minus_e))).multiply(inv_r);
				var public_key = Q.getEncoded(compressed);
				var addr = new Bitcoin.Address(Bitcoin.Util.sha256ripe160(public_key));
				addr.version = addrtype ? addrtype : 0;
				return addr.toString();
			},
		}
	});
	
})(jQuery);