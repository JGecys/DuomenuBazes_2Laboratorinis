<?php

include 'libraries/serijos.class.php';
include 'libraries/zanrai.class.php';
$series = new serijos();
$padaliniai = new zanrai();

$formErrors = null;
$fields = array();

// nustatome privalomus laukus
$required = array('serija');

// maksimalūs leidžiami laukų ilgiai
$maxLengths = array(
    'serija' => 20
);

// paspaustas išsaugojimo mygtukas
if (!empty($_POST['submit'])) {
    // nustatome laukų validatorių tipus
    $validations = array(
        'serija' => 'anything');

    // sukuriame validatoriaus objektą
    include 'utils/validator.class.php';
    $validator = new validator($validations, $required, $maxLengths);

    if ($validator->validate($_POST)) {
        // suformuojame laukų reikšmių masyvą SQL užklausai
        $data = $validator->preparePostFieldsForSQL();
        if (isset($data['id'])) {
            // atnaujiname duomenis
            $series->update($data);
        } else {
            // randame didžiausią markės id duomenų bazėje
            $latestId = $series->getMaxId();

            // įrašome naują įrašą
            $data['id'] = $latestId + 1;
            $series->insert($data);
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
        $fields = $series->get($id);
    }
}
?>
<ul id="pagePath">
    <li><a href="index.php">Pradžia</a></li>
    <li><a href="index.php?module=<?php echo $module; ?>">Filmų serijos</a></li>
    <li><?php if (!empty($id)) echo "Serijos redagavimas"; else echo "Nauja serija"; ?></li>
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
                       for="name">Serija<?php echo in_array('serija', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="name" name="serija" class="textbox-150"
                       value="<?php echo isset($fields['serija']) ? $fields['serija'] : ''; ?>">
                <?php if (key_exists('serija', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['serija']} simb.)</span>"; ?>
            </p>
            <p>
                <label class="field"
                       for="name">Žanras<?php echo in_array('fk_zanras', $required) ? '<span> *</span>' : ''; ?></label>
                <select name="fk_zanras" title=''>
                    <?php

                    $zanraiList = $padaliniai->getAll(null, null);

                    foreach ($zanraiList as $key => $val) {
                        $selected = '';
                        if(isset($fields['fk_zanras']) && $fields['fk_zanras'] == $val['id']){
                            $selected = 'selected';
                        }
                        echo "<option value='{$val['id']}' {$selected}>{$val['zanras']}</option>";
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