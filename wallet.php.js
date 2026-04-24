var loader = document.getElementById("loader");
ShowLoader(loader);

var response = fetch("https://api.guildwars2.com/v2/currencies?ids=all", {
  method: "GET",
})
  .then((response) => response.json())
  .then((data) => {
    data.sort((a, b) => a.order - b.order);
    data.forEach((rec) => {
        if(rec.name == "")
            return;

      var template = document.getElementById("template");
      var clone = template.content.cloneNode(true);

      var iconA = clone.querySelector("[data-id='icon'] a");
      iconA.href = "https://wiki.guildwars2.com/wiki/" + rec.name;

      var iconI = clone.querySelector("[data-id='icon'] img");
      iconI.src = rec.icon;
      iconI.title = rec.name;

      var currency = clone.querySelector("[data-id='currency']");
      currency.innerText = rec.name;

      var val = clone.querySelector("[data-id='value']");
      val.innerText = "TBD";
      val.setAttribute("data-value", rec.id);

      document.querySelector("table tbody").append(clone);
    });

    var response1 = fetch(
      "https://api.guildwars2.com/v2/account/wallet?v=latest&access_token=" +
        api_key,
      {
        method: "GET",
      },
    )
      .then((response1) => response1.json())
      .then((data1) => {
        data1.forEach((rec) => {
          var nf = new Intl.NumberFormat("en-US");
          var val = nf.format(rec.value);
          if (rec.id == 1) val = calculateCoin(rec.value);
          document.querySelector("[data-value='" + rec.id + "']").innerText =
            val;
        });
        document.querySelectorAll("[data-id='value']").forEach((rec) => {
          if (rec.innerText == "TBD") rec.innerText = 0;
        });
        HideLoader(loader);
      });
  });
