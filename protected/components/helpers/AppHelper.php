<?php
namespace app\components\helpers;
use yii;

/**
 * @author Lam Huynh <lamhq.com>
 */
class AppHelper
{
    public static function setSuccess($message) {
        Yii::$app->getSession()->setFlash('alert', [
            'body'=>$message,
            'options'=>['class'=>'alert-success']
        ]);
    }

    public static function setError($message) {
        Yii::$app->getSession()->setFlash('alert', [
            'body'=>$message,
            'options'=>['class'=>'alert-error']
        ]);
    }

    public static function getTitle() {
        $t = array_filter([ Yii::$app->view->title, Yii::$app->params['siteTitle'] ]);
        return implode(' | ', $t);
    }
}
