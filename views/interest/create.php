<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Interest */

$this->title = Yii::t('app', 'Create Interest');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Interests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interest-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
