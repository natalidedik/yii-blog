<?php
/* @var $this PostController */
/* @var $model Post */

$this->breadcrumbs = array(
    'Posts' => array('index'),
    $model->title,
);

$this->menu = array(
    array('label' => 'List Post', 'url' => array('index')),
    array('label' => 'Create Post', 'url' => array('create'), 'visible' => Yii::app()->user->checkAccess('admin')),
    array('label' => 'Update Post', 'url' => array('update', 'id' => $model->id), 'visible' => Yii::app()->user->checkAccess('admin')|| Yii::app()->user->checkAccess('moderator')),
    array('label' => 'Delete Post', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?'), 'visible' => Yii::app()->user->checkAccess('admin')),
    array('label' => 'Manage Post', 'url' => array('admin'), 'visible' => Yii::app()->user->checkAccess('moderator') || Yii::app()->user->checkAccess('admin')),
    array('label' => 'Manage Comments', 'url' => array('comment/admin'), 'visible' => Yii::app()->user->checkAccess('moderator') || Yii::app()->user->checkAccess('admin')),
);
?>

<h1><?php echo $model->title; ?></h1>

<div class="post-content"><?php echo nl2br($model->content); ?></div>
<div class="post-tags"><b>Tags: </b><?php echo nl2br($model->tags); ?></div>
<div class="separator"></div>
<!--<div class="time">
    <i><?php //echo date('F j, Y \a\t h:i a', $model->create_time);  ?></i>
</div> -->
<div id="comments">
<?php if ($model->commentCount >= 1): ?>
        <h3>
        <?php echo $model->commentCount > 1 ? $model->commentCount . ' comments' : 'One comment'; ?>
        </h3>

    <?php
    $this->renderPartial('_comments', array(
        'post' => $model,
        'comments' => $model->comments,
    ));
    ?>
    <?php endif; ?>


    <?php if (!Yii::app()->user->isGuest) { ?>
        <div class="padd_top"><h3>Leave a Comment</h3></div>

        <?php if (Yii::app()->user->hasFlash('commentSubmitted')): ?>
            <div class="flash-success">
                <?php echo Yii::app()->user->getFlash('commentSubmitted'); ?>
            </div>
        <?php else: ?>
            <?php
            $this->renderPartial('/comment/_form', array(
                'model' => $comment,
            ));
            ?>
    <?php endif; ?>
<?php } ?>

</div><!-- comments -->
