function CreatorStudio() {
    this.deposit = document.querySelector(".deposit-container");
    this.items = document.querySelector(".items");
    this.summary = document.querySelector(".summary-container");
    this.form = document.getElementById("items-form");
    this.submit = document.querySelector(".btn.submit");

    this.draggable = null;
    this.steps = {5: 5, 10: 10, 20: 15};

    this.initDraggable();
    this.initSubmit();
}

CreatorStudio.prototype = {

    initDraggable: function() {
        var self = this;
        var goodZone = false;

        this.draggable = new window['Draggable'].Droppable(document.querySelector('.creation-container'), {
            draggable: '.i--draggable',
            droppable: '.i--droppable',

            mirror: {
                constrainDimensions: true
            }
        });

        this.draggable.on('drag:start', function() {
            self.deposit.style.display = "block";
            goodZone = false;
        });
        this.draggable.on('drag:stop', function(event) {
            self.deposit.style.display = "none";

            if (goodZone) {
                document.querySelector(".intro-container").style.display = "none";
                self.newItem(event.source);

                self.deposit.classList.remove("draggable-droppable--occupied");
                self.deposit.classList.remove("active");
            }
        });
        this.draggable.on('droppable:over', function(event) {
            if (event.droppable === self.deposit) {
                self.deposit.classList.add("active");
                goodZone = true;
            }
        });
        this.draggable.on('droppable:out', function(event) {
            if (event.droppable === self.deposit) {
                self.deposit.classList.remove("active");
                goodZone = false;
            }
        });
    },

    initSubmit: function() {
        var self = this;
        this.submit.addEventListener("click", function() {
            self.form.submit();
        });
    },

    newItem: function(item) {
        var name = item.dataset.name;
        var price = item.dataset.price;
        var itemId = item.dataset.id;

        var clone = document.querySelector(".my-item.template").cloneNode(true);

        // On paramètre le clone
        clone.querySelector(".name").textContent = name;
        clone.querySelector(".price span").textContent = price;
        clone.dataset.unityPrice = price;
        clone.dataset.itemId = itemId;

        // Ajout du clone dans la liste des items ...
        clone.classList.remove("template");
        this.items.appendChild(clone);

        // ... et dans le formulaire de soumission!
        var inp = document.createElement("input");
        inp.type = "hidden"; inp.value = "1"; inp.name = "article_" + itemId;
        this.form.appendChild(inp);

        // Démarrage des écouteurs d'évènements sur l'item
        this.initQtySelector(clone.querySelector(".qty-selector"));
        this.initRemoveButton(clone.querySelector(".remove-ctn"));

        this.updateTotal();
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

        if (manual) {
            var inp = this.form.querySelector("input[name=\"article_" + selector.parentNode.dataset.itemId + "\"");
            inp.value = qty;
        }
    },

    initRemoveButton: function(selector) {
        var self = this;

        selector.addEventListener("click", function() {
            var item = this.parentNode;
            var itemId = item.dataset.itemId;

            item.parentNode.removeChild(item);
            self.form.removeChild(self.form.querySelector("input[name=\"article_" + itemId + "\"]"));

            var draggableItem = self.deposit.querySelector(".item[data-id='" + itemId + "']");
            var wrap = document.querySelector(".item-wrapper[data-id='" + itemId + "']");

            if (wrap != null && draggableItem != null) {
                wrap.appendChild(draggableItem.cloneNode(true));
                wrap.classList.add("draggable-droppable--occupied");
                draggableItem.parentElement.removeChild(draggableItem);
            }

            self.updateTotal();
        }, true);
    },

    updateItemPrice: function(item) {
        var qty    = parseInt(item.querySelector(".qty-selector span").innerHTML);
        var uPrice = parseFloat(item.dataset.unityPrice);
        var cSpan  = item.querySelector(".price span");

        cSpan.innerHTML = (qty * uPrice).toFixed(2);

        // On oublie pas de mettre à jour le total aussi.
        this.updateTotal();
    },

    updateTotal: function() {
        // Elements du DOM à modifier
        var tPrice = this.summary.querySelector(".tprice");
        var pReduc = this.summary.querySelector(".preduc");
        var vReduc = this.summary.querySelector(".vreduc");
        var fPrice = this.summary.querySelector(".fprice");
        var proBar = this.summary.querySelector(".progress-bar");
        var stepsD = this.summary.querySelectorAll(".progress-formule .step");
        var submit = document.querySelector(".btn.submit");

        // Récupération du prix global
        var total = 0;

        var items = this.items.querySelectorAll(".my-item");
        for (var i = 0; i < items.length; i++)
            total += parseFloat(items[i].querySelector(".price span").textContent)

        // Mise à jour des composants
        tPrice.textContent = total.toFixed(2);
        proBar.style.width = (Math.min(Math.max((total - 4) / (22 - 4) * 100, 1), 100)) + "%";

        var mSteps = this.steps;
        var step = null;
        var ind = 0;
        Object.keys(mSteps).forEach(function (key) {
            if (total >= parseInt(key)) {
                step = [ind, parseInt(key), mSteps[key]];
                stepsD[ind].classList.add("active");
            } else {
                stepsD[ind].classList.remove("active");
            }

            ind++;
        });

        var reduc = 0;

        if (step != null) {
            reduc = (step[2] / 100) * total;

            if (total - reduc < step[1])
                reduc = total - step[1];

            pReduc.textContent = step[2] + '%';
            vReduc.textContent = '-' + reduc.toFixed(2);
            submit.style.display = "block";
        } else {
            pReduc.textContent = '-';
            vReduc.textContent = '0';
            submit.style.display = "none";
        }

        fPrice.textContent = (total - reduc).toFixed(2);
    }

};

window.addEventListener("load", function() {
    new CreatorStudio();
}, true);