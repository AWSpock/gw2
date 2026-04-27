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

<ul>
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
            <li><a href="#manual-daily">Daily</a></li>
            <li><a href="#manual-weekly">Weekly</a></li>
        </ul>
    </li>
</ul>

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

<h4 id="wizards-vault-daily">Daily</h4>
<p><a href="https://wiki.guildwars2.com/wiki/Wizard%27s_Vault#Daily" target="_blank">Wiki Information</a></p>
<div class="form-group">
    <span class="alert alert-info"><strong>Overal Status: <span id="wzdaily-status">Checking..</span></strong></span>
</div>
<table id="wvdaily" class="form-group">
    <thead>
        <tr>
            <th>Complete?</th>
            <th>Type</th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<div class="form-group">
    <a href="#top">Top</a>
</div>

<h4 id="wizards-vault-weekly">Weekly</h4>
<p><a href="https://wiki.guildwars2.com/wiki/Wizard%27s_Vault#Weekly" target="_blank">Wiki Information</a></p>
<div class="form-group">
    <span class="alert alert-info"><strong>Overal Status: <span id="wzweekly-status">Checking..</span></strong></span>
</div>
<table id="wvweekly" class="form-group">
    <thead>
        <tr>
            <th>Complete?</th>
            <th>Type</th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<div class="form-group">
    <a href="#top">Top</a>
</div>

<h4 id="wizards-vault-special">Special</h4>
<p><a href="https://wiki.guildwars2.com/wiki/Wizard%27s_Vault#Special" target="_blank">Wiki Information</a></p>
<div class="form-group">
    <span class="alert alert-info"><strong>Overal Status: <span id="wzspecial-status">Checking..</span></strong></span>
</div>
<table id="wvspecial" class="form-group">
    <thead>
        <tr>
            <th>Complete?</th>
            <th>Type</th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<div class="form-group">
    <a href="#top">Top</a>
</div>

<template id="template-wv">
    <tr>
        <td data-id="complete"></td>
        <td data-id="type"></td>
        <td data-id="name"></td>
    </tr>
</template>

<h3 id="crafting">Crafting</h3>
<table data-id="crafting" class="form-group">
    <thead>
        <tr>
            <th>Complete?</th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody>
        <tr id="charged_quartz_crystal">
            <td data-id="complete">Checking..</td>
            <td>Charged Quartz Crystal</td>
        </tr>
        <tr id="glob_of_elder_spirit_residue">
            <td data-id="complete">Checking..</td>
            <td>Glob of Elder Spirit Residue</td>
        </tr>
        <tr id="lump_of_mithrilium">
            <td data-id="complete">Checking..</td>
            <td>Lump of Mithrilium</td>
        </tr>
        <tr id="spool_of_silk_weaving_thread">
            <td data-id="complete">Checking..</td>
            <td>Spool of Silk Weaving Thread</td>
        </tr>
        <tr id="spool_of_thick_elonian_cord">
            <td data-id="complete">Checking..</td>
            <td>Spool of Thick Elonian Cord</td>
        </tr>
    </tbody>
</table>

<div class="form-group">
    <a href="#top">Top</a>
</div>


<h3 id="manual">Manual</h3>

<h4 id="manual-daily">Daily</h4>
<table class="form-group">
    <thead>
        <tr>
            <th>Complete?</th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $section = "";
        foreach ($todos as $todo) {
            if ($section !== $todo->section) {
                $section = $todo->section;
        ?>
                <tr class="divider">
                    <th colspan="2" data-id="section"><?php echo $section; ?></th>
                </tr>
            <?php
            }
            ?>
            <tr data-key="<?php echo $todo->id; ?>">
                <td><a data-id="daily" data-value="<?php echo $todo->id; ?>" href="javascript:void(0)">Checking..</a></td>
                <td data-id="name"><?php echo $todo->name; ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<div class="form-group">
    <a href="#top">Top</a>
</div>

<h4 id="manual-weekly">Weekly</h4>
<table class="form-group">
    <thead>
        <tr>
            <th>Complete?</th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $section = "";
        foreach ($todoWs as $todo) {
            if ($section !== $todo->section) {
                $section = $todo->section;
        ?>
                <tr class="divider">
                    <th colspan="2" data-id="section">
                        <?php
                        if (property_exists($todo, "url")) {
                        ?>
                            <a href="<?php echo $todo->url; ?>" target="_blank"><?php echo $section; ?></a>
                        <?php
                        } else {
                            echo $section;
                        }
                        ?>
                    </th>
                </tr>
            <?php
            }
            ?>
            <tr data-key="<?php echo $todo->id; ?>">
                <td><a data-id="weekly" data-value="<?php echo $todo->id; ?>" href="javascript:void(0)">Checking..</a></td>
                <td data-id="name"><?php echo $todo->name; ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<div class="form-group">
    <a href="#top">Top</a>
</div>

<?php

$template->returnFooter();
