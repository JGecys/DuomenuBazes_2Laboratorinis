<?php

include 'libraries/sutartys.class.php';
include 'libraries/diskai.class.php';
include "libraries/darbuotojai.class.php";
include 'libraries/padaliniai.class.php';
include 'libraries/papildoma_iranga.class.php';
include 'libraries/mokesciai.class.php';
include 'libraries/klientai.class.php';
include 'libraries/mokejimai.class.php';
$sutartys = new sutartys();
$diskai = new diskai();
$darbuotojai = new darbuotojai();
$padaliniai = new padaliniai();
$papildoma_iranga = new papildoma_iranga();
$mokesciai = new mokesciai();
$klientai = new klientai();
$mokejimai = new mokejimai();

date_default_timezone_set('Europe/Vilnius');

$formErrors = null;
$fields = array();

// nustatome privalomus laukus
$required = array(
    'sutarties_data',
    'nuomos_data',
    'planuojama_grazinimo_data',
    'kaina',
    'fk_diskas',
    'fk_patvirtino_darbuotojas',
    'fk_paemimo_vieta',
    'fk_grazinimo_vieta',
    'fk_uzsakovas'
);

// maksimalūs leidžiami laukų ilgiai
$maxLengths = array();
$dateCheck = false;
$dateCheck2 = false;
$dateCheck3= false;

// paspaustas išsaugojimo mygtukas
if (!empty($_POST['submit'])) {
    // nustatome laukų validatorių tipus
    $validations = array(
        'sutarties_data' => 'date',
        'nuomos_data' => 'date',
        'planuojama_grazinimo_data' => 'date',
        'faktine_grazinimo_data' => 'date',
        'kaina' => 'positivenumber',
        'fk_diskas' => 'anything',
        'fk_patvirtino_darbuotojas' => 'anything',
        'fk_paemimo_vieta' => 'anything',
        'fk_grazinimo_vieta' => 'anything',
        'fk_uzsakovas' => 'anything');

    // sukuriame validatoriaus objektą
    include 'utils/validator.class.php';
    $validator = new validator($validations, $required, $maxLengths);

    $dateCheck = strtotime($_POST['sutarties_data']) <= strtotime($_POST['nuomos_data']);
    $dateCheck2 = strtotime($_POST['nuomos_data']) <= strtotime($_POST['planuojama_grazinimo_data']);

    if ($validator->validate($_POST) && $dateCheck && $dateCheck2) {
        // suformuojame laukų reikšmių masyvą SQL užklausai
        $data = $validator->preparePostFieldsForSQL();
        if (isset($data['id'])) {
            // atnaujiname duomenis
            $sutartys->update($data);
        } else {
            // randame didžiausią markės id duomenų bazėje
            $latestId = $sutartys->getMaxId();

            // įrašome naują įrašą
            $data['id'] = $latestId + 1;
            $sutartys->insert($data);
        }

        // nukreipiame į markių puslapį
        header("Location: index.php?module={$module}&success");
        die();
    } else {
        if(!$dateCheck){
            $validator->addError('nuomos_data','Turi buti didesne nei sutarties data');
        }
        if(!$dateCheck2){
            $validator->addError('planuojama_grazinimo_data','Turi buti didesne nei nuomos data');
        }
        // gauname klaidų pranešimą
        $formErrors = $validator->getErrorHTML();
        // gauname įvestus laukus
        $fields = $_POST;
    }
} else {
    // tikriname, ar nurodytas elemento id. Jeigu taip, išrenkame elemento duomenis ir jais užpildome formos laukus.
    if (!empty($id)) {
        $fields = $sutartys->get($id);
    }
}
?>
<ul id="pagePath">
    <li><a href="index.php">Pradžia</a></li>
    <li><a href="index.php?module=<?php echo $module; ?>">Sutartys</a></li>
    <li><?php if (!empty($id)) echo "Sutarties redagavimas"; else echo "Nauja sutartis"; ?></li>
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
            <legend>Sutarties informacija</legend>
            <p>
                <label class="field" for="sutarties_data">Sutarties data<?php echo in_array('sutarties_data', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="sutarties_data" name="sutarties_data" class="textbox-150 date"
                       value="<?php echo isset($fields['sutarties_data']) ? $fields['sutarties_data'] : date("Y-m-d"); ?>">
                <?php if (key_exists('sutarties_data', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['sutarties_data']} simb.)</span>"; ?>
            </p>
            <p>
                <label class="field" for="nuomos_data">Nuomos data<?php echo in_array('nuomos_data', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="nuomos_data" name="nuomos_data" class="textbox-150 date"
                       value="<?php echo isset($fields['nuomos_data']) ? $fields['nuomos_data'] : date("Y-m-d"); ?>">
                <?php if (key_exists('nuomos_data', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['nuomos_data']} simb.)</span>"; ?>
            </p>
            <p>
                <label class="field" for="planuojama_grazinimo_data">Planuojama grazinimo data<?php echo in_array('planuojama_grazinimo_data', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="planuojama_grazinimo_data" name="planuojama_grazinimo_data" class="textbox-150 date"
                       value="<?php echo isset($fields['planuojama_grazinimo_data']) ? $fields['planuojama_grazinimo_data'] : date("Y-m-d"); ?>">
                <?php if (key_exists('planuojama_grazinimo_data', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['planuojama_grazinimo_data']} simb.)</span>"; ?>
            </p>
            <p>
                <label class="field" for="faktine_grazinimo_data">Faktine grazinimo data<?php echo in_array('faktine_grazinimo_data', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="faktine_grazinimo_data" name="faktine_grazinimo_data" class="textbox-150 date"
                       value="<?php echo isset($fields['faktine_grazinimo_data']) ? $fields['faktine_grazinimo_data'] : ''; ?>">
                <?php if (key_exists('faktine_grazinimo_data', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['faktine_grazinimo_data']} simb.)</span>"; ?>
            </p>
            <p>
                <label class="field" for="kaina">Kaina<?php echo in_array('kaina', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="kaina" name="kaina" class="textbox-150"
                       value="<?php echo isset($fields['kaina']) ? $fields['kaina'] : ''; ?>">
                <?php if (key_exists('kaina', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['kaina']} simb.)</span>"; ?>
            </p>
            <p>
                <label class="field"
                       for="serija">Diskas<?php echo in_array('fk_diskas', $required) ? '<span> *</span>' : ''; ?></label>
                <select id="serija" name="fk_diskas" title=''>
                    <?php

                    $diskuList = $diskai->getAll(null, null);

                    foreach ($diskuList as $key => $val) {
                        $selected = '';
                        if (isset($fields['fk_diskas']) && $fields['fk_diskas'] == $val['id']) {
                            $selected = 'selected';
                        }
                        echo "<option value='{$val['id']}' {$selected}>{$val['fk_filmas']}</option>";
                    }
                    ?>
                </select>
            </p>
            <p>
                <label class="field"
                       for="patvirtino">Patvirtino<?php echo in_array('fk_patvirtino_darbuotojas', $required) ? '<span> *</span>' : ''; ?></label>
                <select id="patvirtino" name="fk_patvirtino_darbuotojas" title=''>
                    <?php

                    $darbuotojaiList = $darbuotojai->getAll(null, null);

                    foreach ($darbuotojaiList as $key => $val) {
                        $selected = '';
                        if (isset($fields['fk_patvirtino_darbuotojas']) && $fields['fk_patvirtino_darbuotojas'] == $val['id']) {
                            $selected = 'selected';
                        }
                        echo "<option value='{$val['tabelio_nr']}' {$selected}>{$val['tabelio_nr']}</option>";
                    }
                    ?>
                </select>
            </p>
            <p>
                <label class="field"
                       for="paemimo-vieta">Paemimo vieta<?php echo in_array('fk_paemimo_vieta', $required) ? '<span> *</span>' : ''; ?></label>
                <select id="paemimo-vieta" name="fk_paemimo_vieta" title=''>
                    <?php

                    $padaliniaiList = $padaliniai->getAll(null, null);

                    foreach ($padaliniaiList as $key => $val) {
                        $selected = '';
                        if (isset($fields['fk_paemimo_vieta']) && $fields['fk_paemimo_vieta'] == $val['id']) {
                            $selected = 'selected';
                        }
                        echo "<option value='{$val['id']}' {$selected}>{$val['padalinys']}</option>";
                    }
                    ?>
                </select>
            </p>
            <p>
                <label class="field"
                       for="serija">Grazinimo vieta<?php echo in_array('fk_grazinimo_vieta', $required) ? '<span> *</span>' : ''; ?></label>
                <select id="serija" name="fk_grazinimo_vieta" title=''>
                    <?php
                    foreach ($padaliniaiList as $key => $val) {
                        $selected = '';
                        if (isset($fields['fk_grazinimo_vieta']) && $fields['fk_grazinimo_vieta'] == $val['id']) {
                            $selected = 'selected';
                        }
                        echo "<option value='{$val['id']}' {$selected}>{$val['padalinys']}</option>";
                    }
                    ?>
                </select>
            </p>
            <p>
                <label class="field"
                       for="serija">Papildoma iranga<?php echo in_array('fk_papildoma_iranga', $required) ? '<span> *</span>' : ''; ?></label>
                <select id="serija" name="fk_papildoma_iranga" title=''>
                    <option value="">Nėra</option>
                    <?php

                    $papildomosIrangosList = $papildoma_iranga->getAll(null, null);

                    foreach ($papildomosIrangosList as $key => $val) {
                        $selected = '';
                        if (isset($fields['fk_papildoma_iranga']) && $fields['fk_papildoma_iranga'] == $val['id']) {
                            $selected = 'selected';
                        }
                        echo "<option value='{$val['id']}' {$selected}>{$val['pavadinimas']}</option>";
                    }
                    ?>
                </select>
            </p>
            <p>
                <label class="field"
                       for="serija">Mokestis<?php echo in_array('fk_mokestis', $required) ? '<span> *</span>' : ''; ?></label>
                <select id="serija" name="fk_mokestis" title=''>
                    <option value="">Nėra</option>
                    <?php

                    $mokesciaiList = $mokesciai->getAll(null, null);

                    foreach ($mokesciaiList as $key => $val) {
                        $selected = '';
                        if (isset($fields['fk_mokestis']) && $fields['fk_mokestis'] == $val['id']) {
                            $selected = 'selected';
                        }
                        echo "<option value='{$val['id']}' {$selected}>{$val['pavadinimas']}</option>";
                    }
                    ?>
                </select>
            </p>
            <p>
                <label class="field"
                       for="serija">Mokejimas<?php echo in_array('fk_mokejimas', $required) ? '<span> *</span>' : ''; ?></label>
                <select id="serija" name="fk_mokejimas" title=''>
                    <option value="">Nėra</option>
                    <?php

                    $mokejimaiList = $mokejimai->getAll(null, null);

                    foreach ($mokejimaiList as $key => $val) {
                        $selected = '';
                        if (isset($fields['fk_mokejimas']) && $fields['fk_mokejimas'] == $val['id']) {
                            $selected = 'selected';
                        }
                        echo "<option value='{$val['id']}' {$selected}>{$val['id']}</option>";
                    }
                    ?>
                </select>
            </p>
            <p>
                <label class="field"
                       for="uzsakovas">Užsakovas<?php echo in_array('fk_uzsakovas', $required) ? '<span> *</span>' : ''; ?></label>
                <select id="uzsakovas" name="fk_uzsakovas" title=''>
                    <?php

                    $klientaiList = $klientai->getAll(null, null);

                    foreach ($klientaiList as $key => $val) {
                        $selected = '';
                        if (isset($fields['fk_uzsakovas']) && $fields['fk_uzsakovas'] == $val['id']) {
                            $selected = 'selected';
                        }
                        echo "<option value='{$val['id']}' {$selected}>{$val['asmens_kodas']}</option>";
                    }
                    ?>
                </select>
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