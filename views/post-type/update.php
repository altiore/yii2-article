<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model altiore\article\models\PostType */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Post Type',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Post Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="post-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
