window.SF_MultipleInstanceRefire_addEnd = [];
window.SF_MultipleInstanceRefire_addBefore = [];
window.addMultipleInstanceRefire = function(options, refireFn) {
	
	var opt = options ? options : {};
	var addEnd    = (opt.addEnd !== null) ? opt.addEnd : true, // default true
		addBefore = (opt.addBefore !== null) ? opt.AddBefore : true, // default true
		remove    = (opt.remove !== null) ? opt.remove : false, // default false
		reorder   = (opt.reorder !== null) ? opt.reorder : false; // default false
	
	if (addEnd)
		window.SF_MultipleInstanceRefire_addEnd.push(refireFn);

	if (addBefore)
		window.SF_MultipleInstanceRefire_addBefore.push(refireFn);
	
	if (remove)
		console.log("not yet supported"); //window.SF_MultipleInstanceRefire_remove.push(refireFn);

	if (reorder)
		console.log("not yet supported"); //window.SF_MultipleInstanceRefire_reorder.push(refireFn);

	setTimeout(refireFn, 2000); // without delay some things aren't fully setup yet, even after DOM load
	
};

// after DOM loads apply functions to instance-adder, remover and reorder buttons
$(function(){

	var refire = function(group){
		for(var fn in group) {
			group[fn]();
		}
	};
	
	var refireAddEnd    = refire(window.SF_MultipleInstanceRefire_addEnd),
		refireAddBefore = refire(window.SF_MultipleInstanceRefire_addBefore);

	// clicking the "add" button in a Semantic Form will not automatically
	// apply Javascript to new elements this re-fires certain functions so
	// they can apply themselves to new elements
	$(".multipleTemplateAdder").click(function(){
		// setTimeout so functions happen after new instance added
		setTimeout(refireAddEnd,100);
		return true;
	});
	
	$(".multipleTemplateInstance .addAboveButton").click(function(){
		// setTimeout so functions happen after new instance added
		setTimeout(refireAddBefore,100);
		return true;
	});
	
});