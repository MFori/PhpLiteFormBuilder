# PhpLiteFormBuilder

###Basic form:
```
$form = new Form('myForm', 'post', 'action_file.php');

$form->addElement(new InputText())->setName('name')->setId('name')->setLabel(new Label('Name:'));
$form->addElement(new InputSubmit())->setValue('Submit');

$form->create();

$form->render();

```

###Getting data:

```
$mform = Form::getForm('myForm');

$mform->process();

if ( $mform->validate() )
  $data = $mform->getData();

```
