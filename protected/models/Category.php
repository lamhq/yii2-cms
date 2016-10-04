<?php

namespace app\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property integer $status
 * @property string $parent_category_id
 *
 * @property Category $parentCategory
 * @property Category[] $categories
 * @property Post[] $posts
 */
class Category extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public static function getStatuses() {
        return Lookup::items('status');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'parent_category_id'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['parent_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['parent_category_id' => 'id']],
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
            'slug' => Yii::t('app', 'Slug'),
            'status' => Yii::t('app', 'Active'),
            'parent_category_id' => Yii::t('app', 'Parent'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentCategory()
    {
        return $this->hasOne(static::className(), ['id' => 'parent_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(static::className(), ['parent_category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['category_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\query\CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\CategoryQuery(get_called_class());
    }

    public function behaviors() {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'ensureUnique' => true,
                'immutable'=>true,
                // 'slugAttribute' => 'slug',
            ],
        ];
    }

    static public function getListData() {
        return \yii\helpers\ArrayHelper::map(static::find()->all(), 'id', 'name');
    }

    /*
     * convert category models to array for use in Menu widget
     */
    public static function getCategoryMenuItems() {
        $categories = self::findAll(['parent_category_id'=>null]);
        return self::categoriesToMenuItems($categories);
    }

    protected static function categoriesToMenuItems($categories) {
        $items = [];
        foreach ($categories as $category) {
            $item = $category->toMenuItem();
            $item['items'] = self::categoriesToMenuItems($category->categories);
            $items[] = $item;
        }
        return $items;
    }

    public function toMenuItem() {
        return [
            'label'=>$this->name,
            'url'=> $this->url,
        ];
    }

    /**
     * @return string
     */
    public function getUrl() {
        return \yii\helpers\Url::to(['category/view', 'slug'=>$this->slug]);
    }

}
