<?php
namespace app\components;
use yii\swiftmailer\Message;
use app\models\EmailTemplate;

/**
 * Class Mailer
 * send all email in system
 * @author Lam Huynh <lamhq.com>
 */
class Mailer
{
    public static function sendMail($name, $params, $from, $to) {
        $email = EmailTemplate::findOne([
            'name' => $name,
        ]);
        if (!$email) return false;

        $subject = strtr($email->subject, $params);
        $content = strtr($email->body, $params);
        \Yii::$app->mailer->view->title = $subject;
        $message = \Yii::$app->mailer->compose('all', ['content'=>$content]);
        $message->setSubject($subject);
        $message->setFrom($from);
        $message->setTo($to);
        return $message->send();
    }

    public static function sendMailResetPassword($user, $token) {
        $resetLink = \yii\helpers\Url::to(['/backend/site/reset-password', 'token' => $token->token], true);
        $params = [
            '{{username}}' => $user->username,
            '{{email}}' => $user->email,
            '{{link}}' => $resetLink,
        ];
        return self::sendMail('reset-password', $params, \Yii::$app->params['robotEmail'], $user->email);
    }
}
