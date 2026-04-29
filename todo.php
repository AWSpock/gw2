<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/_template.php");


$template = new Template();

$template->returnHeader();

$todos = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/_data/todos-daily.json"));
$todoWs = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/_data/todos-weekly.json"));

?>

<h2>To Do Lists</h2>

<div class="alert alert-info" id="play-message" style="display:none;">
    <p>You have not played today!</p>
</div>

<ul id="bookmarks">
    <li><a href="#resets">Resets</a></li>
    <li><a href="#wizards-vault">Wizards Vault</a>
        <ul>
            <li><a href="#wizards-vault-daily">Daily</a></li>
            <li><a href="#wizards-vault-weekly">Weekly</a></li>
            <li><a href="#wizards-vault-special">Special</a></li>
        </ul>
    </li>
    <li><a href="#crafting">Crafting</a></li>
    <li><a href="#manual">Manual</a>
        <ul>
            <li><a href="#manual-daily">Daily</a>
                <ul id="bookmarks-manual-daily"></ul>
            </li>
            <li><a href="#manual-weekly">Weekly</a>
                <ul id="bookmarks-manual-weekly"></ul>
            </li>
        </ul>
    </li>
</ul>
<template id="template-bookmark">
    <li><a></a></li>
</template>

<h3 id="resets">Resets</h3>
<p><a href="https://wiki.guildwars2.com/wiki/Server_reset" target="_blank">Wiki Information</a></p>

<table class="center form-group">
    <thead>
        <tr>
            <th>Daily</th>
            <th>Weekly</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td id="daily-time"></td>
            <td id="weekly-time"></td>
        </tr>
        <tr>
            <td id="daily-remaining"></td>
            <td id="weekly-remaining"></td>
        </tr>
    </tbody>
</table>

<div class="form-group">
    <a href="#top">Top</a>
</div>

<h3 id="wizards-vault">Wizards Vault</h3>

<div class="wizards-vault">
    <div class="wizards-vault-daily">
        <h4 id="wizards-vault-daily">Daily</h4>
        <p><a href="https://wiki.guildwars2.com/wiki/Wizard%27s_Vault#Daily" target="_blank">Wiki Information</a></p>

        <div class="form-group">
            <span class="alert alert-info"><strong>Overal Status: <span id="wzdaily-status">Checking..</span></strong></span>
        </div>
        <div id="wvdaily" class="form-group table-wizards-vault"></div>

        <div class="form-group">
            <a href="#top">Top</a>
        </div>
    </div>

    <div class="wizards-vault-weekly">
        <h4 id="wizards-vault-weekly">Weekly</h4>
        <p><a href="https://wiki.guildwars2.com/wiki/Wizard%27s_Vault#Weekly" target="_blank">Wiki Information</a></p>
        <div class="form-group">
            <span class="alert alert-info"><strong>Overal Status: <span id="wzweekly-status">Checking..</span></strong></span>
        </div>
        <div id="wvweekly" class="form-group table-wizards-vault"></div>

        <div class="form-group">
            <a href="#top">Top</a>
        </div>
    </div>

    <div class="wizards-vault-special">
        <h4 id="wizards-vault-special">Special</h4>
        <p><a href="https://wiki.guildwars2.com/wiki/Wizard%27s_Vault#Special" target="_blank">Wiki Information</a></p>
        <div id="wvspecial" class="form-group table-wizards-vault"></div>

        <div class="form-group">
            <a href="#top">Top</a>
        </div>
    </div>
</div>

<template id="template-wv">
    <div data-id="row">
        <div data-id="complete"></div>
        <div data-id="type"></div>
        <div data-id="name"></div>
    </div>
</template>


<h3 id="crafting">Crafting</h3>
<div data-id="crafting" class="form-group table-crafting">
    <div id="charged_quartz_crystal" data-id="row">
        <div>Charged Quartz Crystal</div>
    </div>
    <div id="glob_of_elder_spirit_residue" data-id="row">
        <div>Glob of Elder Spirit Residue</div>
    </div>
    <div id="lump_of_mithrilium" data-id="row">
        <div>Lump of Mithrilium</div>
    </div>
    <div id="spool_of_silk_weaving_thread" data-id="row">
        <div>Spool of Silk Weaving Thread</div>
    </div>
    <div id="spool_of_thick_elonian_cord" data-id="row">
        <div>Spool of Thick Elonian Cord</div>
    </div>
</div>

<div class="form-group">
    <a href="#top">Top</a>
</div>


<h3 id="manual">Manual</h3>

<h4 id="manual-daily">Daily</h4>
<div class="manual-daily">
    <?php
    $section = "";
    foreach ($todos as $todo) {
        if ($section !== $todo->section) {
            if ($section !== "") {
    ?>
</div>
<?php
            }
            $section = $todo->section;
?>
<div class="form-group table-manual-daily">
    <h5 data-id="manual-daily" id="manual-daily-<?php echo $section; ?>"><?php echo $section; ?></h5>
<?php
        }
?>
<div data-id="row" data-key="<?php echo $todo->id; ?>">
    <div><a data-id="daily" data-value="<?php echo $todo->id; ?>" href="javascript:void(0)">Checking..</a></div>
    <div data-id="name"><?php echo $todo->name; ?></div>
</div>
<?php
    }
    if (count($todos) > 0) {
?>
</div>
<?php
    }
?>
</div>

<div class="form-group">
    <a href="#top">Top</a>
</div>

<h4 id="manual-weekly">Weekly</h4>
<div class="manual-weekly">
    <?php
    $section = "";
    foreach ($todoWs as $todo) {
        if ($section !== $todo->section) {
            if ($section != "") {
    ?>
</div>
<?php
            }
            $section = $todo->section;
?>
<div class="form-group table-manual-weekly">
    <h5 data-id="manual-weekly" id="manual-weekly-<?php echo $section; ?>">
        <?php
            if (property_exists($todo, "url")) {
        ?>
            <a href="<?php echo $todo->url; ?>" target="_blank"><?php echo $section; ?></a>
        <?php
            } else {
                echo $section;
            }
        ?>
    </h5>
<?php
        }
?>
<div data-id="row" data-key="<?php echo $todo->id; ?>">
    <div><a data-id="weekly" data-value="<?php echo $todo->id; ?>" href="javascript:void(0)">Checking..</a></div>
    <div data-id="name"><?php echo $todo->name; ?></div>
</div>
<?php
    }
    if (count($todos) > 0) {
?>
</div>
<?php
    }
?>
</div>

<div class="form-group">
    <a href="#top">Top</a>
</div>

<?php

$template->returnFooter();
