<?php

include 'libraries/diskai.class.php';
include 'libraries/padaliniai.class.php';
$discs = new diskai();
$padaliniai = new padaliniai();

$formErrors = null;
$fields = array();

// nustatome privalomus laukus
$required = array('kiek_kartu_nuomuota',
    'fk_filmas',
    'fk_vieta');

// maksimalūs leidžiami laukų ilgiai
$maxLengths = array (
    'kiek_kartu_nuomuota' => 11
);

// paspaustas išsaugojimo mygtukas
if(!empty($_POST['submit'])) {
    // nustatome laukų validatorių tipus
    $validations = array (
        'kiek_kartu_nuomuota' => 'positivenumber',
        'fk_filmas' => 'positivenumber',
        'fk_vieta' => 'positivenumber');

    // sukuriame validatoriaus objektą
    include 'utils/validator.class.php';
    $validator = new validator($validations, $required, $maxLengths);

    if($validator->validate($_POST)) {
        // suformuojame laukų reikšmių masyvą SQL užklausai
        $data = $validator->preparePostFieldsForSQL();
        if(isset($data['id'])) {
            // atnaujiname duomenis
            $discs->update($data);
        } else {
            // randame didžiausią markės id duomenų bazėje
            $latestId = $discs->getMaxId();

            // įrašome naują įrašą
            $data['id'] = $latestId + 1;
            $discs->insert($data);
        }

        // nukreipiame į markių puslapį
        header("Location: index.php?module={$module}&success");
        die();
    } else {
        // gauname klaidų pranešimą
        $formErrors = $validator->getErrorHTML();
        // gauname įvestus laukus
        $fields = $_POST;
    }
} else {
    // tikriname, ar nurodytas elemento id. Jeigu taip, išrenkame elemento duomenis ir jais užpildome formos laukus.
    if(!empty($id)) {
        $fields = $discs->get($id);
    }
}
?>
<ul id="pagePath">
    <li><a href="index.php">Pradžia</a></li>
    <li><a href="index.php?module=<?php echo $module; ?>">Diskai</a></li>
    <li><?php if(!empty($id)) echo "Disko redagavimas"; else echo "Naujas diskas"; ?></li>
</ul>
<div class="float-clear"></div>
<div id="formContainer">
    <?php if($formErrors != null) { ?>
        <div class="errorBox">
            Neįvesti arba neteisingai įvesti šie laukai:
            <?php
            echo $formErrors;
            ?>
        </div>
    <?php } ?>
    <form action="" method="post">
        <fieldset>
            <legend>Disko informacija</legend>
            <p>
                <label class="field" for="kiek_kartu_nuomuota">Kiek kartu nuomuota<?php echo in_array('kiek_kartu_nuomuota', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="kiek_kartu_nuomuota" name="kiek_kartu_nuomuota" class="textbox-150" value="<?php echo isset($fields['kiek_kartu_nuomuota']) ? $fields['kiek_kartu_nuomuota'] : ''; ?>">
                <?php if(key_exists('kiek_kartu_nuomuota', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['kiek_kartu_nuomuota']} simb.)</span>"; ?>
            </p>
            <p>
                <label class="field"
                       for="fk_filmas">Filmas<?php echo in_array('fk_filmas', $required) ? '<span> *</span>' : ''; ?></label>
                <select id="fk_filmas" name="fk_filmas" title=''>
                    <?php

                    $seriesList = $discs->getSeriesSorted();

                    foreach ($seriesList as $key => $val) {
                        echo "<optgroup label='{$val['serija']}'>";
                        $filmList = $discs->getFilmsForSeries($val['id']);
                        foreach ($filmList as $key1 => $val1) {

                            $selected = '';
                            if(isset($fields['fk_filmas']) && $fields['fk_filmas'] == $val1['id']){
                                $selected = 'selected';
                            }
                            echo "<option value='{$val1['id']}' {$selected}>{$val1['pavadinimas']}</option>";
                        }

                        echo "</optgroup>";
                    }
                    ?>
                </select>
            </p>
            <p>
                <label class="field"
                       for="fk_vieta">Disko vieta<?php echo in_array('fk_vieta', $required) ? '<span> *</span>' : ''; ?></label>
                <select name="fk_vieta" title=''>
                    <?php

                    $padaliniaiList = $padaliniai->getAll(null, null);

                    foreach ($padaliniaiList as $key => $val) {
                        $selected = '';
                        if(isset($fields['fk_vieta']) && $fields['fk_vieta'] == $val['id']){
                            $selected = 'selected';
                        }
                        echo "<option value='{$val['id']}' {$selected}>{$val['padalinys']}</option>";
                    }
                    ?>
                </select>
            </p>
        </fieldset>
        <p class="required-note">* pažymėtus laukus užpildyti privaloma</p>
        <p>
            <input type="submit" class="submit" name="submit" value="Išsaugoti">
        </p>
        <?php if(isset($fields['id'])) { ?>
            <input type="hidden" name="id" value="<?php echo $fields['id']; ?>" />
        <?php } ?>
    </form>
</div>