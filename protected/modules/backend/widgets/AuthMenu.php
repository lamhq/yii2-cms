<?php
namespace backend\widgets;

class AuthMenu extends \yii\widgets\Menu
{
	/**
	 * @inheritdoc
	 */
	protected function normalizeItems($items, &$active)
	{
		foreach ($items as &$item) {
			if ( isset($item['permission']) ) {
				$can = \app\components\helpers\AuthHelper::check($item['permission']);
				$item['visible'] = $can && (isset($item['visible']) ? $item['visible'] : true);
			}
		}
		return parent::normalizeItems($items, $active);
	}
}
