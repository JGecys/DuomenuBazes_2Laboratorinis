<?php

function getLib($module)
{
    switch ($module) {
        case 'zanrai':
            include 'libraries/zanrai.class.php';
            return new zanrai();
        case 'serijos':
            include 'libraries/serijos.class.php';
            return new serijos();
        case 'filmai':
            include 'libraries/filmai.class.php';
            return new filmai();
        case 'ivertinimai':
            include 'libraries/ivertinimai.class.php';
            return new ivertinimai();
        case 'miestai':
            include 'libraries/miestai.class.php';
            return new miestai();
        case 'padaliniai':
            include 'libraries/padaliniai.class.php';
            return new padaliniai();
        case 'darbuotojai':
            include 'libraries/darbuotojai.class.php';
            return new darbuotojai();
        case 'klientai':
            include 'libraries/klientai.class.php';
            return new klientai();
        case 'sutartys':
            include 'libraries/sutartys.class.php';
            return new sutartys();
        default:
            include 'libraries/zanrai.class.php';
            return new zanrai();
    }
}

$genres = getLib($module);

// sukuriame puslapiavimo klasės objektą
include 'utils/paging.class.php';
$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

if (!empty($removeId)) {
    // patikriname, ar šalinama markė nepriskirta modeliui
    $count = $genres->getReferenceCount($removeId);

    $removeErrorParameter = '';
    $success = '';
    if ($count == 0) {
        // šaliname markę
        $genres->delete($removeId);
        $success = '&success';
    } else {
        // nepašalinome, nes markė priskirta modeliui, rodome klaidos pranešimą
        $removeErrorParameter = '&remove_error';
    }

    // nukreipiame į markių puslapį
    header("Location: index.php?module={$module}{$removeErrorParameter}{$success}");
    die();
}
?>
    <ul id="pagePath">
        <li><a href="index.php">Pradžia</a></li>
        <li><?php echo $genres->getPath(); ?></li>
    </ul>
    <div id="actions">
        <a href='index.php?module=<?php echo $module; ?>&action=new'><?php echo $genres->getCreateNewMessage(); ?></a>
    </div>
    <div class="float-clear"></div>

<?php if (isset($_GET['remove_error'])) { ?>
    <div class="errorBox">
        <?php echo $genres->getErrorMessage(); ?>
    </div>
<?php } ?>
<?php if (isset($_GET['success'])) { ?>
    <div class="successBox">
        <?php echo $genres->getSuccessMessage(); ?>
    </div>
<?php } ?>

    <div style="overflow-x: auto">
        <table>
            <tr>
                <?php foreach ($genres->getTableHeaders() as $key => $val) {
                    echo "<th>$val</th>";
                } ?>
                <th></th>
            </tr>
            <?php
            // suskaičiuojame bendrą įrašų kiekį
            $elementCount = $genres->getCount();

            // suformuojame sąrašo puslapius
            $paging->process($elementCount, $pageId);

            // išrenkame nurodyto puslapio markes
            $data = $genres->getAll($paging->size, $paging->first);

            // suformuojame lentelę
            foreach ($data as $key => $val) {
                echo
                    "<tr>"
                    . $genres->getRowFromData($val)
                    . "<td>"
                    . "<a href='#' onclick='showConfirmDialog(\"{$module}\", \"{$val[$genres->getId()]}\"); return false;' title=''>šalinti</a>&nbsp;"
                    . "<a href='index.php?module={$module}&id={$val[$genres->getId()]}' title=''>redaguoti</a>"
                    . "</td>"
                    . "</tr>";
            }
            ?>
        </table>
    </div>

<?php
// įtraukiame puslapių šabloną
include 'controls/paging.php';
?>