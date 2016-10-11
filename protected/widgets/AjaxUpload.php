<?php
namespace app\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AjaxUpload as AjaxUploadAsset;

/**
 * The following example shows how to use AjaxUpload widget:
 * 
 * ```php
 * <?= AjaxUpload::widget([
 *     'name' => 'image',
 *     'extensions' => ['jpg', 'png', 'gif'],
 *     'maxSize' => 1024,	// 1MB
 * ]) ?>
 * ```
 * 
 * You can also use this widget in an [[yii\widgets\ActiveForm|ActiveForm]] using the [[yii\widgets\ActiveField::widget()|widget()]]
 * method, for example like this:
 * 
 * ```php
 * <?= $form->field($model, 'image')->widget(AjaxUpload::className(), [
 *     'extensions' => ['jpg', 'png', 'gif'],
 *     'maxSize' => 1024,	// 1MB
 * ]) ?>
 * ```
 * 
 * @author Lam Huynh <lamhq.com>
 */
class AjaxUpload extends InputWidget
{
	/**
	 * @var string url to submit the file
	 */
	public $url;
	
	/**
	 * @var boolean allow multiple file upload
	 */
	public $multiple = false;
	
	/**
	 * @var array allowed file extension
	 */
	public $extensions = array();
	
	/**
	 * @var float the maximum file size for upload in KB. 
	 * If set to 0, it means size allowed is unlimited. Defaults to 0.
	 */
	public $maxSize = 0;

	/**
	 * Executes the widget.
	 * This method registers all needed client scripts and renders
	 * the widget
	 */
	public function run() {
		$model = $this->model;
		$attribute = $this->attribute;
		$value = $model->$attribute;
		$items = $this->multiple ? $value : [$value];
		$this->id = 'yw'.time().rand(0,99);
		$this->options = array_merge([
			'id' => $this->id,
			'url' => Url::to($this->url, true),
			'extensions' => $this->extensions,
			'maxSize' => $this->maxSize,
			'multiple' => $this->multiple,
		], $this->options);
		if (!isset($this->options['name'])) {
			$this->options['name'] = Html::getInputName($this->model, $this->attribute);
		}
		
		$this->registerClientScript();
		return $this->render('ajax-upload', [
			'items'=>$items,
			'options'=>$this->options
		]);
	}

	/**
	 * Registers the needed CSS and JavaScript.
	 */
	public function registerClientScript()
	{
		$view=$this->view;
		AjaxUploadAsset::register($view);
		$options = json_encode($this->options);
		$view->registerJs("$('#{$this->id}').ajaxUpload({$options});");
	}
}
