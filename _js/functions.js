function ShowLoader(div) {
  div.innerHTML =
    "<div class='loading'><div class='lds-ring'><div></div><div></div><div></div><div></div></div>LOADING</div>";
}
function HideLoader(div) {
  div.innerHTML = "";
}

//

var formatter2 = new Intl.NumberFormat("en-US", {
  style: "currency",
  currency: "USD",
});
var formatter3 = new Intl.NumberFormat("en-US", {
  style: "currency",
  currency: "USD",
  maximumFractionDigits: 3,
});
var formatterGal = new Intl.NumberFormat("en-US", {
  style: "unit",
  unit: "gallon",
  maximumFractionDigits: 3,
});
var formatterMile = new Intl.NumberFormat("en-US", {
  style: "unit",
  unit: "mile",
  maximumFractionDigits: 3,
});
var formatterInt = new Intl.NumberFormat("en-US", {
  maximumFractionDigits: 0,
});

//

function calculateCoin(balance) {
  var nf = new Intl.NumberFormat("en-US");
  var gold = 0;
  var silver = 0;
  var copper = 0;

  var neg = false;
  if (balance < 0) {
    balance *= -1;
    neg = true;
  }

  if (balance > 10000) {
    gold = Math.trunc(balance / 10000);
    balance = balance - gold * 10000;
  }
  if (balance > 100) {
    silver = Math.trunc(balance / 100);
    balance = balance - silver * 100;
  }
  copper = balance;

  var val = "";
  if (gold > 0) val += nf.format(gold) + " Gold ";
  if (silver > 0) val += silver + " Silver ";
  val += copper + " Copper";
  if (neg) val = "-" + val;
  return val;
}

function formatDate(date) {
  date = new Date(date);
  var part1 = date.toLocaleDateString();
  var part2 = date.toLocaleTimeString([], {
    hour: "2-digit",
    minute: "2-digit",
  });
  return part1 + " " + part2;
}

function secondsToTime(seconds, includeYear = true) {
  var nf = new Intl.NumberFormat("en-US");
  var disp = "";
  var years = Math.floor(seconds / (365 * 24 * 60 * 60));
  if (!includeYear) years = 0;
  if (years > 0) disp += nf.format(years) + " y" + " ";
  seconds = seconds - years * 365 * 24 * 60 * 60;
  var days = Math.floor(seconds / 60 / 60 / 24);
  if (days > 0) disp += nf.format(days) + " d" + " ";
  seconds = seconds - days * 24 * 60 * 60;
  var hours = Math.floor(seconds / 60 / 60);
  if (hours > 0) disp += hours + " hr" + " ";
  seconds = seconds - hours * 60 * 60;
  var minutes = Math.floor(seconds / 60);
  if (minutes > 0) disp += minutes + " min" + " ";
  seconds = seconds - minutes * 60;
  // seconds = Math.floor(seconds);
  // if (seconds > 0) disp += seconds + " s" + " ";
  return disp;
}
