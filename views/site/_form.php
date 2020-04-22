<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Interest;

/* @var $this yii\web\View */
/* @var $model app\models\Site */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="site-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'site')->textInput(['maxlength' => true, 'readonly' => ! $model->isNewRecord]) ?>
    
    <div class="form-group">
    <?=
    Select2::widget([
        'name' => 'interests',
        'value' => $model->isNewRecord ? Yii::$app->request->post('interests') : ArrayHelper::getColumn($model->interests, 'id'),
        'data' => ArrayHelper::map(Interest::find()->all(), 'id', 'title'),
        'options' => ['multiple' => true, 'placeholder' => Yii::t('app', 'Select Interests...')],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]);
?>
</div>
    

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
