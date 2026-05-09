var loader = document.getElementById("loader");
ShowLoader(loader);

var txtSearch = document.getElementById("txtSearch");

async function Run() {
  var ids = [];

  var response = await fetch(
    "https://api.guildwars2.com/v2/account/bank?access_token=" +
      api_key +
      "&" +
      caching,
    {
      method: "GET",
    },
  )
    .then((response) => response.json())
    .then((data) => {
      console.log("Bank Complete");
      var container = document.getElementById("container");
      var boxTemplate = document.getElementById("template-box");
      var box;
      var count = 0;
      var max = 30;
      data.forEach((rec) => {
        // console.log(rec);

        if (count === 0) {
          box = boxTemplate.content.cloneNode(true);
        }

        var clone;
        if (rec === null) {
          var itemTemplate = document.getElementById("template-item-empty");
          clone = itemTemplate.content.cloneNode(true);
        } else {
          ids.push(rec.id);
          var itemTemplate = document.getElementById("template-item");
          clone = itemTemplate.content.cloneNode(true);
          clone
            .querySelector("[data-id='item']")
            .setAttribute("data-item", rec.id);
          clone.querySelector("[data-id='amount']").innerText =
            rec.charges ?? rec.count;
        }

        box.querySelector("[data-id='box']").append(clone);

        count++;

        if (count >= max) {
          count = 0;
          container.append(box);
        }
      });
    });
  console.log("After Bank");

  var chunks = [];
  for (var x = 0; x < ids.length; x += 100) {
    var chunk = ids.slice(x, x + 100);
    chunks.push(chunk);
  }
  console.log("Chunk Count: " + chunks.length);

  var promises = [];
  chunks.forEach((chunk) => {
    console.log("Add Promise");
    promises.push(LoadItems(chunk));
  });

  await Promise.all(promises);
  console.log("Promises Done");

  HideLoader(loader);
  console.log("Hide Loader");

  txtSearch.parentElement.style.display = "block";
}

Run();

async function LoadItems(chunk) {
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
          .querySelectorAll("[data-item='" + rec.id + "']")
          .forEach((div) => {
            div.querySelector("a").href =
              "https://wiki.guildwars2.com/wiki/" + rec.name;
            div.querySelector("img").src = rec.icon;
            div.querySelector("img").title = rec.name;
            div.setAttribute("data-search", rec.name.toLowerCase());
          });
      });
    });
}

// search

txtSearch.addEventListener("keyup", function (e) {
  var searchTerm = txtSearch.value.toLowerCase();

  document
    .querySelectorAll("[data-id='item']:not([data-item])")
    .forEach((item) => {
      if (searchTerm === "") {
        item.style.display = "block";
      } else {
        item.style.display = "none";
      }
    });

  document.querySelectorAll("[data-item]").forEach((item) => {
    if (
      item.getAttribute("data-search").includes(searchTerm) ||
      searchTerm === ""
    ) {
      item.style.display = "block";
    } else {
      item.style.display = "none";
    }
  });
});
