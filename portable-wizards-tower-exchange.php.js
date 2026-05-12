var loader = document.getElementById("loader");
ShowLoader(loader);

// var txtSearch = document.getElementById("txtSearch");

async function Run(type) {
  var Type = "";
  var Types = "";
  var types = "";
  if (type == "material") {
    Type = "Material";
    Types = "Materials";
    types = "materials";
  }
  if (type == "currency") {
    Type = "Currency";
    Types = "Currencies";
    types = "wallet";
  }
  var rows = document.querySelectorAll("[data-id='row'][data-" + type + "]");
  console.log("Rows: ", rows);

  var ids = [];
  for (var x = 0; x < rows.length; x++) {
    ids.push(rows[x].getAttribute("data-" + type));
  }
  console.log("IDs: ", ids);

  var chunks = [];
  for (var x = 0; x < ids.length; x += 100) {
    var chunk = ids.slice(x, x + 100);
    chunks.push(chunk);
  }
  console.log("Chunk Count: " + chunks.length);

  var promises = [];
  chunks.forEach((chunk) => {
    console.log("Add Promise");
    if (type == "material")
      promises.push(LoadMaterials(chunk));
    if (type == "currency")
      promises.push(LoadCurrencies(chunk));
  });

  await Promise.all(promises);
  console.log("Promises Done");

  var response = await fetch(
    "https://api.guildwars2.com/v2/account/" + types + "?access_token=" +
    api_key +
    "&" +
    caching,
    {
      method: "GET",
    },
  )
    .then((response) => response.json())
    .then((data) => {
      console.log("Account " + Types + " Complete");
      data.forEach((rec) => {
        document
          .querySelectorAll("[data-" + type + "='" + rec.id + "']")
          .forEach((item) => {
            if (type == "material")
              item.querySelector("[data-id='amount']").innerText = rec.count;
            if (type == "currency")
              item.querySelector("[data-id='amount']").innerText = formatterInt.format(rec.value);
          });
      });
    });
  console.log("After Account " + Type);

  HideLoader(loader);
  console.log("Hide Loader");

  // txtSearch.parentElement.style.display = "block";
}

Run("material");
Run("currency");

async function LoadMaterials(chunk) {
  var response1 = await fetch(
    "https://api.guildwars2.com/v2/items?ids=" + chunk.join(","),
    {
      method: "GET",
    },
  )
    .then((response1) => response1.json())
    .then((data1) => {
      console.log("Chunk Done");
      data1.forEach((rec) => {
        document
          .querySelectorAll("[data-material='" + rec.id + "']")
          .forEach((div) => {
            var a = div.querySelector("a");
            a.href = "https://wiki.guildwars2.com/wiki/" + rec.name;
            a.setAttribute("target", "_blank");
            a.textContent = rec.name;
            div.querySelector("img").src = rec.icon;
            div.querySelector("img").title = rec.name;
            // div.setAttribute("data-search", rec.name.toLowerCase());
          });
      });
    });
}

async function LoadCurrencies(chunk) {
  var response1 = await fetch(
    "https://api.guildwars2.com/v2/currencies?ids=" + chunk.join(","),
    {
      method: "GET",
    },
  )
    .then((response1) => response1.json())
    .then((data1) => {
      console.log("Chunk Done");
      data1.forEach((rec) => {
        document
          .querySelectorAll("[data-currency='" + rec.id + "']")
          .forEach((div) => {
            var a = div.querySelector("a");
            a.href = "https://wiki.guildwars2.com/wiki/" + rec.name;
            a.setAttribute("target", "_blank");
            a.textContent = rec.name;
            div.querySelector("img").src = rec.icon;
            div.querySelector("img").title = rec.name;
            // div.setAttribute("data-search", rec.name.toLowerCase());
          });
      });
    });
}

// // search

// txtSearch.addEventListener("keyup", function (e) {
//   var searchTerm = txtSearch.value.toLowerCase();
//   document.querySelectorAll("[data-item]").forEach((item) => {
//     if (
//       item.getAttribute("data-search").includes(searchTerm) ||
//       searchTerm === ""
//     ) {
//       item.style.display = "block";
//     } else {
//       item.style.display = "none";
//     }
//   });
// });
