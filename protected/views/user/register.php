<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'register',
);

?>

<h1>Register</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>