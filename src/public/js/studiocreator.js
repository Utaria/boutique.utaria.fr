function CreatorStudio() {
    this.deposit = document.querySelector(".deposit-container");
    this.items = document.querySelector(".items");

    this.draggable = null;

    this.initDraggable();
}

CreatorStudio.prototype = {

    initDraggable: function() {
        var self = this;
        this.draggable = new window['Draggable'].Droppable(document.querySelector('.creation-container'), {
            draggable: '.i--draggable',
            droppable: '.i--droppable',

            mirror: {
                constrainDimensions: true
            }
        });

        this.draggable.on('drag:start', function() {
            self.deposit.style.display = "block";
        });
        this.draggable.on('drag:stop', function(event) {
            self.deposit.style.display = "none";

            self.newItem(event.source);

            self.deposit.classList.remove("draggable-droppable--occupied");
            self.deposit.classList.remove("active");
        });
        this.draggable.on('droppable:over', function(event) {
            if (event.droppable === self.deposit)
                self.deposit.classList.add("active");
        });
        this.draggable.on('droppable:out', function(event) {
            if (event.droppable === self.deposit)
                self.deposit.classList.remove("active");
        });
    },

    newItem: function(item) {
        var name = item.dataset.name;
        var price = item.dataset.price;

        var clone = document.querySelector(".my-item.template").cloneNode(true);

        // On paramètre le clone
        clone.querySelector(".name").textContent = name;
        clone.querySelector(".price span").textContent = price;
        clone.dataset.unityPrice = price;

        // Ajout du clone dans la liste des items
        clone.classList.remove("template");
        this.items.appendChild(clone);

        // Démarrage des écouteurs d'évènements sur l'item
        this.initQtySelector(clone.querySelector(".qty-selector"));
        this.initRemoveButton(clone.querySelector(".remove-ctn"));
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
            self.updateItemPrice(sel.parentNode);
            self.updateSelector(sel, true);
        });
        plusEl.addEventListener("click", function() {
            if (this.classList.contains("hidden")) return;
            var sel = this.parentNode;
            var spa = sel.querySelector("span");

            spa.innerHTML = parseInt(spa.innerHTML) + 1;
            self.updateItemPrice(sel.parentNode);
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

        // if (manual) updateQtySession(...);
    },

    initRemoveButton: function(selector) {
        var self = this;

        selector.addEventListener("click", function() {
            var item = this.parentNode;
            item.parentNode.removeChild(item);

            console.log(Object.getOwnPropertyNames(self.draggable).filter(function (p) {
                return typeof self.draggable[p] === 'function';
            }));
        }, true);
    },

    updateItemPrice: function(item) {
        var qty    = parseInt(item.querySelector(".qty-selector span").innerHTML);
        var uPrice = parseFloat(item.dataset.unityPrice);
        var cSpan  = item.querySelector(".price span");

        cSpan.innerHTML = (qty * uPrice).toFixed(2);

        // On oublie pas de mettre à jour le total aussi.
        // this.updateTotal();
    }

};

window.addEventListener("load", function() {
    new CreatorStudio();
}, true);