let cart = {};
cart.items = [];

$(document).ready(function () {

    // Check local data, it nothing exist, then create
    if (localStorage.getItem("shopping_cart") != null) {
        let items = JSON.parse(localStorage.getItem("shopping_cart")).items;
        for (let i = 0; i < items.length; i++) {
            if ($.isNumeric(items[i].amount) && $.isNumeric(items[i].price)) {
                addItemOnPresenter(i, items[i]);
                cart.items.push(items[i]);
            }
        }
        updateTotalPriceOnPresenter();
    }

    $(".btn-add-to-cart").click(function (e) {
        let amount = 1;
        if ($.isNumeric($(".btn-add-to-cart-main-amount").val())) {
            amount = parseInt($(".btn-add-to-cart-main-amount").val())
        }
        let t = $(this);
        let loadingText = 'Adding...';
        if (t.html() !== loadingText) {
            t.data('original-text', t.html());
            t.html(loadingText);
            t.addClass("disabled");

            // If item already exist
            let itemInStorage = itemExistInStorage(cart.items, t.data("id"));
            if (!itemInStorage) {
                retrieveDetailFromServer(t.data("id"));
            } else {
                itemInStorage["amount"] = parseInt(itemInStorage["amount"]) + amount;
				$("#item_" + t.data("id")).val(itemInStorage["amount"]);
                updateTotalPriceOnPresenter();
            }

            t.removeClass("disabled");
            t.html(t.data('original-text'));
        }

        updateLocalStorage();
    });

    $("#register_email").change(function(){
        validateRegisterEmail($(this).val());
    });

});

function addItemOnPresenter(key, item) {
    $("#shopping-list").append(
        '<div class="form-row" data-attribute=' + item.id + '>\n' +
        '    <div class="form-group col-12"><label for="item_' + item.id + '">' + item.name + '</label></div>\n' +
        '    <div class="form-group col-6">\n' +
        '        <input class="form-control" id="item_' + item.id + '" min="0" type="number"\n' +
        '               value="' + item.amount + '"  onchange="onChangeItemAmount(this, ' + key + ', ' + item.id + ');">\n' +
        '    </div>\n' +
        '    <div class="form-group col-6 text-right" id="item_' + item.id + '_price">$' + item.price + '\/each</div>\n' +
        '</div>'
    );
}


function itemExistInStorage(items, itemId, itemKey = false) {
    for (let i = 0; i < items.length; i++) {
        if (items[i].id === itemId)
            return (itemKey === true) ? i : items[i];
    }
}


function onChangeItemAmount(item, key, directValue = 0) {
	if (item.value > 0) {
        cart.items[key]["amount"] = item.value;
    } else {
        if (confirm('Are you sure to remove the item?')) {
            removeItem(key);
        } else {
            cart.items[key]["amount"] = 1;
        }
    }
    updateLocalStorage();
}


function removeItem(key) {
    cart.items.splice(key, 1);
    $("#shopping-list").empty();
    let items = cart.items;
    for (let i = 0; i < items.length; i++) {
        if ($.isNumeric(items[i].amount) && $.isNumeric(items[i].price)) {
            addItemOnPresenter(i, items[i]);
        }
    }
    updateLocalStorage();
}


function updateTotalPriceOnPresenter() {
    let totalPrice = 0;
    $.each(cart.items, function (key, value) {
        totalPrice += value.amount * value.price;
    });
    $(".shopping-cart-popup-price").html('$ ' + totalPrice.toFixed(2));
    $(".shopping-cart-popup-item-amount").html('(' + cart.items.length + ')');
}


function updateLocalStorage() {
    localStorage.setItem("shopping_cart", JSON.stringify(cart));
    updateTotalPriceOnPresenter();
}


function retrieveDetailFromServer(itemId, amount = 1) {
    $.ajax({
        type: 'POST',
        url: 'inc/api.php',
        dataType: 'json',
        data: {
            action: 'validate_cart',
            id: itemId,
            amount: amount,
        },
        success: function (msg) {
            let newItem = ({
                id: itemId,
                name: msg.name,
                price: msg.price,
                amount: amount,
                addAt: $.now()
            });
            cart.items.push(newItem);
            let key = itemExistInStorage(cart.items, itemId, true);
            addItemOnPresenter(key, newItem);
            updateLocalStorage();
        }
    });
}


function validateLoginPassword(password) {
    $.ajax({
        type: 'POST',
        url: 'inc/api.php?action=validate_password',
        dataType: 'json',
        data: {
            password: password,
        },
        success: function (msg) {
            if(msg.success === true){
                $("#register_email_helper").replaceWith("test");
            }else{
                $("#register_email_helper").replaceWith("fuck");
            }
        }
    });
}

function validateRegisterEmail(email){
    $.ajax({
        type: 'POST',
        url: 'inc/api.php',
        dataType: 'json',
        data: {
            action: 'validate_register_email',
            email: email,
        },
        success: function (msg) {
            let newItem = ({
                id: itemId,
                name: msg.name,
                price: msg.price,
                amount: amount,
                addAt: $.now()
            });
            cart.items.push(newItem);
            let key = itemExistInStorage(cart.items, itemId, true);
            addItemOnPresenter(key, newItem);
            updateLocalStorage();
        }
    });
}