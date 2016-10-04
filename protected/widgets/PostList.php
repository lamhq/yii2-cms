<?php
namespace app\widgets;

use yii\base\Widget;
use app\models\Post;

class PostList extends Widget
{
	/**
	 * @var string
	 */
	public $type;
	
	public function run() {
		switch($this->type) {
			case 'recent':
				$posts = Post::find()->published()->recent(5)->all();
				break;
			default:
				$posts = [];
				break;
		}
		$items = array_map(function ($p) {
			return [
				'label'=>$p->title,
				'url'=>$p->url
			];
		}, $posts);
		return $this->render('post-list',['items'=>$items]);
	}
}
