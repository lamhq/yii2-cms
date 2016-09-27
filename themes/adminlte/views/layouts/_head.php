<?php
use yii\helpers\Html;
/* @var $this \yii\web\View */
/* @var $content string */

// setup base url
$js = sprintf("app.baseUrl = '%s';", Yii::getAlias('@web'));
$this->registerJs($js, \yii\web\View::POS_END);
?>

<meta charset="<?= Yii::$app->charset ?>">
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

<?= Html::csrfMetaTags() ?>
<title><?= Html::encode($this->title) ?> - <?= Html::encode(Yii::$app->name) ?></title>
<?php $this->head() ?>
