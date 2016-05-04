<?php

include 'libraries/filmai.class.php';
include 'libraries/serijos.class.php';
include 'libraries/ivertinimai.class.php';
$filmai = new filmai();
$serijos = new serijos();
$ivertinimai = new ivertinimai();

$formErrors = null;
$fields = array();

// nustatome privalomus laukus
$required = array(
    'skirta',
    'fk_serija',
    'pavadinimas',
    'isleidimo_data',
    'musu_ivertinimas',
    'aprasymas',
    'trukme',
    'nuomos_kaina'
);

// maksimalūs leidžiami laukų ilgiai
$maxLengths = array(
    'pavadinimas' => 30,
    'aprasymas' => 1000,
);

// paspaustas išsaugojimo mygtukas
if (!empty($_POST['submit'])) {
    // nustatome laukų validatorių tipus
    $validations = array(
        'pavadinimas' => 'anything',
        'aprasymas' => 'anything',
        'skirta' => 'anything',
        'fk_serija' => 'anything',
        'isleidimo_data' => 'anything',
        'musu_ivertinimas' => 'anything',
        'trukme' => 'anything',
        'nuomos_kaina' => 'anything');

    // sukuriame validatoriaus objektą
    include 'utils/validator.class.php';
    $validator = new validator($validations, $required, $maxLengths);

    if ($validator->validate($_POST)) {
        // suformuojame laukų reikšmių masyvą SQL užklausai
        $data = $validator->preparePostFieldsForSQL();
        if (isset($data['id'])) {
            // atnaujiname duomenis
            $filmai->update($data);
        } else {
            // randame didžiausią markės id duomenų bazėje
            $latestId = $filmai->getMaxId();

            // įrašome naują įrašą
            $data['id'] = $latestId + 1;
            $filmai->insert($data);
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
    if (!empty($id)) {
        $fields = $filmai->get($id);
    }
}
?>
<ul id="pagePath">
    <li><a href="index.php">Pradžia</a></li>
    <li><a href="index.php?module=<?php echo $module; ?>">Filmai</a></li>
    <li><?php if (!empty($id)) echo "Filmo redagavimas"; else echo "Nauajs filmas"; ?></li>
</ul>
<div class="float-clear"></div>
<div id="formContainer">
    <?php if ($formErrors != null) { ?>
        <div class="errorBox">
            Neįvesti arba neteisingai įvesti šie laukai:
            <?php
            echo $formErrors;
            ?>
        </div>
    <?php } ?>
    <form action="" method="post">
        <fieldset>
            <legend>Serijos informacija</legend>
            <p>
                <label class="field"
                       for="pavadinimas">Pavadinimas<?php echo in_array('pavadinimas', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="pavadinimas" name="pavadinimas" class="textbox-150"
                       value="<?php echo isset($fields['pavadinimas']) ? $fields['pavadinimas'] : ''; ?>">
                <?php if (key_exists('pavadinimas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['pavadinimas']} simb.)</span>"; ?>
            </p>
            <p>
                <label class="field"
                       for="serija">Serija<?php echo in_array('fk_serija', $required) ? '<span> *</span>' : ''; ?></label>
                <select id="serija" name="fk_serija" title=''>
                    <?php

                    $seriesList = $serijos->getAll(null, null);

                    foreach ($seriesList as $key => $val) {
                        $selected = '';
                        if (isset($fields['fk_serija']) && $fields['fk_serija'] == $val['id']) {
                            $selected = 'selected';
                        }
                        echo "<option value='{$val['id']}' {$selected}>{$val['serija']}</option>";
                    }
                    ?>
                </select>
            </p>
            <p>
                <label class="field"
                       for="skirta">Skirta<?php echo in_array('skirta', $required) ? '<span> *</span>' : ''; ?></label>
                <select name="skirta" title=''>
                    <?php

                    $ivertinimaiList = $ivertinimai->getAll(null, null);

                    foreach ($ivertinimaiList as $key => $val) {
                        $selected = '';
                        if (isset($fields['skirta']) && $fields['skirta'] == $val['id']) {
                            $selected = 'selected';
                        }
                        echo "<option value='{$val['id']}' {$selected}>{$val['ivertinimas']}</option>";
                    }
                    ?>
                </select>
            </p>
            <p>
                <label class="field"
                       for="isleista">Isleista<?php echo in_array('isleidimo_data', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="number" step="1" id="isleista" name="isleidimo_data" class="textbox-150"
                       value="<?php echo isset($fields['isleidimo_data']) ? $fields['isleidimo_data'] : ''; ?>">
                <?php if (key_exists('isleidimo_data', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['isleidimo_data']} simb.)</span>"; ?>
            </p>
            <p>
                <label class="field"
                       for="musu_ivertinimas">Musu Ivertinimas<?php echo in_array('musu_ivertinimas', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="number"  step="0.1" id="musu_ivertinimas" name="musu_ivertinimas" class="textbox-150"
                       value="<?php echo isset($fields['musu_ivertinimas']) ? $fields['musu_ivertinimas'] : ''; ?>">
                <?php if (key_exists('musu_ivertinimas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['musu_ivertinimas']} simb.)</span>"; ?>
            </p>
            <p>
                <label class="field"
                       for="trukme">Trukme (min)<?php echo in_array('trukme', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="number" step="0.01" id="trukme" name="trukme" class="textbox-150"
                       value="<?php echo isset($fields['trukme']) ? $fields['trukme'] : ''; ?>">
                <?php if (key_exists('trukme', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['trukme']} simb.)</span>"; ?>
            </p>
            <p>
                <label class="field"
                       for="nuomos_kaina">Nuomos kaina<?php echo in_array('nuomos_kaina', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="number" step="0.01" id="nuomos_kaina" name="nuomos_kaina" class="textbox-150"
                       value="<?php echo isset($fields['nuomos_kaina']) ? $fields['nuomos_kaina'] : ''; ?>">
                <?php if (key_exists('nuomos_kaina', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['nuomos_kaina']} simb.)</span>"; ?>
            </p>
            <p>
                <label class="field"
                       for="aprasymas">Aprašymas<?php echo in_array('aprasymas', $required) ? '<span> *</span>' : ''; ?></label>
                <textarea id="aprasymas" name="aprasymas" class="textbox-150" style="height: 150px; width: 450px; resize:vertical;"><?php echo isset($fields['aprasymas']) ? $fields['aprasymas'] : ''; ?>
                </textarea>
                <?php if (key_exists('aprasymas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['aprasymas']} simb.)</span>"; ?>
            </p>
        </fieldset>
        <p class="required-note">* pažymėtus laukus užpildyti privaloma</p>
        <p>
            <input type="submit" class="submit" name="submit" value="Išsaugoti">
        </p>
        <?php if (isset($fields['id'])) { ?>
            <input type="hidden" name="id" value="<?php echo $fields['id']; ?>"/>
        <?php } ?>
    </form>
</div>