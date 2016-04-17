<?php

include 'libraries/padaliniai.class.php';
include 'libraries/miestai.class.php';
$padaliniai = new padaliniai();
$miestai = new miestai();

$formErrors = null;
$fields = array();

// nustatome privalomus laukus
$required = array('padalinys','adresas','telefonas','e_pastas');

// maksimalūs leidžiami laukų ilgiai
$maxLengths = array(
    'padalinys' => 20,
    'adresas' => 20,
    'telefonas' => 15,
    'e_pastas' => 30
);

// paspaustas išsaugojimo mygtukas
if (!empty($_POST['submit'])) {
    // nustatome laukų validatorių tipus
    $validations = array(
        'padalinys' => 'anything',
        'adresas' => 'anything',
        'telefonas' => 'anything',
        'e_pastas' => 'anything');

    // sukuriame validatoriaus objektą
    include 'utils/validator.class.php';
    $validator = new validator($validations, $required, $maxLengths);

    if ($validator->validate($_POST)) {
        // suformuojame laukų reikšmių masyvą SQL užklausai
        $data = $validator->preparePostFieldsForSQL();
        if (isset($data['id'])) {
            // atnaujiname duomenis
            $padaliniai->update($data);
        } else {
            // randame didžiausią markės id duomenų bazėje
            $latestId = $padaliniai->getMaxId();

            // įrašome naują įrašą
            $data['id'] = $latestId + 1;
            $padaliniai->insert($data);
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
        $fields = $padaliniai->get($id);
    }
}
?>
<ul id="pagePath">
    <li><a href="index.php">Pradžia</a></li>
    <li><a href="index.php?module=<?php echo $module; ?>">Padaliniai</a></li>
    <li><?php if (!empty($id)) echo "Padalinio redagavimas"; else echo "Naujas padalinys"; ?></li>
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
                       for="padalinys">Padalinys<?php echo in_array('padalinys', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="padalinys" name="padalinys" class="textbox-150"
                       value="<?php echo isset($fields['padalinys']) ? $fields['padalinys'] : ''; ?>">
                <?php if (key_exists('padalinys', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['padalinys']} simb.)</span>"; ?>
            </p>
            <p>
                <label class="field"
                       for="fk_miestas">Miestas<?php echo in_array('fk_miestas', $required) ? '<span> *</span>' : ''; ?></label>
                <select id="fk_miestas" name="fk_miestas" title=''>
                    <?php

                    $miestaiList = $miestai->getAll(null, null);

                    foreach ($miestaiList as $key => $val) {
                        $selected = '';
                        if(isset($fields['fk_miestas']) && $fields['fk_miestas'] == $val['id']){
                            $selected = 'selected';
                        }
                        echo "<option value='{$val['id']}' {$selected}>{$val['miestas']}</option>";
                    }
                    ?>
                </select>
            </p>
            <p>
                <label class="field"
                       for="adresas">Adresas<?php echo in_array('adresas', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="adresas" name="adresas" class="textbox-150"
                       value="<?php echo isset($fields['adresas']) ? $fields['adresas'] : ''; ?>">
                <?php if (key_exists('adresas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['adresas']} simb.)</span>"; ?>
            </p>
            <p>
                <label class="field"
                       for="telefonas">Telefonas<?php echo in_array('telefonas', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="telefonas" name="telefonas" class="textbox-150"
                       value="<?php echo isset($fields['telefonas']) ? $fields['telefonas'] : ''; ?>">
                <?php if (key_exists('telefonas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['telefonas']} simb.)</span>"; ?>
            </p>
            <p>
                <label class="field"
                       for="e_pastas">E. Paštas<?php echo in_array('e_pastas', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="e_pastas" name="e_pastas" class="textbox-150"
                       value="<?php echo isset($fields['e_pastas']) ? $fields['e_pastas'] : ''; ?>">
                <?php if (key_exists('e_pastas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['e_pastas']} simb.)</span>"; ?>
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