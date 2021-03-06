function Panier() {
	// Formulaire de promotion
	this.promoForm       = document.querySelector(".promo-form");
	this.promoSpanErr    = this.promoForm.nextSibling.nextSibling;
	this.canSendPromoReq = true;
	this.promoCode       = null;

	this.init();
}

Panier.prototype = {

	init: function() {
		this.initQtySelectors();
		this.initRemoveSelectors();
		this.initPromoForm();
	},

	/* ------------------------------------- */
	/* -- Formulaire de code promotionnel -- */
	/* ------------------------------------- */
	initPromoForm: function() {
		var self = this;

		// Évènement sur le formulaire
		this.promoForm.addEventListener(
			"submit", function(event) {
				event.preventDefault();
				self.postPromoForm(this);
				return false;
			}
		);

		// Initialisation si nécessaire du formulaire
		if (this.promoForm.querySelector("input").value !== "")
			this.postPromoForm(this.promoForm);
	},

	postPromoForm: function(el) {
		var self = this;
		if (!self.canSendPromoReq) return false;

		// Récupération du code à envoyer
		var inp = el.querySelector("input");
		var btn = el.querySelector("button");

		var code = inp.value;
		if (code == null || !code || code === "") return false;

		// Réinitialisation du formulaire du code
		el.classList.remove("error");
		self.promoSpanErr.style.display = "none";
		self.promoSpanErr.innerHTML = "";

		// Envoi du formulaire via XHR
		var xhr = self.newXMLHttpRequest();

		self.canSendPromoReq = false;

		xhr.open("POST", el.action, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

		xhr.onreadystatechange = function() {
			if (xhr.readyState === 4) {
				if (xhr.status === 200) {
					var res = JSON.parse(xhr.responseText);

					if (res.good) {
						self.promoForm.classList.add("success");
						inp.disabled = true;
						
						btn.classList.remove("loading");
						btn.classList.add("success");
						btn.querySelector("svg").className = "fa fa-check";

						self.promoCode = res.code;
						self.updateTotal();
						return true;
					} else {
						self.promoForm.classList.add("error");
						self.promoSpanErr.style.display = "block";
						self.promoSpanErr.innerHTML = res.error_msg + ".";
					}
				}

				inp.value = "";
				self.canSendPromoReq = true;

				btn.classList.remove("loading");
				btn.querySelector("svg").className = "fa fa-angle-right";
			}
		};

		btn.classList.add("loading");
		btn.querySelector("svg").className = "fa fa-spin fa-refresh";
		xhr.send("code=" + code);

		return false;
	},

	removePromoCode: function() {
		// Mise à jour de la session
		var xhr = this.newXMLHttpRequest();

		xhr.open("POST", this.promoForm.action, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send("code=null");

		// Mise à jour du DOM et de la mémoire
		var inp = this.promoForm.querySelector("input");
		var btn = this.promoForm.querySelector("button");

		this.promoForm.classList.remove("success");
		this.canSendPromoReq = true;
		inp.disabled         = false;
		
		inp.value = "";
						
		btn.classList.remove("loading");
		btn.classList.remove("success");
		btn.querySelector("svg").className = "fa fa-angle-right";

		this.promoCode = null;
		this.updateTotal();
	},

	/* --------------------------- */
	/* -- Sélecteur de quantité -- */
	/* --------------------------- */
	initQtySelectors: function() {
		var selectors = document.querySelectorAll("[qty-selector]");

		for (var i = 0; i < selectors.length; i++)
			this.initQtySelector(selectors[i]);
	},

	initQtySelector: function(selector) {
		var self = this;

		// Création des deux boutons de sélection
		var minusEl = document.createElement("div");
		var plusEl  = document.createElement("div");

		minusEl.className = "btn btn-minus";
		minusEl.innerHTML = '<i class="fa fa-minus"></i>';
		plusEl.className = "btn btn-plus";
		plusEl.innerHTML = '<i class="fa fa-plus"></i>';

		selector.insertBefore(minusEl, selector.firstChild);
		selector.appendChild(plusEl);

		// On met à jour les boutons avec la quantité
		this.updateSelector(selector, false);

		// Evènements sur les deux sélecteurs
		minusEl.addEventListener("click", function() {
			if (this.classList.contains("hidden")) return;
			var sel = this.parentNode;
			var spa = sel.querySelector("span");
			
			spa.innerHTML = parseInt(spa.innerHTML) - 1;
			self.updateProductPrice(sel.parentNode);
			self.updateSelector(sel, true);
		});
		plusEl.addEventListener("click", function() {
			if (this.classList.contains("hidden")) return;
			var sel = this.parentNode;
			var spa = sel.querySelector("span");
			
			spa.innerHTML = parseInt(spa.innerHTML) + 1;
			self.updateProductPrice(sel.parentNode);
			self.updateSelector(sel, true);
		});
	},

	updateSelector: function(selector, manual) {
		var qty     = parseInt(selector.querySelector("span").innerHTML);
		var minusEl = selector.querySelector(".btn-minus");
		var plusEl  = selector.querySelector(".btn-plus");

		if (qty === 1) minusEl.classList.add("hidden");
		else           minusEl.classList.remove("hidden");

		if (qty === 999) plusEl.classList.add("hidden");
		else             plusEl.classList.remove("hidden");
		
		if (manual)
			this.updateQtySession(
				selector.parentNode.dataset.productId, qty
			);
	},

	updateQtySession: function(productId, qty) {
		var xhr  = this.newXMLHttpRequest();
		var href = document.querySelector(".products").dataset.qtyHref;

		xhr.open("POST", href, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send("product_id=" + productId + "&qty=" + qty);
	},

	/* ------------------------------ */
	/* -- Suppression d'un produit -- */
	/* ------------------------------ */
	initRemoveSelectors: function() {
		var self        = this;
		var rmSelectors = document.querySelectorAll(".products .remove");
		var confirmCont = document.querySelector(".confirm-container");
		var noPdctError = document.querySelector(".noproduct");

		for (var i = 0; i < rmSelectors.length; i++)
			rmSelectors[i].addEventListener("click", function(event) {
				event.preventDefault();

				var row = this.parentNode.parentNode;
				var pId = parseInt(row.dataset.productId);
				row.parentNode.removeChild(row);

				// Requête XHR pour supprimer le produit de la session
				var xhr  = self.newXMLHttpRequest();
				var href = document.querySelector(".products").dataset.qtyHref;

				xhr.open("POST", href, true);
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xhr.send("product_id=" + pId + "&qty=0");

				// On fait quelques modifications sur la page
				var newNb = document.querySelectorAll(".products [data-product-id]").length;

				if (newNb === 0) {
					self.promoForm.style.display = "none";
                    confirmCont.style.display      = "none";
					noPdctError.style.display    = "block";

					self.removePromoCode();
				} else {
					self.promoForm.style.display = "block";
                    confirmCont.style.display      = "block";
					noPdctError.style.display    = "none";
				}

				self.updateTotal();
				self.updateHeader();
				return false;
			});
	},

	/* --------------------------- */
	/* -- Mise à jour du panier -- */
	/* --------------------------- */
	updateProductPrice: function(row) {
		var qty    = parseInt(row.querySelector("[qty-selector] span").innerHTML);
		var uPrice = parseFloat(row.querySelector("td.u-price span").innerHTML);
		var cSpan  = row.querySelector("td.c-price span");

		cSpan.innerHTML = (qty * uPrice).toFixed(2);

		// On oublie pas de mettre à jour le total aussi.
		this.updateTotal();
		this.updateHeader();
	},

	updateHeader: function() {
		var btnHead     = document.querySelector(".cart-header");
		var productList = document.querySelector(".cart-content .cart-products");

		var nb    = this.calcCartSize();
		var total = this.calcCartTotal();

		btnHead.querySelector(".badge").innerHTML   = nb;
		btnHead.querySelector(".balance").innerHTML = total.toFixed(2) + "€";

		// Mise à jour de la liste des produits
		var rows = document.querySelectorAll("tr[data-product-id]");

		productList.innerHTML = "";

		for (var i = 0; i < rows.length; i++) {
		    var row = rows[i];
			var name  = row.querySelector(".name").innerHTML;
			var qty   = row.querySelector(".qty span").innerHTML;
			var price = row.querySelector(".c-price span").innerHTML;

			var artEl = document.createElement("div");
			artEl.className = "product";

			artEl.innerHTML  = '<span class="name">' + name + '</span>';
			artEl.innerHTML += '<span class="qty">x' + qty + '</span>';
			artEl.innerHTML += '<span class="price">' + parseFloat(price).toFixed(2) + '€</span>';

			productList.appendChild(artEl);
		}
	},

	updateTotal: function() {
		var self         = this;
		var totalTable   = document.querySelector("table.total");
		var totPriceSpan = totalTable.querySelector(".total-products-price");
		var trPromo      = totalTable.querySelector("tr.promo");
		var total        = this.calcCartTotal();

		totPriceSpan.innerHTML = total.toFixed(2);

		// Ajout de la remise si elle existe
		if (this.promoCode != null) {
			trPromo.classList.remove("hidden");

			// Calcul de la réduction
			var reducSymbol = (this.promoCode.type === "percent") ? "%" : "€";
			var reducValue  = parseFloat(this.promoCode.value);
			var reduc       = "- " + reducValue + " " + reducSymbol;
			var newTotal    = total;

			if (reducSymbol === "%")
				newTotal = total - total * (reducValue / 100);
			else
				newTotal = total - reducValue;

			// Vérification du prix minimum de commande
            if (total < parseFloat(this.promoCode.min_price)) {
                this.removePromoCode();
                return;
            }


			// Mise à jour des élements du DOM
			trPromo.querySelector("span.code").innerHTML = this.promoCode.code;
			trPromo.querySelector(".reduc").innerHTML    = reduc;
			trPromo.nextSibling.nextSibling.querySelector("span").innerHTML = newTotal.toFixed(2);

			trPromo.querySelector(".rm-code").onclick = function() {
				self.removePromoCode();
			};
		} else {
			trPromo.classList.add("hidden");
		}
	},


	/*   Fonctions utilitaires   */
	newXMLHttpRequest: function() {
		var xhr = null;
		
		if (window.XMLHttpRequest || window.ActiveXObject) {
			if (window.ActiveXObject) {
				try {
					xhr = new ActiveXObject("Msxml2.XMLHTTP");
				} catch(e) {
					xhr = new ActiveXObject("Microsoft.XMLHTTP");
				}
			} else {
				xhr = new XMLHttpRequest(); 
			}
		} else {
			alert("Votre navigateur ne supporte pas cette fonctionnalité...");
			return null;
		}
		
		return xhr;
	},

	calcCartSize: function() {
		var qtySpans = document.querySelectorAll("[qty-selector] span");
		var total    = 0;

		for (var i = 0; i < qtySpans.length; i++)
			total += parseFloat(qtySpans[i].innerHTML);

		return total;
	},

	calcCartTotal: function() {
		// Calcul du coût total du panier sans remise
		var priceSpans = document.querySelectorAll(".c-price span");
		var total      = 0;

		for (var i = 0; i < priceSpans.length; i++)
			total += parseFloat(priceSpans[i].innerHTML);

		return total;
	}

};

window.addEventListener("load", function() {
    new Panier();
});