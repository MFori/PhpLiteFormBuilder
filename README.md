# PhpLiteFormBuilder
PhpLiteFormBuilder is lite library for manipulating (creating and processing) forms in php. 

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

if ( $mform instanceof Form)
  if ( $mform->isValid() ) {
    $data = $mform->getData();
    $name = $data['name'];
  }

```
