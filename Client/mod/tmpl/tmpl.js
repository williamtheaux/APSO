/*
 * @version 0.5.0
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package	tmpl.tmpl.js
 */

(function($, undefined) {

	$.extend( {
	
		tmpl: {
			
			/**
			 * Funct setup.
			 */
			setup: function() {
				
				// Load model.
				$.m.load('tmpl', function() {
					
					// Load tmpl.
					$.tmpl.load('tmpl', function() {
						
						// Extend model : Func nice Number.
						$.m.tmpl.NICE = function() { 
							return function(text, render) {
								
								// return.
								return render(text).toString().replace(/(\d)(?=(?:\d{3})+(?:$))/g, '$1 ');
							}
						};
						
						// Event template start.
						$('#'+$.m.div.event).trigger($.m.event.setup);
						
						// Init valide form.
						$.tmpl.setupValidatorForm();
					});
				});
			},
			
			/**
			 * spinOn. add spinner.
			 * @param e div conteneur balise i.
			 * @param i name icon.
			 */
			spinOn: function(e, i) {
				
				// add in tmpl.
				$('#'+e+' i').removeClass(i).addClass($.m.tmpl.html.spinClass);
			},
			
			/**
			 * spinOff. close spinner.
			 * @param e div conteneur.
			 * @param i name icone add.
			 */
			spinOff: function(e, i) {
			
				// remove icone.
				$('#'+e+' i').removeClass($.m.tmpl.html.spinClass).addClass(i);
			},
			
			/**
			 * Funct error.
			 * @param e Message erreur.
			 */
			error: function(e) {
				
				// boucle error.
				$.each($.m.tmpl.error.blackList, function(key, val) {
					
					// if critique erreur.
					if (e==val) {
						
						// clean.
						$.tmpl.clean();
						
						// Add message.
						$.m.tmpl.error.label = val;
						
						// add tmpl. Form Login.
						$('#'+$.m.div.event).empty().mustache('erreur', $.m);
						
						// Break boucle.
						return false;
					}
				});
				
				// render erreur.
				$.jGrowl($.lng.tr(e, true)+' ', { 
					position: $.m.tmpl.error.position,
					closeTemplate: $.m.tmpl.error.closeIcon
				});
				
				// Play sound after 500ms.
				setTimeout(function() { $.voix.play($.m.tmpl.sound.error); }, 500);
			},
			
			/**
			 * Funct msg.
			 * @param e Message.
			 */
			msg: function(e) {
				
				// boucle error.
				$.each($.m.tmpl.error.blackList, function(key, val) {
					
					// if critique erreur.
					if (e==val) {
						
						// clean.
						$.tmpl.clean();
						
						// Add message.
						$.m.tmpl.error.label = val;
						
						// add tmpl. Form Login.
						$('#'+$.m.div.event).empty().mustache('erreur', $.m);
						
						// Break boucle.
						return false;
					}
				});
				
				// render erreur.
				$.jGrowl($.lng.tr(e, true)+' ', { 
					position: $.m.tmpl.error.position,
					closeTemplate: $.m.tmpl.error.closeIcon
				});
				
				// Play sound after 500ms.
				setTimeout(function() { $.voix.play($.m.tmpl.sound.click); }, 500);
			},
			
			/**
			 * modal.
			 * @param e div of modal.
			 */
			modal: function(e) {
				
				// Clean windows.
				$('.popover').remove();
				$('.tooltip').remove();
				
				// If modal in dom. Play sound.
				if($('.modal-backdrop').length > 0) $.voix.play($.m.tmpl.sound.closeWin);
				
				// Play sound.
				else $.voix.play($.m.tmpl.sound.openWin);
				
				// setup modal.
				$('#'+e).modal('toggle');
			},
			
			/**
			 * clean. Clean windows.
			 */
			clean: function() {
				
				// Clean windows.
				$('.popover').remove();
				$('.tooltip').remove();
				
				// if modal, remove modal.
				$('.modal-backdrop').remove();
				$('.modal-scrollable').remove();
				
				// Boucle for destroy anim.
				$('#'+$.m.div.content+' div').each(function() {
					
					// resets element and removes animation.
					$(this).destroy();
				});
			},
			
			/**
			 * anim. animation.css.
			 * @param e Element.
			 * @param effect The number.
			 * @param r remove class after.
			 * @param callback execut end function.
			 */
			anim: function(e, effect, r, callback) {
				
				// Add animation.
				e.addClass('animated '+ effect);
				
				// Event end animation.
				e.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
					
					// if remove anim.
					if(r) e.removeClass('animated '+ effect);
					
					// If call back.
					if(callback) callback(e);
				});
			},
			
			/**
			 * scroll.
			 * @param e tag for if.
			 */
			scroll: function(e) {
					
				// If top.
				if(e=='up') {
					
					// Scroll up.
					$('html,body').animate({scrollTop: 0}, 'slow');
					
					// Play sound.
					$.voix.play($.m.tmpl.sound.scroll);
				}
				
				// If b.
				else if(e=='down') { 
					
					// Scroll down.
					$('html,body').animate({scrollTop: $(document).height() - $(window).height()}, 'slow');
					
					// Play sound.
					$.voix.play($.m.tmpl.sound.scroll);
				}
				
				else {
					
					// Scroll div # position.
					$('html,body').animate({scrollTop: $('#'+e).offset().top-70}, 'slow');
				}
			},
			
			/**
			 * tagScroll.
			 * @param e id div.
			 */
			tagScroll: function(e) {
				
				// mouse enter btn.
				$('#'+e).html(
					
					// Btn scroll top. mouse enter.
					$($.m.tmpl.html.scrollBtn).mouseenter(function() {
						
						// Anim icon & color text with class.
						$(this).switchClass( $.m.tmpl.html.scrollBtnCl1, $.m.tmpl.html.scrollBtnCl2, 500, "easeOutQuint" );
						
						// Add animation. @param 1-element 2-effect 3-remove anim class after 'default=false' 4-callBack
						$.tmpl.anim($(this), 'pulse', true);
						
						// Play sound.
						$.voix.play($.m.tmpl.sound.click);
					
					// 	mouse leave
					}).mouseleave(function() {
						
						// Anim icon & color text with class.
						$(this).switchClass( $.m.tmpl.html.scrollBtnCl2, $.m.tmpl.html.scrollBtnCl1, 500, "easeOutQuint" );
					})
				);
			},
			
			/**
			 * Funct load.
			 * @param e file name.
			 * @param callback.
			 */
			load: function(e, callback) {
				
				// Load tmpl.
				$.Mustache.load($.m.plug.url+e+'/'+e+'.htm').done(function () {
				
					// If call back.
					if(callback) callback();
				});
			},
			
			/**
			 * niceNumber. ex: '12 345 542'
			 * @param e The number.
			 * Return string number nice.
			 */
			niceNumber: function(e) {
				
				// return.
				return e.toString().replace(/(\d)(?=(?:\d{3})+(?:$))/g, '$1 ');
			},
			
			/**
			 * Funct setupValidatorForm. validator form.
			 */
			setupValidatorForm : function() {
				
				// Param validation input.
				jQuery.validator.setDefaults( { 
					
					// regle input.
					rules: $.m.tmpl.rules,
					
					// Message.
					messages: $.m.tmpl.messages,
					
					// class erreur
					errorClass: "help-block",
					errorElement: "small",
					
					// div render.
					errorPlacement: function(error, e) {
						
						// div erreur.
						error.appendTo($(e).parents(".form-group"));
					},
					
					// invalidHandler.
					showErrors: function(errorMap, errorList) {
						
						// boucle error.
						$.each(errorList, function(key, val) {
							
							//translate error.
							val.message = $.lng.tr(val.message, true);
						});
						
						// Print error.
						this.defaultShowErrors();
					},
					
					// if input good.
					highlight:function(e, errorClass, validClass) {
						
						// add class erreur.
						$(e).parents('.form-group').addClass('has-error');
						// if success.
						$(e).parents('.form-group').removeClass('has-success'); 
					},
					
					// If bad input.
					unhighlight: function(e, errorClass, validClass) {
						
						// if erreur.
						$(e).parents('.form-group').removeClass('has-error');
						// add class success.
						$(e).parents('.form-group').addClass('has-success');
					},
					
					// after valid.
					submitHandler: function(form) {
						
						// Event.
						$('#'+form.id).trigger(form.id);
					}
				});
			},
		}
	});
	
})(jQuery);