<?php

include 'libraries/zanrai.class.php';
$genres = new zanrai();

$formErrors = null;
$fields = array();

// nustatome privalomus laukus
$required = array('zanras');

// maksimalūs leidžiami laukų ilgiai
$maxLengths = array (
    'zanras' => 20
);

// paspaustas išsaugojimo mygtukas
if(!empty($_POST['submit'])) {
    // nustatome laukų validatorių tipus
    $validations = array (
        'zanras' => 'anything');

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
    <li><a href="index.php?module=<?php echo $module; ?>">Filmų zanrai</a></li>
    <li><?php if(!empty($id)) echo "Žanro redagavimas"; else echo "Naujas žanras"; ?></li>
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
            <legend>Žanro informacija</legend>
            <p>
                <label class="field" for="name">Žanras<?php echo in_array('zanras', $required) ? '<span> *</span>' : ''; ?></label>
                <input type="text" id="name" name="zanras" class="textbox-150" value="<?php echo isset($fields['zanras']) ? $fields['zanras'] : ''; ?>">
                <?php if(key_exists('zanras', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['zanras']} simb.)</span>"; ?>
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