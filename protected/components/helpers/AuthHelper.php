<?php
namespace app\components\helpers;
use yii;

/**
 * @author Lam Huynh <lamhq.com>
 */
class AuthHelper {

    static public function check($permission) {
        $can = false;
        if (is_array($permission)) {
            foreach ($permission as $p) {
                $can = $can | \Yii::$app->user->can($p);
            }
        } else {
            $can = \Yii::$app->user->can($permission);
        }
        return $can;
    }

}
