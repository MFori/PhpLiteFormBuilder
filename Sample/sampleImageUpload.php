<?php

/**
 *  PhpLiteFormBuilder Sample
 *  @author Martin Forejt
 *  https://github.com/MFori
 */

include '../FormBuilder/Form.php';

// FormBuilder use super global variable $_SESSION
session_start();

// create new Form
$mForm = new Form('mForm', 'post');

// adding elements
$mForm->addElement(new InputFile())->setName('file')->setMaxSize(1000000)->setAccept('image/*');
$mForm->addElement(new InputSubmit())->setValue('LogIn')->setName('loginForm');

// create from
$mForm->create();

// render form
$mForm->render();

// processing form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sendForm = Form::getForm('mForm');

    // must call before validating or getting data
    $sendForm->process();

    echo '<h2>Data from Form:</h2>';

    // validation form elements
    if ($sendForm->isValid()) {
        $data = $sendForm->getData();

        move_uploaded_file($data['file']['tmp_name'], '../images/'.$data['file']['name']);

        echo '<img src="../images/' . $data['file']['name'] . '">';

    } else {
        echo 'Form is not valid.';
    }
}