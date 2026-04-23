<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/_template.php");


$template = new Template();

$template->returnHeader();

?>

<h2>Account</h2>

<div class="form-group">
    <label class="form-control">API Key</label>
    <div class="form-control" id="api_key"></div>
</div>

<h3>Information</h3>

<div class="form-group">
    <label class="form-control">Name</label>
    <div class="" id="name"></div>
</div>
<div class="form-group">
    <label class="form-control">Created</label>
    <div class="" id="created"></div>
</div>
<div class="form-group">
    <label class="form-control">Last Modified</label>
    <div class="" id="modified"></div>
</div>
<div class="form-group">
    <label class="form-control">Account Age</label>
    <div class="" id="age"></div>
</div>
<div class="form-group">
    <label class="form-control">Total Gameplay</label>
    <div class="" id="gameplay"></div>
</div>

<h3>Expansions</h3>

<ul id="expansions"></ul>

<?php

$template->returnFooter();
