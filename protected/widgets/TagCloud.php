<?php
namespace app\widgets;

use yii\base\Widget;
use app\models\Tag;

class TagCloud extends Widget
{
	public $minSize = 8;

	public $maxSize = 30;

	public function run() {

		$tags = Tag::find()
			->select(['{{%tag}}.*', 'COUNT({{%post}}.id) as postCount'])
			->joinWith('posts')
			->orderBy('name')
			->groupBy('{{%tag}}.id')
			->all();
		$maxPostCount = 0;
		foreach($tags as $tag)
			$maxPostCount = max($maxPostCount, $tag->postCount);
		$items = array_map(function ($tag) use ($maxPostCount) {
			$n = max($this->minSize, floor($tag->postCount*$this->maxSize/$maxPostCount));
			return [
				'label'=>$tag->name,
				'url'=>$tag->url,
				'options'=>['style'=>"font-size: {$n}px"]
			];
		}, $tags);
		return $this->render('tag-cloud', ['items'=>$items]);
	}
}
