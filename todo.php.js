var daily_reset = returnDailyReset();
var weekly_reset = returnWeeklyReset();

document.getElementById("daily-time").innerText =
  daily_reset.toLocaleTimeString([], {
    hour: "numeric",
    minute: "2-digit",
  });
document.getElementById("weekly-time").innerText =
  weekly_reset.toLocaleDateString([], {
    weekday: "long",
  }) +
  " " +
  weekly_reset.toLocaleTimeString([], {
    hour: "numeric",
    minute: "2-digit",
  });

var daily_remaining = Math.abs(daily_reset - today);
var weekly_remaining = Math.abs(weekly_reset - today);

document.getElementById("daily-remaining").innerText = secondsToTime(
  daily_remaining / 1000,
);
document.getElementById("weekly-remaining").innerText = secondsToTime(
  weekly_remaining / 1000,
);

//

var loader = document.getElementById("loader");
ShowLoader(loader);

var previous_daily_reset = new Date(daily_reset);
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
  var [response1, response2, response3, response4] = await Promise.all([
    fetch(
      "https://api.guildwars2.com/v2/account/wizardsvault/daily?access_token=" +
        api_key +
        "&" +
        caching,
      {
        method: "GET",
      },
    ),
    fetch(
      "https://api.guildwars2.com/v2/account/wizardsvault/weekly?access_token=" +
        api_key +
        "&" +
        caching,
      {
        method: "GET",
      },
    ),
    fetch(
      "https://api.guildwars2.com/v2/account/wizardsvault/special?access_token=" +
        api_key +
        "&" +
        caching,
      {
        method: "GET",
      },
    ),
    fetch(
      "https://api.guildwars2.com/v2/account/dailycrafting?access_token=" +
        api_key +
        "&" +
        caching,
      {
        method: "GET",
      },
    ),
  ]);

  var data1 = await response1.json();
  buildWV("daily", data1);

  var data2 = await response2.json();
  buildWV("weekly", data2);

  var data3 = await response3.json();
  buildWV("special", data3);

  var data4 = await response4.json();
  buildCrafting(data4);

  HideLoader(loader);
}

function buildWV(section, data) {
  var progress = document.getElementById("wz" + section + "-status");
  if (progress) {
    if (data.meta_progress_complete == data.meta_progress_current) {
      progress.textContent = "Complete!";
    } else {
      progress.textContent = "Incomplete";
    }
  }
  data.objectives.forEach((rec) => {
    var complete = "No";
    if (rec.progress_complete && rec.progress_current)
      complete = rec.progress_current + " of " + rec.progress_complete;
    if (rec.claimed) complete = "Yes";

    var template = document.getElementById("template-wv");

    var clone = template.content.cloneNode(true);
    if (complete === "Yes") clone.querySelector("tr").classList.add("complete");
    clone.querySelector("[data-id='complete']").textContent = complete;
    clone.querySelector("[data-id='type']").textContent = rec.track;
    clone.querySelector("[data-id='name']").textContent = rec.title;

    document.querySelector("#wv" + section + " tbody").append(clone);
  });
}

function buildCrafting(data) {
  var rows = document.querySelectorAll("table[data-id='crafting'] tbody tr");
  rows.forEach((row) => {
    var complete = row.querySelector("[data-id='complete']");
    if (data.includes(row.id)) {
      row.classList.add("complete");
      complete.innerText = "Yes";
    } else {
      complete.innerText = "No";
    }
  });
}

//

var storage =
  today.getUTCFullYear() +
  "-" +
  (today.getUTCMonth() + 1) +
  "-" +
  today.getUTCDate();

var storageDailyName = "todo_daily-" + storage;

var storageDaily = [];

if (getStorage(storageDailyName) === null) {
  setStorage(storageDailyName, storageDaily);
} else {
  storageDaily = JSON.parse(getStorage(storageDailyName));
}

storageDaily.forEach((rec) => {
  document.querySelectorAll("[data-key='" + rec + "']").forEach((el) => {
    el.classList.add("complete");
  });
});

document.querySelectorAll("[data-id='daily']").forEach((el) => {
  var value = el.getAttribute("data-value");
  var rec = storageDaily.indexOf(value);
  document.querySelectorAll("[data-key='" + value + "']").forEach((el) => {
    if (rec > -1) {
      el.classList.add("complete");
    } else {
      el.classList.remove("complete");
    }
  });
  document.querySelectorAll("[data-value='" + value + "']").forEach((el) => {
    if (rec > -1) {
      el.textContent = "Yes";
    } else {
      el.textContent = "No";
    }
  });

  el.addEventListener("click", toggleComplete);
});

function toggleComplete(e) {
  var val = e.target.getAttribute("data-value");
  var rec = storageDaily.indexOf(val);
  var add = true;
  if (rec > -1) {
    storageDaily.splice(rec, 1);
    add = false;
  } else {
    storageDaily.push(val);
  }

  setStorage(storageDailyName, storageDaily);

  document.querySelectorAll("[data-key='" + val + "']").forEach((el) => {
    if (add) {
      el.classList.add("complete");
    } else {
      el.classList.remove("complete");
    }
  });
  document.querySelectorAll("[data-value='" + val + "']").forEach((el) => {
    if (add) {
      el.textContent = "Yes";
    } else {
      el.textContent = "No";
    }
  });
}

//

var previous_weekly_reset = new Date(weekly_reset);
previous_weekly_reset.setUTCDate(weekly_reset.getUTCDate() - 7);

var storage =
  previous_weekly_reset.getUTCFullYear() +
  "-" +
  (previous_weekly_reset.getUTCMonth() + 1) +
  "-" +
  previous_weekly_reset.getUTCDate();

var storageWeeklyName = "todo_weekly-" + storage;

var storageWeekly = [];

if (getStorage(storageWeeklyName) === null) {
  setStorage(storageWeeklyName, storageWeekly);
} else {
  storageWeekly = JSON.parse(getStorage(storageWeeklyName));
}

storageWeekly.forEach((rec) => {
  document.querySelectorAll("[data-key='" + rec + "']").forEach((el) => {
    el.classList.add("complete");
  });
});

document.querySelectorAll("[data-id='weekly']").forEach((el) => {
  var value = el.getAttribute("data-value");
  var rec = storageWeekly.indexOf(value);
  document.querySelectorAll("[data-key='" + value + "']").forEach((el) => {
    if (rec > -1) {
      el.classList.add("complete");
    } else {
      el.classList.remove("complete");
    }
  });
  document.querySelectorAll("[data-value='" + value + "']").forEach((el) => {
    if (rec > -1) {
      el.textContent = "Yes";
    } else {
      el.textContent = "No";
    }
  });

  el.addEventListener("click", toggleCompleteWeekly);
});

function toggleCompleteWeekly(e) {
  var val = e.target.getAttribute("data-value");
  var rec = storageWeekly.indexOf(val);
  var add = true;
  if (rec > -1) {
    storageWeekly.splice(rec, 1);
    add = false;
  } else {
    storageWeekly.push(val);
  }

  setStorage(storageWeeklyName, storageWeekly);

  document.querySelectorAll("[data-key='" + val + "']").forEach((el) => {
    if (add) {
      el.classList.add("complete");
    } else {
      el.classList.remove("complete");
    }
  });
  document.querySelectorAll("[data-value='" + val + "']").forEach((el) => {
    if (add) {
      el.textContent = "Yes";
    } else {
      el.textContent = "No";
    }
  });
}
