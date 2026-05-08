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

var previous_daily_reset = new Date(returnDailyReset());
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
  var [response1, response2, response3, response4, response5] =
    await Promise.all([
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
      fetch("/api/todo-completion.php?api_key=" + api_key + "&" + caching, {
        method: "GET",
      }),
    ]);

  var data1 = await response1.json();
  buildWV("daily", data1);

  var data2 = await response2.json();
  buildWV("weekly", data2);

  var data3 = await response3.json();
  buildWV("special", data3);

  var data4 = await response4.json();
  buildCrafting(data4);

  var data5 = await response5.json();
  displayManualCompletion(data5);

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
    if (complete === "Yes")
      clone.querySelector("[data-id='row']").classList.add("complete");
    clone.querySelector("[data-id='complete']").textContent = complete;
    clone.querySelector("[data-id='type']").textContent = rec.track;
    clone.querySelector("[data-id='name']").textContent = rec.title;

    document.querySelector("#wv" + section).append(clone);
  });
}

function buildCrafting(data) {
  var rows = document.querySelectorAll(
    "div[data-id='crafting'] div[data-id='row']",
  );
  rows.forEach((row) => {
    var complete = row.querySelector("[data-id='complete']");
    if (data.includes(row.id)) {
      row.classList.add("complete");
    }
  });
}

function displayManualCompletion(data) {
  // console.log(data);
  document.querySelectorAll("[data-key]").forEach((el) => {
    var value = el.getAttribute("data-key");
    var lnk = el.querySelector("a[data-id][data-value]");

    var complete = false;
    if (data.indexOf(value) > -1) {
      complete = true;
      el.classList.add("complete");
      lnk.textContent = "Yes";
    } else {
      el.classList.remove("complete");
      lnk.textContent = "No";
    }
    // console.log(value, complete);
  });
}

async function toggleComplete(e) {
  var val = e.target.getAttribute("data-value");

  var data = new FormData();
  data.append("identifier", val);

  var response = await fetch(
    "/api/todo-completion.php?api_key=" + api_key + "&" + caching,
    {
      method: "POST",
      body: data,
    },
  );

  if (!response.ok) {
    alert("An Error Occurred");
    throw new Error(`${response.statusText}`);
  }

  fetch("/api/todo-completion.php?api_key=" + api_key + "&" + caching, {
    method: "GET",
  })
    .then((response) => response.json())
    .then((data) => {
      displayManualCompletion(data);
    });
}

document.querySelectorAll("[data-key]").forEach((el) => {
  var lnk = el.querySelector("a[data-id][data-value]");
  lnk.addEventListener("click", toggleComplete);
});
