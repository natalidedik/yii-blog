<?php
/* @var $this PostController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Posts',
);

$this->menu=array(
	array('label'=>'Create Post', 'url'=>array('create'), 'visible' => Yii::app()->user->checkAccess('admin')),
	array('label'=>'Manage Post', 'url'=>array('admin'), 'visible' => Yii::app()->user->checkAccess('moderator') || Yii::app()->user->checkAccess('admin')),        
        array('label' =>'Manage Users', 'url' => array('user/admin'), 'visible' => Yii::app()->user->checkAccess('admin')),
);
?>

<?php if(!empty($_GET['tag'])): ?>
<h1>Posts with tag<i><?php echo CHtml::encode($_GET['tag']); ?></i></h1>
<?php endif; ?>
 
<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view',
    'template'=>"{items}\n{pager}",
)); ?>