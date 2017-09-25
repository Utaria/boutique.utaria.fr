var cartBtn = document.querySelector(".shop-cart-summary .cart-header");

cartBtn.addEventListener("click", function(event) {
	event.preventDefault();

	var btn    = this.parentNode;
	var opened = btn.classList.contains("opened");

	if (opened) btn.classList.remove("opened");
	else        btn.classList.add("opened");

	return false;
});