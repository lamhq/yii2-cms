<?php
namespace app\components\helpers;
use yii;

/**
 * @author Lam Huynh <lamhq.com>
 */
class AuthHelper {

    /**
     * Checks if the current user has access on a permission or any permissions in list.
     *
     * @param string|array $permission the name of the permission or an array of permission names
     * @return boolean whether the user has access on a permission or permisions.
     */
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
