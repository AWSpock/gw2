var items = [];
var allItems = [];

document.querySelectorAll("[data-group]").forEach((rec, i) => {
    console.log(i, rec);

    items[i] = [];
    rec.querySelectorAll("[data-item-id]").forEach(function (rec) {
        items[i].push(rec.getAttribute("data-item-id"));
        allItems.push(rec.getAttribute("data-item-id"));
    });
});

async function Run() {
    var chunks = [];
    for (var x = 0; x < allItems.length; x += 99) {
        var chunk = allItems.slice(x, x + 99);
        chunks.push(chunk);
    }
    console.log("Chunk Count: " + chunks.length);

    var promises = [];
    chunks.forEach((chunk) => {
        console.log("Add Promise");
        promises.push(LoadItems(chunk));
        promises.push(LoadCommerce(chunk));
    });

    await Promise.all(promises);
    console.log("Promises Done");

    console.log("Calculate Prices");
    var amounts = [];
    items.forEach(col => {
        console.log(col);
        var amount = 0;
        col.forEach(id => {
            amount += CalcPrice(id);
        });
        console.log("Amount:", amount);
        amounts.push(amount);
    });
    console.log("Calculate Prices Done");

    console.log("Get Index for Max Amount");
    var max = amounts.indexOf(Math.max(...amounts));
    console.log("Index:", max);

    document.querySelectorAll("[data-group]").forEach((rec, i) => {
        if (i == max) {
            rec.parentElement.classList.add("winner");
        }
    });
}

Run();

async function LoadItems(items) {
    await fetch("https://api.guildwars2.com/v2/items?ids=" + items.join(","), {
        method: "GET"
    })
        .then(response => response.json())
        .then(data => {
            // console.log(data);
            data.forEach(function (rec) {
                // console.log(rec);
                var dv = document.querySelector("[data-id='container']").querySelector("[data-item-id='" + rec.id + "']");
                dv.querySelector("[data-id='name']").textContent = rec.name + " (" + rec.id + ")";
                dv.querySelector("[data-id='gw2tp']").setAttribute("href", "https://www.gw2tp.com/item/" + rec.id);
                dv.querySelector("img").src = rec.icon;
            });
        });
}

async function LoadCommerce(items) {
    await fetch("https://api.guildwars2.com/v2/commerce/listings?ids=" + items.join(","), {
        method: "GET"
    })
        .then(response => response.json())
        .then(data => {
            // console.log(data);
            data.forEach(function (rec) {
                console.log(rec);
                var dv = document.querySelector("[data-id='container']").querySelector("[data-item-id='" + rec.id + "']");
                if (dv.querySelector("[data-id='SellNow']")) {
                    if (rec.buys[0] == null) {
                        dv.querySelector("[data-id='SellNow']").textContent = 0;
                    } else {
                        if (rec.buys[0] == null) {
                            dv.querySelector("[data-id='SellNow']").textContent = 0;
                        } else {
                            dv.querySelector("[data-id='SellNow']").textContent = rec.buys[0].unit_price;
                        }
                    }
                }
                // dv.querySelector("[data-id='BuyNow']").textContent = rec.sells[0].unit_price;
            });
        });
}

function CalcPrice(id) {
    var item = document.querySelector("[data-item-id='" + id + "']");
    var baseprice = 0;
    if (item.querySelector("[data-id='SellNow']"))
        baseprice = item.querySelector("[data-id='SellNow']").textContent;
    if (item.querySelector("[data-id='Cost']"))
        baseprice = item.querySelector("[data-id='Cost']").textContent * -1;
    var quantity = item.getAttribute("data-quantity");
    item.querySelector("[data-id='Quantity']").textContent = quantity;
    var price = baseprice * quantity;

    item.querySelector("[data-id='TotalCompare']").textContent = price;
    return price;
}