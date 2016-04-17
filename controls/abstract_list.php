<?php
/**
 * Created by PhpStorm.
 * User: jgecy
 * Date: 2016-04-16
 * Time: 16:06
 */

// sukuriame markių klasės objektą
include 'libraries/zanrai.class.php';
include 'libraries/serijos.class.php';
include 'libraries/filmai.class.php';
include 'libraries/ivertinimai.class.php';
include 'libraries/miestai.class.php';
include 'libraries/padaliniai.class.php';
include 'libraries/darbuotojai.class.php';

function getLib($module)
{
    switch ($module) {
        case 'zanrai':
            return new zanrai();
        case 'serijos':
            return new serijos();
        case 'filmai':
            return new filmai();
        case 'ivertinimai':
            return new ivertinimai();
        case 'miestai':
            return new miestai();
        case 'padaliniai':
            return new padaliniai();
        case 'darbuotojai':
            return new darbuotojai();
        default:
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