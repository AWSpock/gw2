<?php

$view_bag = (object) [
    'Browser Title' => 'GW2 | Spockfamily',
    'Title' => 'Account',
    'Menu' => 'Menu'
];

include($_SERVER['DOCUMENT_ROOT'] . "/src/Templates/Header.php");

?>

<div id="loader"></div>

<div id="data">
    <h3>Information</h3>

    <div class="row form-group">
        <label>Name:</label>
        <div id="dvName"></div>
    </div>

    <div class="row form-group">
        <label>Created:</label>
        <div id="dvCreated"></div>
    </div>

    <div class="row form-group">
        <label>Account Age</label>
        <div id="dvAge"></div>
    </div>

    <div class="row form-group">
        <label>Total Gameplay:</label>
        <div id="dvGameplay"></div>
        <div class="row"><a title="View History" href="/gameplay-history.php?api_key=<?php echo $global->api_key; ?>" target="_blank">History</a></div>
    </div>
</div>

<h3>API Key</h3>

<div class="row form-group">
    <div id="dvApiKey"></div>
</div>
</div>

<script>
    var loader = document.getElementById("loader");
    ShowLoader(loader);
    var dvData = document.getElementById("data");
    dvData.style.display = "none";

    var nf = new Intl.NumberFormat('en-US');

    var response = fetch("https://api.guildwars2.com/v2/account?v=latest&access_token=<?php echo $global->api_key; ?>", {
            method: "GET"
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById("dvName").innerText = data.name;
            var created = new Date(data.created);
            var disp = created.getFullYear() + "-";
            disp += (created.getMonth() < 9 ? "0" : "") + (created.getMonth() + 1) + "-";
            disp += (created.getDate() < 10 ? "0" : "") + created.getDate() + " ";
            disp += (created.getHours() == 0 ? "12" : (created.getHours() > 12 ? created.getHours() - 12 : (created.getHours() < 10 ? "0" + created.getHours() : created.getHours()))) + ":";
            disp += (created.getMinutes() < 10 ? "0" : "") + created.getMinutes();
            disp += (created.getHours() == 0 ? "AM" : created.getHours() > 11 ? "PM" : "AM");
            document.getElementById("dvCreated").innerText = disp;

            var dispAge = "";
            var today = new Date();
            var Age = (today.getTime() - created.getTime()) / 1000;

            var years = Math.floor(Age / (365 * 24 * 60 * 60));
            if (years > 0)
                dispAge += nf.format(years) + " year" + (years != 1 ? "s" : "") + " ";
            Age = Age - (years * 365 * 24 * 60 * 60);
            var days = Math.floor(Age / 60 / 60 / 24);
            if (days > 0)
                dispAge += nf.format(days) + " day" + (days != 1 ? "s" : "") + " ";
            Age = Age - (days * 24 * 60 * 60);
            var hours = Math.floor(Age / 60 / 60);
            if (hours > 0)
                dispAge += hours + " hour" + (hours != 1 ? "s" : "") + " ";
            Age = Age - (hours * 60 * 60);
            var minutes = Math.floor(Age / 60);
            if (minutes > 0)
                dispAge += minutes + " minute" + (minutes != 1 ? "s" : "") + " ";
            Age = Age - (minutes * 60);
            Age = Math.floor(Age);
            if (Age > 0)
                dispAge += Age + " second" + (Age != 1 ? "s" : "") + " ";
            document.getElementById("dvAge").innerText = dispAge;

            var dispGamePlay = "";
            var GamePlay = data.age;
            var days = Math.floor(GamePlay / 60 / 60 / 24);
            if (days > 0)
                dispGamePlay += nf.format(days) + " day" + (days != 1 ? "s" : "") + " ";
            GamePlay = GamePlay - (days * 24 * 60 * 60);
            var hours = Math.floor(GamePlay / 60 / 60);
            if (hours > 0)
                dispGamePlay += hours + " hour" + (hours != 1 ? "s" : "") + " ";
            GamePlay = GamePlay - (hours * 60 * 60);
            var minutes = Math.floor(GamePlay / 60);
            if (minutes > 0)
                dispGamePlay += minutes + " minute" + (minutes != 1 ? "s" : "") + " ";
            GamePlay = GamePlay - (minutes * 60);
            if (GamePlay > 0)
                dispGamePlay += GamePlay + " second" + (GamePlay != 1 ? "s" : "") + " ";
            document.getElementById("dvGameplay").innerText = dispGamePlay;

            document.getElementById("dvApiKey").innerText = "<?php echo $global->api_key; ?>";
            dvData.style.display = "block";
            HideLoader(loader);

            if (data.id) {
                // ensure created
                var formData = new FormData();
                formData.append("id", data.id);
                formData.append("api_key", "<?php echo $global->api_key; ?>");

                fetch("/api.php/account/<?php echo $global->api_key; ?>", {
                    method: "POST",
                    body: formData
                });
            }
        });
</script>
<?php

include($_SERVER['DOCUMENT_ROOT'] . "/src/Templates/Footer.php");
