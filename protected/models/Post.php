<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use app\components\helpers\FileHelper;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property string $id
 * @property string $title
 * @property string $slug
 * @property string $featured_image
 * @property string $short_content
 * @property string $content
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $published_at
 * @property string $created_by
 * @property string $category_id
 *
 * @property Category $category
 * @property PostTag[] $postTags
 * @property Tag[] $tags
 */
class Post extends \yii\db\ActiveRecord
{
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;

	public $status = self::STATUS_ACTIVE;

	public static function getStatuses() {
		return Lookup::items('status');
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%post}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['short_content', 'content'], 'string'],
			[['status'], 'default', 'value' => function () {
				return self::STATUS_ACTIVE;
			}],
			[['status', 'created_by', 'category_id'], 'integer'],
			[['created_at', 'updated_at', 'published_at'], 'safe'],
			[['title', 'slug', 'featured_image'], 'string', 'max' => 255],
			[['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'title' => Yii::t('app', 'Title'),
			'slug' => Yii::t('app', 'Slug'),
			'featured_image' => Yii::t('app', 'Featured Image'),
			'short_content' => Yii::t('app', 'Short Content'),
			'content' => Yii::t('app', 'Content'),
			'status' => Yii::t('app', 'Active'),
			'created_at' => Yii::t('app', 'Created At'),
			'updated_at' => Yii::t('app', 'Updated At'),
			'published_at' => Yii::t('app', 'Published At'),
			'created_by' => Yii::t('app', 'Author'),
			'category_id' => Yii::t('app', 'Category'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(Category::className(), ['id' => 'category_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostTags()
	{
		return $this->hasMany(PostTag::className(), ['post_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTags()
	{
		return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('{{%post_tag}}', ['post_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAuthor()
	{
		return $this->hasOne(User::className(), ['id' => 'created_by']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\query\PostQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \app\models\query\PostQuery(get_called_class());
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			[
				'class' => TimestampBehavior::className(),
				'value' => new \yii\db\Expression('NOW()'),
			],
			[
				'class'=>BlameableBehavior::className(),
				'updatedByAttribute' => false
			],
			[
				'class' => SluggableBehavior::className(),
				'attribute' => 'title',
				'immutable' => true
			],
		];
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return \yii\helpers\Url::to(['post/view', 'slug'=>$this->slug]);
	}

	/**
	 * @return string
	 */
	public function getAuthorName() {
		return $this->author ? $this->author->username : '';
	}

	/**
	 * @return string
	 */
	public function getFeaturedImageUrl($width=null, $height=null) {
		return FileHelper::getModelImageUrl($this, $this->featured_image, $width, $height);
	}

}
