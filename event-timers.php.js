var today = new Date();

var storageName =
  today.getUTCFullYear() +
  "-" +
  (today.getUTCMonth() + 1) +
  "-" +
  today.getUTCDate();

var storageManualName = "event_manual-" + storageName;
var storageAutoName = "event_auto-" + storageName;

var storageManual = [];
var storageAuto = [];

function setStorage(name, data) {
  localStorage.setItem(name, JSON.stringify(data));
}

if (localStorage.getItem(storageManualName) === null) {
  setStorage(storageManualName, storageManual);
} else {
  storageManual = JSON.parse(localStorage.getItem(storageManualName));
}
if (localStorage.getItem(storageAutoName) === null) {
  setStorage(storageAutoName, storageAuto);
} else {
  storageAuto = JSON.parse(localStorage.getItem(storageAutoName));
}

var response = fetch("/_data/event-timers.json", {
  method: "GET",
})
  .then((response) => response.json())
  .then((data) => {
    var table_next = document.getElementById("next");
    var table_all = document.getElementById("all");

    var template_next = document.getElementById("template_next");
    var template_all = document.getElementById("template_all");

    var template_next_section = document.getElementById(
      "template_next_section",
    );
    var template_all_section = document.getElementById("template_all_section");

    var section_all = "";

    var nextList = [];

    // process data
    data.forEach((val) => {
      if (section_all != val.section) {
        section_all = val.section;
        var clone = template_all_section.content.cloneNode(true);
        clone.querySelector("[data-id='section']").innerText = section_all;
        table_all.querySelector("tbody").append(clone);
      }

      // build occurrences
      val.times.forEach((time) => {
        var t = time.split(":");
        var o = new Date();
        o.setUTCHours(t[0], t[1]);
        if (o < today) o.setHours(o.getHours() + 24);
        // console.log(time, o);
        val.occurrences.push(o);
      });
      val.occurrences.sort((a, b) => a - b);

      // build all event table
      var clone = template_all.content.cloneNode(true);
      clone.querySelector("[data-id='name'] [data-id='val']").innerText =
        val.name;
      clone.querySelector("[data-id='name'] [data-id='val']").href = val.url;
      clone.querySelector("[data-id='next']").innerText =
        val.occurrences[0].toLocaleTimeString([], {
          hour: "numeric",
          minute: "2-digit",
        });
      var occurrences = "";
      val.occurrences.forEach((o, i) => {
        if (i > 0) {
          if (occurrences != "") occurrences += ", ";
          occurrences += o.toLocaleTimeString([], {
            hour: "numeric",
            minute: "2-digit",
          });
        }
      });
      clone.querySelector("[data-id='others']").innerText = occurrences;

      var complete = false;
      var row = clone.querySelector("tr");

      if (Object.hasOwn(val, "manual")) {
        if (!storageManual.find((obj) => obj.key === val.manual)) {
          storageManual.push({ key: val.manual, value: false });
          setStorage(storageManualName, storageManual);
        }
        clone.querySelector("[data-id='name'] [data-id='complete']").innerText =
          "Toggle Completion";
        clone
          .querySelector("[data-id='name'] [data-id='complete']")
          .setAttribute("data-value", val.manual);

        row.setAttribute("data-key", val.manual);

        complete = storageManual.find((obj) => obj.key === val.manual).value;
      } else {
        if (!storageAuto.find((obj) => obj.key === val.id)) {
          storageAuto.push({ key: val.id, value: false });
          setStorage(storageAutoName, storageAuto);
        }

        clone
          .querySelector("[data-id='name'] [data-id='complete']")
          .parentElement.remove();

        row.setAttribute("data-key", val.id);

        complete = storageAuto.find((obj) => obj.key === val.id).value;
      }

      if (complete) {
        row.classList.add("complete");
      } else {
        row.classList.remove("complete");
      }

      table_all.querySelector("tbody").append(clone);

      // add to next list with only the next occurrence
      var ob = {
        section: val.section,
        name: val.name,
        next: val.occurrences[0],
        url: val.url,
      };
      if (Object.hasOwn(val, "manual")) {
        ob.manual = val.manual;
      } else {
        ob.id = val.id;
      }
      nextList.push(ob);
    });

    // build next table
    var section_next = "";
    nextList.sort((a, b) => a.next - b.next);
    nextList.forEach((val) => {
      var next = val.next.toLocaleTimeString([], {
        hour: "numeric",
        minute: "2-digit",
      });

      if (section_next != next) {
        section_next = next;
        var clone = template_next_section.content.cloneNode(true);
        table_next.querySelector("tbody").append(clone);
      }
      var clone = template_next.content.cloneNode(true);
      clone.querySelector("[data-id='section']").innerText = val.section;
      clone.querySelector("[data-id='name'] [data-id='val']").innerText =
        val.name;
      clone.querySelector("[data-id='name'] [data-id='val']").href = val.url;
      clone.querySelector("[data-id='next']").innerText = next;

      var complete = false;
      var row = clone.querySelector("tr");

      if (Object.hasOwn(val, "manual")) {
        clone.querySelector("[data-id='name'] [data-id='complete']").innerText =
          "Toggle Completion";
        clone
          .querySelector("[data-id='name'] [data-id='complete']")
          .setAttribute("data-value", val.manual);

        row.setAttribute("data-key", val.manual);

        complete = storageManual.find((obj) => obj.key === val.manual).value;
      } else {
        clone
          .querySelector("[data-id='name'] [data-id='complete']")
          .parentElement.remove();

        row.setAttribute("data-key", val.id);

        complete = storageAuto.find((obj) => obj.key === val.id).value;
      }

      if (complete) {
        row.classList.add("complete");
      } else {
        row.classList.remove("complete");
      }

      table_next.querySelector("tbody").append(clone);
    });

    // console.log(data);

    document.querySelectorAll("[data-id='complete']").forEach((el) => {
      el.addEventListener("click", toggleComplete);
    });
  });

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
      storageAuto.find((obj) => obj.key === rec).value = true;
      document.querySelectorAll("[data-key='" + val + "']").forEach((el) => {
        el.classList.add("complete");
      });
    });
    setStorage(storageAutoName, storageAuto);
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
      storageAuto.find((obj) => obj.key === rec).value = true;
      document.querySelectorAll("[data-key='" + val + "']").forEach((el) => {
        el.classList.add("complete");
      });
    });
    setStorage(storageAutoName, storageAuto);
  });

//

function toggleComplete(e) {
  var val = e.target.getAttribute("data-value");
  var rec = storageManual.find((obj) => obj.key === val);

  if (rec.value) {
    rec.value = false;
  } else {
    rec.value = true;
  }

  setStorage(storageManualName, storageManual);

  document.querySelectorAll("[data-key='" + val + "']").forEach((el) => {
    if (rec.value) {
      el.classList.add("complete");
    } else {
      el.classList.remove("complete");
    }
  });
}
