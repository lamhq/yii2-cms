<?php

namespace backend\models;

use Yii;
use yii\helpers\Url;

/**
 * @inheritdoc
 */
class Category extends \app\models\Category
{
	public function toMenuItem() {
		return [
			'label'=>$this->name,
			'url'=> Url::to(['/backend/category/index', 'id'=>$this->id]),
			'options'=>['class'=>$this->status==self::STATUS_ACTIVE ? '' : 'in-active']
		];
	}

	/*
	 * convert category models to array use in dropdown box
	 */
	public static function getCategoryDropdownList($excludeId=null) {
		$categories = self::findAll(['parent_category_id'=>null]);
		return self::categoriesToDropdownItems($categories, $excludeId);
	}

	private static function categoriesToDropdownItems($categories, $excludeId=null, $level=0) {
		$items = [];
		foreach ($categories as $category) {
			if ($category->id==$excludeId) continue;
			
			$item = str_repeat('&nbsp;&nbsp;&nbsp;', $level).$category->name;
			$items[$category->id] = $item;
			$items = $items+self::categoriesToDropdownItems($category->categories, $excludeId, $level+1);
		}
		return $items;
	}

}
