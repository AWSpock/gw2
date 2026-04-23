<?php

class Template
{

    public $global;
    private $current_page;
    private $public_pages;

    public function __construct()
    {
        $this->global = new stdClass();
        $this->global->api_key = "";

        $this->public_pages = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/_data/public-pages.json"));
        if (isset($_GET['api_key'])) {
            $this->global->api_key = $_GET['api_key'];
        }

        $this->current_page = strtok($_SERVER['REQUEST_URI'], "?");
        if ($this->current_page == "/")
            $this->current_page = "/index.php";
    }

    private function IncludeCSS($file)
    {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $file)) {
            return "<link rel='stylesheet' type='text/css' href='" . $file . "?v=" . filemtime($_SERVER['DOCUMENT_ROOT'] . $file) . "' />\n";
        }
    }
    private function IncludeJS($file)
    {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $file)) {
            return "<script src='" . $file . "?v=" . filemtime($_SERVER['DOCUMENT_ROOT'] . $file) . "'></script>\n";
        }
    }
    private function echoApiKeyLink()
    {
        echo isset($this->global->api_key) ? "?api_key=" . $this->global->api_key : "";
    }

    public function returnHeader()
    {

        $uri = $_SERVER['REQUEST_URI'];
        $uri = explode("?", $uri)[0];

        if (!in_array($uri, $this->public_pages) && $this->global->api_key == "") {
            header("Location: /login.php?return=" . $uri);
        }

?>

        <!DOCTYPE html>
        <html>

        <head>
            <title>GW2 | Spockfamily</title>
            <link rel="icon" href="/_img/icon.ico" type="image/x-icon">


            <?php //echo $this->IncludeCSS("/css/loading.css"); 
            ?>

            <?php
            echo $this->IncludeCSS("/_css/theme.css");
            echo $this->IncludeCSS("/_css/hover.css");
            echo $this->IncludeCSS("/_css/layout.css");
            echo $this->IncludeCSS("/_css/form.css");
            echo $this->IncludeCSS("/_css/loading.css");

            echo $this->IncludeCSS($this->current_page . ".css");
            ?>

            <link href='https://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet' type='text/css'>

            <script src="https://kit.fontawesome.com/d3431fa995.js" crossorigin="anonymous"></script>
            <?php //echo $this->IncludeJS("/js/functions.js"); 
            ?>

            <script type="text/javascript"></script>

            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta charset="utf-8" />
        </head>

        <body>
            <header class="header">
                <h1>GW2 Helper</h1>
            </header>
            <nav class="nav">
                <a href="javascript:void(0)">
                    <h2>Menu</h2>
                </a>
                <ul>
                    <li><a href="/<?php $this->echoApiKeyLink(); ?>" class="hvr-icon-buzz-out hvr-bounce-to-right"><i class="fa-solid fa-home hvr-icon"></i>Account</a></li>
                    <li><a href="/todo.php<?php $this->echoApiKeyLink(); ?>" class="hvr-icon-buzz-out hvr-bounce-to-right"><i class="fa-solid fa-list-check hvr-icon"></i>To Do Lists</a></li>
                    <li><a href="/event-timers.php<?php $this->echoApiKeyLink(); ?>" class="hvr-icon-buzz-out hvr-bounce-to-right"><i class="fa-solid fa-clock hvr-icon"></i>Event Timers</a></li>
                </ul>
            </nav>
            <div class="content">
                <div id="loader"></div>
            <?php
        }

        public function returnFooter()
        {
            ?>
            </div>

        </body>

        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->

        <script>
            var api_key = "<?php echo $this->global->api_key; ?>";
            var current_page = "<?php echo $this->current_page; ?>";
        </script>

        <?php
            echo $this->IncludeJS("/_js/layout.js");
            echo $this->IncludeJS("/_js/functions.js");

            echo $this->IncludeJS($this->current_page . ".js");
        ?>

        </html>
<?php
        }
    }
