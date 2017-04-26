<?php
namespace backend\models;

use yii\base\Model;

/**
 * Password reset request form
 */
class WidthdrawPasswordResettForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email','message'=>'邮箱格式不正确,请输入正确的邮箱'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['enable' => '1'],
                'message' => '这个邮箱没有注册过用户'
            ],
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
        $user = LoginUser::findOne([
            'enable' => '1',
            'email' => $this->email,
        ]);

        if ($user) {
            if (!LoginUser::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {
                return \Yii::$app->mailer->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user])
                    ->setFrom([\Yii::$app->params['supportEmail'] =>'国民旅游环球俱乐部'])
                    ->setTo($this->email)
                    ->setSubject('重置密码-国民旅游环球俱乐部')
                    ->send();
            }
        }

        return false;
    }
}
