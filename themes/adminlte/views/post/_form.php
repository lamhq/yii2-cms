<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Post;
use backend\models\Category;
use app\components\helpers\DateHelper;
use trntv\yii\datetime\DateTimeWidget;

/* @var $this yii\web\View */
/* @var $model backend\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'short_content')->textArea(['rows'=>5]) ?>

    <?= $form->field($model, 'content')->widget(
        \yii\imperavi\Widget::className(),
        [
            'options' => [
                'minHeight' => 400,
                'maxHeight' => 400,
                'buttonSource' => true,
                'convertDivs' => false,
                'removeEmptyTags' => false,
                'imageUpload' => Yii::$app->urlManager->createUrl(['/file-storage/upload-imperavi'])
            ]
        ]
    ) ?>

    <?= $form->field($model, 'status')->checkbox(['uncheck'=>Post::STATUS_INACTIVE]) ?>

    <?= 

    $form->field($model, 'published_at')->widget(
        DateTimeWidget::className(),
        [
            'phpDatetimeFormat' => DateHelper::getAppDatetimeFormat(),
            'momentDatetimeFormat' => DateHelper::getDatepicketDatetimeFormat(),
        ]
    ) 
    ?>

    <?= $form->field($model, 'category_id')
        ->dropdownList(Category::getCategoryDropdownList(), ['encode'=>false, 'prompt'=>'']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
