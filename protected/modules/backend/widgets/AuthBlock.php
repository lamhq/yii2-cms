<?php
namespace backend\widgets;
use app\components\helpers\AuthHelper;

/**
 * ```php
 * <?php AuthBlock::begin(['permission'=>'viewPost']) ?>
 * Some authorized content...
 * <?php AuthBlock::end() ?>
 * ```
 */
class AuthBlock extends \yii\base\Widget
{
	public $permission;
	
	/**
	 * Starts recording a block.
	 */
	public function init()
	{
		ob_start();
		ob_implicit_flush(false);
	}

	/**
	 * Ends recording a block.
	 * This method stops output buffering and saves the rendering result as a named block in the view.
	 */
	public function run()
	{
		$block = ob_get_clean();
		if (AuthHelper::check($this->permission)) {
			echo $block;
		}
	}

}
