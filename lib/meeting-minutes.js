


// fire function on document ready
$(function(){
		
	// fire this function now, and after any new multiple instance templates
	addMultipleInstanceRefire(
		{ addBefore:true, addEnd: true, reorder:false, remove:false }, 
		function () {

			// class added to shrink-on-blur elements which have not been
			// initiated yet
			var initialClass = "shrink-on-blur"; // right now just does full text
			var addedClass = initialClass + "-added";
			var reducedHeight = "50px";
			
			//console.log("shrink on blur: " + $('.'+initialClass).size() + " elements");

			
			$('.multipleTemplateInstance .'+initialClass).each(function(index, element){
				var $elem = $(element);
				
				console.log("Height " + index + ": " + $elem.height());
				// record the current full height of the textbox for the sake of
				// animation later (you can't animate to a height of "auto")
				$elem.attr("full-height", $elem.height());

				$elem
				
					// remove the initiation class so future calls don't re-add this
					.removeClass(initialClass)
					
					// keep a class on these elements for later use if required
					.addClass(addedClass)
						
					// set the current height to the reduced height
					.height(reducedHeight)
				
					// when the element loses focus (blurs) reduce it's height
					// .blur(function(){
						// setTimeout(function() {
							// $elem.attr("full-height", $elem.height());
							// $elem.animate({ height : reducedHeight }, 600 );
							// }, 100);
					// })

					// when the element gets focus grow it back to full size
					.focus(function(){
						// console.log($elem.attr("full-height"));
						
						if ($elem.hasClass(initialClass+'-active'))
							return true;
						
						var growNewFocus = function () {
						
							// animate to previous full height, then set height to auto
							// (can't animate to "auto"). Note: this may look funny if 
							// window is resized between blur/focus cycles
							$elem.animate({ height : $elem.attr("full-height") }, 400, function(){
								$elem
									.height("auto") //set back to auto when animate complete
									.addClass(initialClass+'-active');
							});

						};
						
						
						// find previous Full Text field that had focus (if any did)
						var previousFocus = $('.'+initialClass+'-active');
						
						// if any prev focus field, shrink it then grow new field
						if (previousFocus.size() > 0) {
							previousFocus
								.attr("full-height", previousFocus.height())
								.removeClass(initialClass+'-active')
								.animate(
									{ height : reducedHeight }, // attributes to animate
									600, // duration of animation
									function () { // function to call when animation complete
										growNewFocus();
									}
								);
						}
						else {
							growNewFocus();
						}
						
					});
					
			});

		}
	);


});

$("input, textarea")
	.focus(function(){
		$(".focus-multipleTemplateInstance").removeClass("focus-multipleTemplateInstance");
		
		$(this).closest(".multipleTemplateInstance").addClass("focus-multipleTemplateInstance");
	});




/*

window.noLinkInSemanticFormsMessages = [
	"You may not use links in this field. Please remove all square braces.",
	"No, seriously. You can't use links here.",
	"Really? You're going to keep trying to put square braces in this box?",
	"This isn't going to work.",
	"Please stop.",
	"I said please.",
	"Okay, one more time: you cannot use square brackets...so no [ or ]",
	"I'm not talking to you anymore",
	"[no response]",
	"Ha ha look: I can use square brackets",
	"[no response]"
];

// this will add wikilink warnings to all input elements without them
addMultipleInstanceRefire(function () {
    
	// find all elements that need checking, and remove those that already
	// have it...then added it to them
	$(".no-links-allowed").unbind("blur").blur(function(ev){
	
		// if the field has a [ or a ]
		if($(ev.target).val().search(/\[/) != -1 || $(ev.target).val().search(/]/) != -1) {
			
			var messages = window.noLinkInSemanticFormsMessages;
			
			// inform user that the cannot use links in the field
			// alert("You may not use links in this field. Please remove all square braces.");
			var count = $(".validation-error-message").size();
			if( ! messages[count])
				count = messages.length - 1;
			
			$(ev.target).after("<div class='validation-error-message'>" + messages[count] + "</div>");
			
			// send focus back to the field so they can edit the contents
			$(ev.target).focus();
			
			$(ev.target).addClass("validation-error");
		}
		else {
			$(ev.target).removeClass("validation-error");
			$(".validation-error-message").remove();
		}
	});
});
    
    
// this adds collapsible content to properly marked templates. Use Template:Collapsible
addMultipleInstanceRefire(function(){

	$(".montalvo-collapsible").each(function(index,element){

		var collapseText = $(element).attr("data-collapsetext") || "Collapse";
		var expandText = $(element).attr("data-expandtext") || "Expand";
		var buttonText;

		// if no <a> tags within collapsible, then it hasn't been setup yet
		// this only performed the first time on each collapsible
		if ( ! $(element).find(".collapsible-trigger").size() ) {
					
			if ($(element).hasClass("mw-collapsed")) {
				$(element).children(".mw-collapsible-content").first().hide();
				buttonText = expandText;
			}
			else {
				$(element).children(".mw-collapsible-content").first().show();
				buttonText = collapseText;
			}
			
			// if there is a pre-trigger element, insert the trigger after it
			// otherwise, insert the trigger as the first element (prepend)
			if( $(element).find(".pre-trigger").size() )
				$(element).find(".pre-trigger").after("<a href='#' class='collapsible-trigger'>" + buttonText + "</a>");
			else
					$(element).prepend("<a href='#' class='collapsible-trigger'>" + buttonText + "</a>");
			
		}

		$(element).find("a.collapsible-trigger").unbind("click").click(function(ev){
			
			if( $(ev.target).parent().hasClass("mw-collapsed") ) {
				$(ev.target).parent().find(".mw-collapsible-content").first().slideDown("slow");

				// change button to collapse
				$(ev.target).text(collapseText);
			}
			else {
				$(ev.target).parent().find(".mw-collapsible-content").first().slideUp("slow");
			
				// change button to expand
				$(ev.target).text(expandText);
			}

			$(ev.target).parent().toggleClass("mw-collapsed");
			
			return false;
		
		});

	});
	
});

// adds a counter to text fields limited to 255 characters
addMultipleInstanceRefire(function(){

	$(".character-limit-255").each(function(index,element){

		$(element).removeClass(".character-limit-255").addClass(".character-limit-255-added");

		var counter = $("<span></span>").css({
			"text-align" : "right"
		});
		
		$(element).keyup(function(ev){
			
			var chars = $(this).val().length;
			
			counter.html(chars + " of 255 characters");
			
			var css;
			if (chars < 200) {
				css = {
					color : "black",
					"font-weight" : "normal"
				}
			}
			else {
				css = {
					color : "red",
					"font-weight" : "bold"
				}
			}
			
			counter.css(css);
			
		}).blur(function(){
			if( $(this).val().length < 255 ) {
				counter.hide();
			}
		}).focus(function(){
			counter.show();
		}).after(counter);

	});

});

*/