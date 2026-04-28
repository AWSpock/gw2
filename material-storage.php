<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/_template.php");


$template = new Template();

$template->returnHeader();

?>

<h2>Material Storage</h2>

<div class="form-group" style="display:none;">
    <input type="text" class="form-control" placeholder="Search.." id="txtSearch" />
</div>
<div id="container"></div>

<template id="template-section">
    <h3></h3>
</template>

<template id="template-box">
    <div class="box-10" data-id="box"></div>
</template>

<template id="template-item">
    <div class="box" data-id="item">
        <a target="_blank">
            <img class="icon big" />
        </a>
        <div data-id="amount"></div>
    </div>
</template>

<template id="template-item-empty">
    <div class="box" data-id="item">
        <div class="empty"></div>
        <div class="invisible">NULL</div>
    </div>
</template>

<?php

$template->returnFooter();
