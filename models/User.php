<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\models\UserRules;
use app\models\Roles;
use app\models\DefaultRoute;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'users';
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['login' => $username]);
    }

    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return "";
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

    public function getFull_name()
    {
        return $this->surname." ".$this->name;
    }

    public function isPermissionAction($arr)
    {
        if ($this->role == 1)
            return true;

        if ($arr["c"] == "site" and $arr['a'] == 'logout')
            return true;

        if ($arr["c"] == "site" and $arr['a'] == 'login')
            return true;

        $permission = UserRules::find()->where(['id_role' => $this->role, 'controller' => $arr["c"], 'action' => '*'])->one();

        if ($permission)
            return true;

        $permission = UserRules::find()->where(['id_role' => $this->role, 'controller' => $arr["c"], 'action' => $arr['a']])->one();

        if ($permission)
            return true;
    }


    public function getRrole()
    {
        return $this->hasOne(Roles::className(), ['id' => 'role']);
    }

    public function getDefaultRoute()
    {
        return $this->hasOne(DefaultRoute::className(), ['id_role' => 'role']);
    }

    public function homeUrl()
    {
        $res = ['fuel-delivery/index'];

        if ($this->defaultRoute)
            $res = [$this->defaultRoute->route];

        return $res;
    }

    

}