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
// open part 'customer'
$mForm->openPart('customer');
$mForm->addElement(new InputText(), true)->setName('name')->setId('name')->setLabel(new Label('Name:'));
$mForm->addElement(new InputText(), true)->setName('surname')->setId('surname')->setLabel(new Label('Surname:'));
$mForm->closePart();

// open part 'address'
$mForm->openPart('address');
$mForm->addElement(new InputText())->setName('street')->setId('street')->setLabel(new Label('Street:'));
$mForm->addElement(new InputText())->setName('city')->setId('city')->setLabel(new Label('City:'));
$mForm->addElement(new InputText())->setName('state')->setId('state')->setLabel(new Label('State:'));
$mForm->closePart();

// open part 'agreement'
$mForm->openPart('agreement');
$mForm->addElement(new InputCheckBox(), true)->setName('agree')->setId('agree')->setLabel(new Label('I accept the terms and conditions'));
$mForm->addElement(new InputSubmit())->setValue('Sent');
$mForm->closePart();

// create from
$mForm->create();

echo '<h2>Address</h2>';

//render 'address'
$mForm->renderPart('address');

echo '<h2>Customer</h2>';

//render 'customer'
$mForm->renderPart('customer');

echo '<h2>Agreement</h2>';

$mForm->renderPart('agreement');


// processing form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $sendForm = Form::getForm('mForm');

    // Checking if form with this name exist
    if($sendForm instanceof Form) {
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
}
