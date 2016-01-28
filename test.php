<!DOCTYPE html>
<head>
    <meta charset="utf-8">
</head>

<?php
include_once 'Form.php';

session_start();

if (isset($_POST['namesa'])) {

    //echo Form::isValid('form') ? 'jo' : 'ne';
    //echo '<br>' . Form::getForm('form')->getElementByName('jmeno')->getValue();
    //echo '<br>' . Form::getForm('form')->getElementByName('gender')->getValue();
    //echo '<br>' . Form::getForm('form')->getElementByName('souhlas')->getValue();


    echo '<br><br><br><br>';
    //echo $form->getElementByName('asasdf')->getValue();

    echo print_r($form->getData());

} else {

    $form = Form::getForm('form');

    $form->process();

    /*$select = new Select();
    $select->setName('s');
    $select->setHeader('Vyber si');
    $select->addOption(new SelectOption())->setId('id')->setText('nic')->setValue('value');
    $select->addOption(new SelectOption())->setId('i')->setText('text')->setValue('sss');

    $form = new Form('form', 'POST');

    $form->addElement(new InputText(), true, true)->setId('jmeno')->setMinLength(5)->setName('jmeno')->setLabel(new Label('Jméno'));
    $form->addElement(new InputCheckBox(), true)->setName('souhlas')->setId('souhlas')->setLabel(new Label('souhlas'));
    $form->addElement(new InputRadio())->setName('gender')->setId('male')->setValue('male')->setLabel(new Label('Muž'));
    $form->addElement(new InputRadio(), true)->setName('gender')->setId('female')->setValue('female')->setLabel(new Label('Žena'));
    $form->addElement(new TextArea(), true, false)->setName('asasdf')->setRows(5)->setCols(80)->setMaxLength(4)->setMinLength(2);
    $form->addElement($select, true)->setLabel('vyber: ');
    $form->addElement(new InputSubmit(), false)->setName('submit');
    $form->create();

    $form->render();*/
    $formular = new Form('form', 'post');
    $formular->setId('registrations');
    $formular->addElement(new InputText(), true)->setName('name')->setValue('Martin')->setId('name')->setLabel(new Label('Jméno:'));
    $formular->addElement(new InputText(), true)->setName('surname')->setId('surname')->setLabel(new Label('Přijmení:'));
    $formular->addElement(new InputEmail(), true)->setName('email')->setId('email')->setLabel(new Label('Email:'));
    $formular->addElement(new InputTel(), true)->setName('phone')->setId('phone')->setLabel(new Label('Telefon:'));
    $formular->addElement(new InputText())->setName('street')->setId('street')->setLabel(new Label('Ulice a č.p.:'));
    $formular->addElement(new InputText())->setName('city')->setId('city')->setLabel(new Label('Město:'));
    $formular->addElement(new InputNumber())->setName('psc')->setId('psc')->setLabel(new Label('PSČ:'));
    $formular->addElement(new InputText())->setName('state')->setId('state')->setLabel(new Label('Stát:'));
    $formular->addElement(new InputSubmit());
    $formular->create();
    $formular->render();
}
