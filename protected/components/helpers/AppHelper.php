<?php
namespace app\components\helpers;
use yii;

/**
 * @author Lam Huynh <lamhq.com>
 */
class AppHelper
{
    static public function setSuccess($message) {
        Yii::$app->getSession()->setFlash('alert', [
            'body'=>$message,
            'options'=>['class'=>'alert-success']
        ]);
    }

    static public function setError($message) {
        Yii::$app->getSession()->setFlash('alert', [
            'body'=>$message,
            'options'=>['class'=>'alert-error']
        ]);
    }

    static public function getPageTitle() {
        $t = array_filter([ Yii::$app->view->title, Yii::$app->params['siteTitle'] ]);
        return implode(' | ', $t);
    }

    static public function params($name) {
        return Yii::$app->params[$name];
    }
}
