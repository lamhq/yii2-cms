<?php
namespace app\widgets;

use yii\base\Widget;
use app\models\Category;

class CategoryList extends Widget
{
	public function run() {
		return $this->render('category-list',['items'=>Category::getCategoryMenuItems()]);
	}
}
