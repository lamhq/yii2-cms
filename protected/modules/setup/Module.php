<?php

namespace setup;
use Yii;
use yii\base\Controller;

class Module extends \yii\base\Module {

	public $defaultRoute = 'step/database';
	
	public function init() {
		return parent::init();
	}

}
