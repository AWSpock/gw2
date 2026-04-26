var dates = document.querySelectorAll("[utc-convert]");

// console.log(dates);

dates.forEach((d) => {
  //   console.log(d);
  var dt = new Date(d.textContent);
  d.textContent = dt.toLocaleTimeString([], {
    hour: "numeric",
    minute: "2-digit",
  });
});

//

var response = fetch(
  "https://api.guildwars2.com/v2/account/mapchests?v=latest&access_token=" +
    api_key,
  {
    method: "GET",
  },
)
  .then((response) => response.json())
  .then((data) => {
    data.forEach((rec) => {
      document.querySelectorAll("[data-key='" + rec + "']").forEach((el) => {
        el.classList.add("complete");
      });
    });
  });

var response = fetch(
  "https://api.guildwars2.com/v2/account/worldbosses?v=latest&access_token=" +
    api_key,
  {
    method: "GET",
  },
)
  .then((response) => response.json())
  .then((data) => {
    data.forEach((rec) => {
      document.querySelectorAll("[data-key='" + rec + "']").forEach((el) => {
        el.classList.add("complete");
      });
    });
  });

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
