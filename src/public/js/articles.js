function Articles() {
    this.init();
}

Articles.prototype = {

    init: function() {
        var articles = document.getElementsByClassName("article");

        for (var i = 0; i < articles.length; i++)
            this.initArticle(articles[i]);
    },

    initArticle: function(article) {
        var self = this;
        var btnAdd = article.querySelector(".btn.shop-cart-add");

        btnAdd.addEventListener("click", function(event) {
            self.clickOnCartBtn.call(this, self, event);
        }, false);
    },

    clickOnCartBtn: function(self, event) {
        var btn = this;
        var cont = this.parentNode;
        var meta = cont.parentNode;

        var id = meta.dataset.id;

        meta.parentNode.classList.add("opened");
        btn.style.display = "none";
        cont.querySelector(".fa-cog").style.display = "block";

        var xhr = new XMLHttpRequest();

        xhr.addEventListener("readystatechange", function() {
            if (xhr.status === 200 && xhr.readyState === 4) {
                var res = xhr.responseText;
                var label = cont.querySelector(".label");

                cont.querySelector(".fa-cog").style.display = "none";
                label.style.display = "block";

                if (res === "success") {
                    meta.parentNode.classList.add("success");
                    label.innerHTML = "Article ajouté !";

                    self.updateCartButton();
                } else {
                    meta.parentNode.classList.add("error");
                    label.innerHTML = "Déjà ajouté !";
                }

                setTimeout(function() {
                    btn.style.display = "block";
                    label.style.display = "none";

                    meta.parentNode.classList.remove("opened");

                    meta.parentNode.classList.remove("success");
                    meta.parentNode.classList.remove("error");
                }, 2000);
            }
        });

        var url = document.querySelector(".action_url").value.replace(':id', id);
        xhr.open("GET", url, true);

        setTimeout(function() {
            xhr.send(null);
        }, 500);
    },

    updateCartButton: function() {
        var btn = document.querySelector(".shop-cart-summary");
        var badge = btn.querySelector(".badge");



        badge.style.transform = "scale(4) rotate(-20deg)";

        setTimeout(function() {
            badge.style.transform = "scale(1)";
        }, 400);
    }

};

new Articles();