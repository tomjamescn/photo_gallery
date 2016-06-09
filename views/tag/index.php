<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = '图片浏览器 - 标签';
$count = 1;
//实际列数小1
$numPerRow = 4;
$closeFlag = true;
?>

<?php foreach ($tagList as $tag): ?>
    <?php if($closeFlag && ($count % $numPerRow == 0 || $count == 1)) : ?>
        <div class="row">
        <?php $closeFlag = false; ?>
    <?php else: ?>
            <?="<div class='col-sm-".intval(12/($numPerRow - 1))."'>".
        '<a href="?r=site&tagId='.$tag['id'].'">'.$tag['tagName'].'</a>'
        ."</div>" ?>
    <?php endif; ?>

    <?php if($count > 0 && $count % $numPerRow == 0) : ?>
        </div>
        <?php $closeFlag = true; ?>
    <?php endif; ?>

    <?php $count++; ?>

<?php endforeach; ?>

<?php if(!$closeFlag): ?>
    </div>
<?php endif; ?>

