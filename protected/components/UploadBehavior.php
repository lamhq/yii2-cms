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
	}

	public function afterSaveSingle($event) {
		$model = $this->owner;
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
			$filePath = FileHelper::createPathForSave(FileHelper::getModelFilePath($model, $fileUpload['label']));
			$filename = basename(($filePath));
			if ( is_file($tmpPath) )
				rename($tmpPath, $filePath);
		} else {
			$filename = '';
		}
		$model->$valueAttribute = $filename;
		$model->db->createCommand()
			->update($model->tableName(), [$valueAttribute => $filename])->execute();;
	}

	public function afterFindMultiple() {
		$model = $this->owner;
		$fileField = $this->valueAttribute;
		$files = $model->{$this->relation};
		$items = [];
		foreach($files as $file) {
			$items[] = [
				'value' => $file->$fileField,
				'label' => $file->$fileField,
				'url' => FileHelper::getModelFileUrl($model, $file->$fileField)
			];
		}
		$model->{$this->formAttribute} = $items;
	}

	public function afterSaveMultiple() {
		$model = $this->owner;
		$relation = $this->relation;

		// move existings files to tmp dir + delete old relations
		$map = [];
		foreach($model->$relation as $file) {
			$filename = $file->filename;
			$filePath = FileHelper::getModelFilePath($model, $filename);
			$tmpPath = FileHelper::createPathForSave(FileHelper::getTemporaryFilePath($filename));
			$map[$filename] = $tmpPath;
			if ( is_file($filePath) )	// model has value but image may be removed from disk
				rename($filePath, $tmpPath);
			$file->delete();
		}
		// move files from tmp dir to model upload dir + save file relation
		foreach($model->{$this->formAttribute} as $item) {
			$value = $item['value'];
			$tmpPath = isset($map[$value]) ? $map[$value] : FileHelper::getTemporaryFilePath($value);
			$filePath = FileHelper::createPathForSave(FileHelper::getModelFilePath($model, $item['label']));
			$filename = basename(($filePath));
			if ( is_file($tmpPath) )
				rename($tmpPath, $filePath);
			// save relation
			$fileModelClass = $model->getRelation($relation)->modelClass;
			$file = new $fileModelClass([
				$this->valueAttribute => $filename
			]);
			$model->link($relation, $file);
		}
	}
}