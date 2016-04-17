<?php

include 'libraries/miestai.class.php';
$genres = new miestai();

$formErrors = null;
$fields = array();

// nustatome privalomus laukus
$required = array('zanras');

// maksimalūs leidžiami laukų ilgiai
$maxLengths = array (
    'miestas' => 20
);

// paspaustas išsaugojimo mygtukas
if(!empty($_POST['submit'])) {
    // nustatome laukų validatorių tipus
    $validations = array (
        'miestas' => 'anything');

    // sukuriame validatoriaus objektą
    include 'utils/validator.class.php';
    $validator = new validator($validations, $required, $maxLengths);

    if($validator->validate($_POST)) {
        // suformuojame laukų reikšmių masyvą SQL užklausai
        $data = $validator->preparePostFieldsForSQL();
        if(isset($data['id'])) {
            // atnaujiname duomenis
            $genres->update($data);
        } else {
            // randame didžiausią markės id duomenų bazėje
            $latestId = $genres->getMaxId();

            // įrašome naują įrašą
            $data['id'] = $latestId + 1;
            $genres->insert($data);
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
        $fields = $genres->get($id);
    }
}
?>
<ul id="pagePath">
    <li><a href="index.php">Pradžia</a></li>
    <li><a href="index.php?module=<?php echo $module; ?>">Miestai</a></li>
    <li><?php if(!empty($id)) echo "Miesto redagavimas"; else echo "Naujas miestas"; ?></li>
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
            <legend>Miesto informacija</legend>
            <p>
                <label class="field" for="name">Miestas<?php echo in_array('miestas', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="name" name="miestas" class="textbox-150" value="<?php echo isset($fields['miestas']) ? $fields['miestas'] : ''; ?>">
                <?php if(key_exists('miestas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['miestas']} simb.)</span>"; ?>
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