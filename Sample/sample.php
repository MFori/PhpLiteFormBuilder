<?php

include '../FormBuilder/Form.php';

// FormBuilder use super global variable $_SESSION
session_start();

// create new Form
$mForm = new Form('mForm', 'post');

// adding elements
$mForm->addElement(new InputText(), true)->setName('username')->setId('username')->setLabel(new Label('Username:'));
$mForm->addElement(new InputPassword(), true)->setName('password')->setId('password')->setLabel(new Label('Password:'));
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
        foreach ($data as $k => $v)
            echo $k . ': ' . $v . '<br>';
    } else {
        echo 'Form is not valid.';
    }
}
