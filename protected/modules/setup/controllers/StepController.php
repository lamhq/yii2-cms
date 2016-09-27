<?php

namespace setup\controllers;

use Yii;
use yii\web\Controller;

class StepController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionDatabase()
    {
        return $this->render('database');
    }

}
