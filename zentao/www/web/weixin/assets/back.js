$(function(){ 
	pushHistory(); 
	window.addEventListener("popstate", function(e) { 
		if(pageGlobal.backUrl) {
            jump(pageGlobal.backUrl);
        }
	}, false); 
function pushHistory() { 
	var state = { 
		title: "title", 
		url: "#" 
	}; 
	window.history.pushState(state, "title", "#"); 
} 
}) 