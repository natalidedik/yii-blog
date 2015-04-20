<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>This is <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p>Get the latest news and blog posts from personalities, contributors and bloggers and more. </p> 
<p>The latest news and announcements you can find here <?php echo CHtml::link('All News.', array('/'));?></p>

<div id="image">
    <?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/my-news-logo.jpg', 'My news');?>
</div>    

