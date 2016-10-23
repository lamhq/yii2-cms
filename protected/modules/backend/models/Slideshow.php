<?php

namespace backend\models;

use Yii;
use app\components\UploadBehavior;

class Slideshow extends \app\models\Slideshow
{
	public $images;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return array_merge(parent::rules(), [
			[['images'], 'safe']
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return array_merge(parent::behaviors(), [
			[
				'class' => UploadBehavior::className(),
				'valueAttribute'=>'filename',
				'formAttribute'=>'images',
				'relation'=>'slideshowImages',
				'multiple' => true,
			],
		]);
	}
	
}
