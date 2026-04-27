var loader = document.getElementById("loader");
ShowLoader(loader);

var daily_reset = new Date();
daily_reset.setUTCHours(0, 0, 0, 0);

var caching = Math.floor(Date.now() / (1000 * 60));

var response = fetch(
  "https://api.guildwars2.com/v2/account?v=latest&access_token=" + api_key,
  {
    method: "GET",
  },
)
  .then((response) => response.json())
  .then((data) => {
    var last_modified = new Date(data.last_modified);
    if (last_modified > daily_reset) {
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

var today = new Date();
var storageName =
  today.getUTCFullYear() +
  "-" +
  (today.getUTCMonth() + 1) +
  "-" +
  today.getUTCDate();

var storageManualName = "event_manual-" + storageName;

var storageManual = [];

function setStorage(name, data) {
  localStorage.setItem(name, JSON.stringify(data));
}

if (localStorage.getItem(storageManualName) === null) {
  setStorage(storageManualName, storageManual);
} else {
  storageManual = JSON.parse(localStorage.getItem(storageManualName));
}

storageManual.forEach((rec) => {
  document.querySelectorAll("[data-key='" + rec + "']").forEach((el) => {
    el.classList.add("complete");
  });
});

document.querySelectorAll("[data-id='complete']").forEach((el) => {
  el.addEventListener("click", toggleComplete);
});

function toggleComplete(e) {
  var val = e.target.getAttribute("data-value");
  var rec = storageManual.indexOf(val);
  var add = true;
  if (rec > -1) {
    storageManual.splice(rec, 1);
    add = false;
  } else {
    storageManual.push(val);
  }

  setStorage(storageManualName, storageManual);

  document.querySelectorAll("[data-key='" + val + "']").forEach((el) => {
    if (add) {
      el.classList.add("complete");
    } else {
      el.classList.remove("complete");
    }
  });
}
