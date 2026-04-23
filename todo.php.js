var today = new Date();

var daily_reset = new Date();
daily_reset.setUTCHours(24, 0, 0, 0);

var weekly_reset = new Date();
weekly_reset.setUTCDate(
  weekly_reset.getUTCDate() + ((1 + 7 - weekly_reset.getUTCDay()) % 7 || 7),
);
weekly_reset.setUTCHours(7, 30, 0, 0);

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
