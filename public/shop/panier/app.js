$(".quantity").change(function(e){
	var val = $(".quantity").val();
	$(".price").text(val);
});