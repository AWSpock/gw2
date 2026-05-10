var loader = document.getElementById("loader");
ShowLoader(loader);

// var txtSearch = document.getElementById("txtSearch");

async function Run() {
  var rows = document.querySelectorAll("[data-id='row'][data-item]");
  console.log("Rows: ", rows);

  var ids = [];
  for (var x = 0; x < rows.length; x++) {
    ids.push(rows[x].getAttribute("data-item"));
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
    promises.push(LoadItems(chunk));
  });

  await Promise.all(promises);
  console.log("Promises Done");

  var response = await fetch(
    "https://api.guildwars2.com/v2/account/materials?access_token=" +
      api_key +
      "&" +
      caching,
    {
      method: "GET",
    },
  )
    .then((response) => response.json())
    .then((data) => {
      console.log("Account Materials Complete");
      data.forEach((rec) => {
        document
          .querySelectorAll("[data-item='" + rec.id + "']")
          .forEach((item) => {
            item.querySelector("[data-id='amount']").innerText = rec.count;
          });
      });
    });
  console.log("After Account Materials");

  HideLoader(loader);
  console.log("Hide Loader");

  // txtSearch.parentElement.style.display = "block";
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
