<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/_template.php");


$template = new Template();

$template->returnHeader();

include_once("/var/www/gw2/_php/DataAccess/DataAccess.php");
$data = new DataAccess();

$sections = [];
$todos = [];

foreach ($data->todos()->getTodoSections() as $rec) {
    array_push($sections, json_decode($rec->toString()));
    $todos[$rec->name()] = [];
}

foreach ($data->todos()->getTodos() as $rec) {
    array_push($todos[$rec->section()], json_decode($rec->toString()));
}

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
            <?php
            foreach ($sections as $section) {
            ?>
                <li><a href="#manual-<?php echo $section->name; ?>"><?php echo $section->name; ?></a></li>
            <?php
            }
            ?>
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

<div class="manual">
    <?php
    foreach ($sections as $section) {
    ?>
        <div>
            <h4 id="manual-<?php echo $section->name; ?>">
                <?php
                if (isset($section->url)) {
                ?>
                    <a href="<?php echo $section->url; ?>" target="_blank"><?php echo $section->name; ?></a>
                <?php
                } else {
                    echo $section->name;
                }
                ?>
            </h4>

            <div class="form-group table-manual">
                <?php
                foreach ($todos[$section->name] as $rec) {
                ?>
                    <div data-id="row" data-key="<?php echo $rec->identifier; ?>">
                        <div data-id="type"><?php echo ucwords($rec->type); ?></div>
                        <div><a data-id="manual" data-value="<?php echo $rec->identifier; ?>" href="javascript:void(0)">Checking..</a></div>
                        <div data-id="name"><?php echo $rec->name; ?></div>
                    </div>
                <?php
                }
                ?>
            </div>

            <div class="form-group">
                <a href="#top">Top</a>
            </div>
        </div>
    <?php
    }
    ?>
</div>

<?php

$template->returnFooter();
