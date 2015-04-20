<?php foreach ($comments as $comment): ?>
    <div class="comment" id="c<?php echo $comment->id; ?>">
        <div class="author">
            <b><?php echo $comment->authorLink; ?></b> says:
        </div>
        <div class="time">
            <i><?php echo date('F j, Y \a\t h:i a', $comment->create_time); ?></i>
        </div> 
        <div class="content comm_content">
            <?php echo nl2br(CHtml::encode($comment->content)); ?>
        </div>       
    </div>
<?php endforeach; ?>

