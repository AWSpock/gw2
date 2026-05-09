var loader = document.getElementById("loader");
ShowLoader(loader);

var previous_daily_reset = returnDailyReset();
previous_daily_reset.setUTCDate(previous_daily_reset.getUTCDate() - 1);

var response = fetch(
  "https://api.guildwars2.com/v2/account?v=latest&access_token=" +
    api_key +
    "&" +
    caching,
  {
    method: "GET",
  },
)
  .then((response) => response.json())
  .then((data) => {
    var last_modified = new Date(data.last_modified);
    if (last_modified > previous_daily_reset) {
      checkCompletion();
    } else {
      document.getElementById("play-message").style.display = "block";
      HideLoader(loader);
    }
  });

async function checkCompletion() {
  var [response1, response2] = await Promise.all([
    fetch(
      "https://api.guildwars2.com/v2/account/mapchests?v=latest&access_token=" +
        api_key +
        "&" +
        caching,
      {
        method: "GET",
      },
    ),
    fetch(
      "https://api.guildwars2.com/v2/account/worldbosses?v=latest&access_token=" +
        api_key +
        "&" +
        caching,
      {
        method: "GET",
      },
    ),
  ]);

  var data1 = await response1.json();
  data1.forEach((rec1) => {
    document.querySelectorAll("[data-key='" + rec1 + "']").forEach((el) => {
      el.classList.add("complete");
    });
  });

  var data2 = await response2.json();
  data2.forEach((rec2) => {
    document.querySelectorAll("[data-key='" + rec2 + "']").forEach((el) => {
      el.classList.add("complete");
    });
  });

  HideLoader(loader);
}

//

async function displayManualCompletion() {
  fetch("/api/event-completion.php?api_key=" + api_key + "&" + caching, {
    method: "GET",
  })
    .then((response) => response.json())
    .then((data) => {
      document.querySelectorAll("[data-manual]").forEach((el) => {
        var value = el.getAttribute("data-manual");

        var complete = false;
        if (data.indexOf(value) > -1) {
          complete = true;
          el.classList.add("complete");
        } else {
          el.classList.remove("complete");
        }
        // console.log(value, complete);
      });
    });
}

displayManualCompletion();

async function toggleComplete(e) {
  var val = e.target.getAttribute("data-value");

  var data = new FormData();
  data.append("identifier", val);

  var response = await fetch(
    "/api/event-completion.php?api_key=" + api_key + "&" + caching,
    {
      method: "POST",
      body: data,
    },
  );

  if (!response.ok) {
    alert("An Error Occurred");
    throw new Error(`${response.statusText}`);
  }

  displayManualCompletion();
}

document.querySelectorAll("a[data-value]").forEach((el) => {
  el.addEventListener("click", toggleComplete);
});

// var storageName =
//   today.getUTCFullYear() +
//   "-" +
//   (today.getUTCMonth() + 1) +
//   "-" +
//   today.getUTCDate();

// var storageManualName = "event_manual-" + storageName;

// var storageManual = [];

// if (getStorage(storageManualName) === null) {
//   setStorage(storageManualName, storageManual);
// } else {
//   storageManual = JSON.parse(getStorage(storageManualName));
// }

// storageManual.forEach((rec) => {
//   document.querySelectorAll("[data-key='" + rec + "']").forEach((el) => {
//     el.classList.add("complete");
//   });
// });

// document.querySelectorAll("[data-id='complete']").forEach((el) => {
//   el.addEventListener("click", toggleComplete);
// });

// function toggleComplete(e) {
//   var val = e.target.getAttribute("data-value");
//   var rec = storageManual.indexOf(val);
//   var add = true;
//   if (rec > -1) {
//     storageManual.splice(rec, 1);
//     add = false;
//   } else {
//     storageManual.push(val);
//   }

//   setStorage(storageManualName, storageManual);

//   document.querySelectorAll("[data-key='" + val + "']").forEach((el) => {
//     if (add) {
//       el.classList.add("complete");
//     } else {
//       el.classList.remove("complete");
//     }
//   });
// }
