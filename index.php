<?php
// nuskaitome konfigūracijų failą
include 'config.php';

// iškviečiame prisijungimo prie duomenų bazės klasę
include 'utils/mysql.class.php';

// nustatome pasirinktą modulį
$module = '';
if (isset($_GET['module'])) {
    $module = mysql::escape($_GET['module']);
}

// jeigu pasirinktas elementas (sutartis, filmas ir kt.), nustatome elemento id
$id = '';
if (isset($_GET['id'])) {
    $id = mysql::escape($_GET['id']);
}

// nustatome, ar kuriamas naujas elementas
$action = '';
if (isset($_GET['action'])) {
    $action = mysql::escape($_GET['action']);
}

// jeigu šalinamas elementas, nustatome šalinamo elemento id
$removeId = 0;
if (!empty($_GET['remove'])) {
    // paruošiame $_GET masyvo id reikšmę SQL užklausai
    $removeId = mysql::escape($_GET['remove']);
}

// nustatome elementų sąrašo puslapio numerį
$pageId = 1;
if (!empty($_GET['page'])) {
    $pageId = mysql::escape($_GET['page']);
}

// nustatome, kiek įrašų rodysime elementų sąraše
define('NUMBER_OF_ROWS_IN_PAGE', 10);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
</head>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="robots" content="noindex">
    <title>Filmų nuomos IS</title>
    <link rel="stylesheet" type="text/css" href="scripts/datetimepicker/jquery.datetimepicker.css" media="screen"/>
    <link rel="stylesheet" type="text/css" href="style/main.css" media="screen"/>
    <script type="text/javascript" src="scripts/jquery-1.12.0.min.js"></script>
    <script type="text/javascript" src="scripts/datetimepicker/jquery.datetimepicker.full.min.js"></script>
    <script type="text/javascript" src="scripts/main.js"></script>
</head>
<body>
<div id="body">
    <div id="header">
        <h3 id="slogan"><a href="index.php"><?php echo config::TITLE;?></a></h3>
    </div>
    <div id="content">
        <div id="topMenu">
            <ul class="float-left">
                <?php
                foreach (config::NAV_LINKS as $key => $val) {
                    echo "<li><a href='index.php?module=" . $key . "'" . (($module == $key) ? ' class="active"' : '') . ">" . $val . "</a></li>";
                } ?>
            </ul>
        </div>
        <div id="contentMain">
            <?php
            if (!empty($module)) {
                if (empty($id) && empty($action)) {
                    include "controls/abstract_list.php";
                } else {
                    include "controls/{$module}_edit.php";
                }
            } else {
                include "landing.php";
            }
            ?>
            <div class="float-clear"></div>
        </div>
    </div>
    <div id="footer">
        <div style="text-align: center; padding: 10px">&copy;2016 Jurgis Gečys IF-4/8</div>
    </div>
</div>
</body>
</html>