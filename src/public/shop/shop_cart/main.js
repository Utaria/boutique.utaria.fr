var b = false;
$(".basket").click(function(){
	b = !b
	if(b)
	{
		$(this).animate({
			width: '375px',
			height: '200px'
		});
		$(".basket .disappearance").css("display", "none");
	}else
	{
		$(this).animate({
			width: '150',
			height: '45'
		});
		$(".basket .disappearance").css("display", "inline");
	}

});