<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model altiore\article\models\PostType */

$this->title = Yii::t('app', 'Create Post Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Post Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
