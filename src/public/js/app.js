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