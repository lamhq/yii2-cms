<?php
namespace app\widgets;

use yii\base\Widget;
use app\models\Category;

class Search extends Widget
{
	public function run() {
		return $this->render('search');
	}
}
