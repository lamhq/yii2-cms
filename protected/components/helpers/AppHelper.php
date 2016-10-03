<?php
namespace app\components\helpers;

/**
 * @author Lam Huynh <lamhq.com>
 */
class AppHelper
{
    public static function setSuccess($message) {
        \Yii::$app->getSession()->setFlash('alert', [
            'body'=>$message,
            'options'=>['class'=>'alert-success']
        ]);
    }

    public static function setError($message) {
        \Yii::$app->getSession()->setFlash('alert', [
            'body'=>$message,
            'options'=>['class'=>'alert-error']
        ]);
    }
}
