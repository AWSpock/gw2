<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/_template.php");


$template = new Template();

$template->returnHeader();

include_once("/var/www/gw2/_php/DataAccess/DataAccess.php");
$data = new DataAccess();

$sections = [];
$items = [];

foreach ($data->pwtes()->getSections() as $rec) {
    array_push($sections, json_decode($rec->toString()));
    $items[$rec->name()] = [];
}

foreach ($data->pwtes()->getItems() as $rec) {
    array_push($items[$rec->section()], json_decode($rec->toString()));
}

?>

<h2>Portable Wizard's Tower Exchange</h2>

<ul id="bookmarks">
    <?php
    foreach ($sections as $section) {
    ?>
        <li><a href="#<?php echo $section->name; ?>"><?php echo $section->name; ?></a></li>
    <?php
    }
    ?>
</ul>

<?php
foreach ($sections as $section) {
?>
    <div class="section">
        <h4 id="<?php echo $section->name; ?>"><?php echo $section->name; ?></h4>
        <?php
        if (isset($section->url)) {
        ?>
            <p><a href="<?php echo $section->url; ?>" target="_blank">Wiki Information</a></p>
        <?php
        }
        ?>

        <div class="form-group table">
            <?php
            foreach ($items[$section->name] as $item) {
            ?>
                <div data-id="row" data-item="<?php echo $item->api_id; ?>">
                    <div data-id="img"><img></div>
                    <div data-id="name"><a>Loading..</a></div>
                    <div data-id="amount">Loading..</div>
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
