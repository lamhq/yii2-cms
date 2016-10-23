<?php
namespace app\components;

use yii\db\ActiveRecord;
use yii\base\Behavior;
use app\components\helpers\StorageHelper;
use yii\helpers\FileHelper;

/**
 * Delete file belong to model before model is deleted
 */
class CleanupBehavior extends Behavior
{
	/**
	 * @var string Model attribute that contain filename
	 */
	public $attribute;

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
			ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDeleteSingle',
		];

		$multipleEvents = [
			ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDeleteMultiple',
		];

		return $this->multiple ? $multipleEvents : $singleEvents;
	}

	public function beforeDeleteSingle($event) {
		$model = $this->owner;
		$filename = $model->{$this->attribute};
		$filePath = StorageHelper::getModelFilePath($model, $filename);
		// remove directory contain all files of the model
		if ( is_file($filePath) ) {
			FileHelper::removeDirectory(dirname($filePath));
		}
	}

	public function beforeDeleteMultiple() {
		$model = $this->owner;
		$fileField = $this->attribute;
		$files = $model->{$this->relation};
		foreach($files as $file) {
			$filename = $file->$fileField;
			$filePath = StorageHelper::getModelFilePath($model, $filename);
			// remove directory contain all files of the model
			if ( is_file($filePath) ) {
				FileHelper::removeDirectory(dirname($filePath));
				return;
			}
		}
	}
}