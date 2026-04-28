document.getElementById("api_key").innerText = api_key;

var loader = document.getElementById("loader");
ShowLoader(loader);

//

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
    document.getElementById("name").innerText = data.name;
    document.getElementById("created").innerText = formatDate(data.created);
    document.getElementById("modified").innerText = formatDate(
      data.last_modified,
    );
    document.getElementById("age").innerText = secondsToTime(
      (new Date().getTime() - new Date(data.created).getTime()) / 1000,
    );
    document.getElementById("gameplay").innerText = secondsToTime(
      data.age,
      false,
    );

    var response1 = fetch("/_data/expansions.json", {
      method: "GET",
    })
      .then((response1) => response1.json())
      .then((data1) => {
        data.access.forEach((val, index, array) => {
          var li = document.createElement("li");
          li.innerText = data1.find((obj) => obj.key === val).value;
          document.getElementById("expansions").append(li);
        });
      });

    HideLoader(loader);
  });
