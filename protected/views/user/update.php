<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'View User', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h1>Update User <?php echo $model->username; ?></h1>

<?php //$this->renderPartial('_form', array('model'=>$model)); ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'update-role',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p>Select the new role for the user.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'role'); ?>
		<?php echo $form->dropDownList($model,'role',array('user'=>'user', 'admin'=>'admin', 'moderator'=>'moderator', 'admin'=>'admin')); ?>
		<?php echo $form->error($model,'role'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
