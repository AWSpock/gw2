<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/_template.php");

$template = new Template();

$template->returnHeader();

?>

<h2>Price Compare</h2>

<ul>
    <?php
    foreach (scandir(__DIR__ . "/price-compare") as $file) {
        if ($file === '.' || $file === '..')
            continue;

        $filename = str_replace("-", " ", pathinfo($file, PATHINFO_FILENAME));
    ?>
        <li><a href="price-compare/<?php echo $file;
                                    $template->echoApiKeyLink(); ?>"><?php echo $filename; ?></a>
        <?php
    }
        ?>
</ul>

<?php

$template->returnFooter();
