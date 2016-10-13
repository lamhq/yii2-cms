<?php
namespace app\components;

use yii\db\ActiveRecord;
use yii\base\Behavior;
use app\components\helpers\FileHelper;

class UploadBehavior extends Behavior
{
	/**
	 * @var string Model attribute that contain filename
	 */
	public $valueAttribute;

	/**
	 * @var string Model attribute that contain uploaded file information
	 */
	public $formAttribute;

	/**
	 * @var string name of the relation
	 */
	public $relation;

	/**
	 * @var bool
	 */
	public $multiple=false;

	public function events() {
		$singleEvents = [
			ActiveRecord::EVENT_AFTER_FIND => 'afterFindSingle',
			ActiveRecord::EVENT_AFTER_INSERT => 'afterSaveSingle',
			ActiveRecord::EVENT_AFTER_UPDATE => 'afterSaveSingle',
		];

		$multipleEvents = [
			ActiveRecord::EVENT_AFTER_FIND => 'afterFindMultiple',
			ActiveRecord::EVENT_AFTER_INSERT => 'afterSaveMultiple',
			ActiveRecord::EVENT_AFTER_UPDATE => 'afterSaveMultiple',
		];

		return $this->multiple ? $multipleEvents : $singleEvents;
	}

	public function afterFindSingle($event) {
		$model = $this->owner;
		$formAttribute = $this->formAttribute;
		$filename = $model->{$this->valueAttribute};

		$model->$formAttribute = $filename ? [
			'value' => $filename,
			'label' => $filename,
			'url' => FileHelper::getModelFileUrl($model, $filename)
		] : null;

		FileHelper::getModelFileUrl($model, $filename);
	}

	public function afterSaveSingle($event) {
		$model = $this->owner;
		$model->detachBehavior('saveFeaturedImage');
		$valueAttribute = $this->valueAttribute;

		// move existings file to tmp dir
		$map = [];
		$filename = $model->$valueAttribute;
		$filePath = FileHelper::getModelFilePath($model, $filename);
		$tmpPath = FileHelper::createPathForSave(FileHelper::getTemporaryFilePath($filename));
		$map[$filename] = $tmpPath;
		if ( is_file($filePath) )	// model has value but image may be removed from disk
			rename($filePath, $tmpPath);

		// move files from tmp dir to model upload dir + update database
		$fileUpload = $model->{$this->formAttribute};
		if ($fileUpload) {
			$value = $fileUpload['value'];
			$tmpPath = isset($map[$value]) ? $map[$value] : FileHelper::getTemporaryFilePath($value);
			$filePath = FileHelper::createPathForSave(FileHelper::getModelFilePath($model, $value));
			$filename = basename(($filePath));
			if ( is_file($tmpPath) )
				rename($tmpPath, $filePath);
		} else {
			$filename = '';
		}
		$model->$valueAttribute = $filename;
		$model->update([$valueAttribute]);
	}

}