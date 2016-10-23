<?php

namespace app\models;

use Yii;
use app\components\CleanupBehavior;

/**
 * This is the model class for table "{{%slideshow}}".
 *
 * @property integer $id
 * @property string $name
 *
 * @property SlideshowImage[] $slideshowImages
 */
class Slideshow extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%slideshow}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name'], 'required'],
			[['name'], 'string', 'max' => 30],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'name' => Yii::t('app', 'Name'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSlideshowImages()
	{
		return $this->hasMany(SlideshowImage::className(), ['slideshow_id' => 'id']);
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			[
				'class' => CleanupBehavior::className(),
				'attribute'=>'filename',
				'relation'=>'slideshowImages',
				'multiple' => true,
			],
		];
	}
}
