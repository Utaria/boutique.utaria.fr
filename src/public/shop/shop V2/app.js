$.post("recuparticles.php",function(data){
	$(".items-container").html(data);
	$(".add").click(function(){
 		var id = $(this).data("id");
 		$.post("addtocart.php", {id:id}, function(data){
 			$(".instant-cart").text(data);
		});
 	});
});