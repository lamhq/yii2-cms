<?php
namespace app\models;

use Yii;
use app\models\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class ForgotPasswordForm extends Model
{
	/**
	 * @var user email
	 */
	public $email;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['email', 'filter', 'filter' => 'trim'],
			['email', 'required'],
			['email', 'email'],
			// comment this rule for security
			// ['email', 'exist',
			// 	'targetClass' => '\app\models\User',
			// 	'filter' => ['status' => User::STATUS_ACTIVE],
			// 	'message' => 'There is no user with such email.'
			// ],
		];
	}

	/**
	 * Sends an email with a link, for resetting the password.
	 *
	 * @return boolean whether the email was send
	 */
	public function sendEmail()
	{
		/* @var $user User */
		$user = User::findOne([
			'status' => User::STATUS_ACTIVE,
			'email' => $this->email,
		]);

		if ($user) {
			// create token for one day
			$token = UserToken::create($user->id, UserToken::TYPE_PASSWORD_RESET, 86400);
			if ($user->save()) {
				\app\components\Mailer::sendMailResetPassword($user, $token);
			}
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return [
			'email'=>Yii::t('app', 'Send password reset email to:')
		];
	}
}
