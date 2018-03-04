setTimeout(function() {
	if (typeof particlesJS !== 'undefined')
		particlesJS.load('particles-js', 'js/particles-conf.json', function() {});
}, 800);

var clipboard = new Clipboard(document.querySelectorAll(".ip-copy"));
		
clipboard.on('success', function(e) {
	var trigger = e.trigger;
	var bHtml   = trigger.innerHTML;

	trigger.innerHTML = "<b>IP copiée !</b>";

	setTimeout(function() {
		trigger.innerHTML = bHtml;
	}, 3000);
});

function debounce(callback, delay){
    var timer;
    return function(){
        var args = arguments;
        var context = this;
        clearTimeout(timer);
        timer = setTimeout(function(){
            callback.apply(context, args);
        }, delay)
    }
}