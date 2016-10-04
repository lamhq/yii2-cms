<?php

namespace app\models\query;
use app\models\Post;

/**
 * This is the ActiveQuery class for [[\app\models\Post]].
 *
 * @see \app\models\Post
 */
class PostQuery extends \yii\db\ActiveQuery
{
	/*public function active()
	{
		return $this->andWhere('[[status]]=1');
	}*/

	/**
	 * @inheritdoc
	 * @return \app\models\Post[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\Post|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}

	public function published()
	{
		$this->andWhere(['status' => Post::STATUS_ACTIVE]);
		$this->andWhere(['<', '{{%post}}.published_at', date('Y-m-d H:i:s')]);
		return $this;
	}

	public function recent($count) {
		return $this->limit($count)
		->orderBy('published_at DESC');
	}

	public function byKeyword($s) {
		// allow to use double quote for fully match term. ex "all the word"
		$terms = preg_match('/\".*?\"/', $s) ? [trim($s, '"')] : explode(' ', $s);
		foreach ($terms as $term) {
			$this->andWhere(
				['or', ['like', 'title', $term], ['like', 'content', $term], ['like', 'short_content', $term]]
			);			
		}
		return $this;
	}
	
}
