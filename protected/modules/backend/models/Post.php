<?php

namespace backend\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Tag;
use app\components\UploadBehavior;

/**
 * @inheritdoc
 */
class Post extends \app\models\Post
{
	public $tagNames = [];
	public $featuredImage;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return array_merge(parent::rules(), [
			[['published_at'], 'filter', 'skipOnEmpty' => true, 'filter' => function ($value) {
				return \app\components\helpers\DateHelper::toDbDatetime($value);
			}],
			[['tagNames', 'featuredImage'], 'safe']
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), [
			'tagNames' => Yii::t('app', 'Tags'),
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return array_merge(parent::behaviors(), [
			'saveFeaturedImage' => [
				'class' => UploadBehavior::className(),
				'multiple' => false,
				'valueAttribute'=>'featured_image',
				'formAttribute'=>'featuredImage',
			],
		]);
	}

	public function afterFind () {
		$this->tagNames = ArrayHelper::getColumn($this->tags, 'name');
		parent::afterFind();
	}

	public function afterSave ( $insert, $changedAttributes ) {
		$this->saveSelectedTags($insert);
		parent::afterSave($insert, $changedAttributes);
	}

	public function saveSelectedTags($insert=false) {
		// delete all relations belong to this model
		if (!$insert) {
			$this->db->createCommand()->delete('{{%post_tag}}', ['post_id'=>$this->id])->execute();
		}
		
		// save new relations
		$data = [];
		$names = is_array($this->tagNames) ? $this->tagNames : [];
		foreach ($names as $name) {
			$name = trim($name);
			if (!$name) continue;
			
			$tag = Tag::findOne(['name' => $name]);
			if (!$tag) {
				$tag = new Tag(['name'=>$name]);
				$tag->save();
			}
			
			$data[] = [$this->id, $tag->id];
		}
		$this->db->createCommand()->batchInsert('{{%post_tag}}', ['post_id', 'tag_id'], $data)->execute();
		
		// delete empty tag
		Tag::deleteAll('id not in (select tag_id from {{%post_tag}})');
	}
}
