<?php

$return = "/";
if (isset($_GET['return']))
    $return = $_GET['return'];

if (isset($_GET['api_key'])) {
    header('Location: ' . $return . "?api_key=" . $_GET['api_key']);
    die();
}

include_once($_SERVER['DOCUMENT_ROOT'] . "/_template.php");


$template = new Template();

$template->returnHeader();

?>

<h2>Login</h2>

<form method="get">
    <input type="hidden" name="return" value="<?php echo htmlentities($return); ?>" />

    <div class="form-group">
        <label for="api_key" class="form-control">API Key</label>
        <input type="text" name="api_key" class="form-control" />
    </div>

    <div class="form-group">
        <input type="submit" value="Login" class="button primary" />
    </div>
</form>

<?php

$template->returnFooter();
