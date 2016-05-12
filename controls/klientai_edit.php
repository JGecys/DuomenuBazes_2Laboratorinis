<?php

include 'libraries/klientai.class.php';
$klientai = new klientai();

$formErrors = null;
$fields = array();

// nustatome privalomus formos laukus
$required = array('asmens_kodas', 'vardas', 'pavarde', 'gimimo_data', 'telefonas', 'e_pastas');

// maksimalūs leidžiami laukų ilgiai
$maxLengths = array (
    'asmens_kodas' => 20,
    'vardas' => 15,
    'pavarde' => 15,
    'telefonas' => 15,
    'e_pastas' => 30
);

// vartotojas paspaudė išsaugojimo mygtuką
if(!empty($_POST['submit'])) {
    include 'utils/validator.class.php';

    // nustatome laukų validatorių tipus
    $validations = array (
        'asmens_kodas' => 'alfanum',
        'vardas' => 'name',
        'pavarde' => 'name',
        'telefonas' => 'phone',
        'gimimo_data' => 'date',
        'e_pastas' => 'anything');

    // sukuriame laukų validatoriaus objektą
    $validator = new validator($validations, $required, $maxLengths);

    if ($validator->validate($_POST)) {
        // suformuojame laukų reikšmių masyvą SQL užklausai
        $data = $validator->preparePostFieldsForSQL();
        if (isset($data['id'])) {
            // atnaujiname duomenis
            $klientai->update($data);
        } else {
            // randame didžiausią markės id duomenų bazėje
            $latestId = $klientai->getMaxId();

            // įrašome naują įrašą
            $data['id'] = $latestId + 1;
            $klientai->insert($data);
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
        $fields = $klientai->get($id);
    }
}
?>
<ul id="pagePath">
    <li><a href="index.php">Pradžia</a></li>
    <li><a href="index.php?module=<?php echo $module; ?>">Klientai</a></li>
    <li><?php if(!empty($id)) echo "Kliento redagavimas"; else echo "Naujas klientas"; ?></li>
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
            <legend>Kliento informacija</legend>
            <p>
                <label class="field" for="asmens_kodas">Asmens kodas<?php echo in_array('asmens_kodas', $required) ? '<span> *</span>' : ''; ?></label>
                <?php if(!isset($fields['id'])) { ?>
                    <input type="text" id="asmens_kodas" name="asmens_kodas" class="textbox-150" value="<?php echo isset($fields['asmens_kodas']) ? $fields['asmens_kodas'] : ''; ?>" />
                    <?php if(key_exists('asmens_kodas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['asmens_kodas']} simb.)</span>"; ?>
                <?php } else { ?>
                    <span class="input-value"><?php echo $fields['asmens_kodas']; ?></span>
                    <input type="hidden" name="editing" value="1" />
                    <input type="hidden" name="asmens_kodas" value="<?php echo $fields['asmens_kodas']; ?>" />
                <?php } ?>
            </p>
            <p>
                <label class="field" for="vardas">Vardas<?php echo in_array('vardas', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="vardas" name="vardas" class="textbox-150" value="<?php echo isset($fields['vardas']) ? $fields['vardas'] : ''; ?>" />
                <?php if(key_exists('vardas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['vardas']} simb.)</span>"; ?>
            </p>
            <p>
                <label class="field" for="pavarde">Pavardė<?php echo in_array('pavarde', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="pavarde" name="pavarde" class="textbox-150" value="<?php echo isset($fields['pavarde']) ? $fields['pavarde'] : ''; ?>" />
                <?php if(key_exists('pavarde', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['pavarde']} simb.)</span>"; ?>
            </p>

            <p>
                <label class="field" for="gimimo_data">Gimimo data<?php echo in_array('gimimo_data', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="gimimo_data" name="gimimo_data" class="textbox-150 date" value="<?php echo isset($fields['gimimo_data']) ? $fields['gimimo_data'] : ''; ?>" />
                <?php if(key_exists('gimimo_data', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['gimimo_data']} simb.)</span>"; ?>
            </p>

            <p>
                <label class="field" for="telefonas">Telefonas<?php echo in_array('telefonas', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="telefonas" name="telefonas" class="textbox-150" value="<?php echo isset($fields['telefonas']) ? $fields['telefonas'] : ''; ?>" />
                <?php if(key_exists('telefonas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['telefonas']} simb.)</span>"; ?>
            </p>
            <p>
                <label class="field" for="e_pastas">El Pastas<?php echo in_array('e_pastas', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="e_pastas" name="e_pastas" class="textbox-150" value="<?php echo isset($fields['e_pastas']) ? $fields['e_pastas'] : ''; ?>" />
                <?php if(key_exists('e_pastas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['e_pastas']} simb.)</span>"; ?>
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