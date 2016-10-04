<?php
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Posts')
?>
<?php echo \yii\widgets\ListView::widget([
    'dataProvider'=>$dataProvider,
    'itemView'=>'_item',
    'summary'=>'',
    'pager'=>[ 'hideOnSinglePage'=>true,],
    'pager'=>[
    	'nextPageLabel'=>'<span class="glyphicon glyphicon-chevron-right"></span>',
    	'prevPageLabel'=>'<span class="glyphicon glyphicon-chevron-left"></span>',
    ]
])?>