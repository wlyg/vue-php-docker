<?php
namespace app\models;

class User extends BaseModel
{
    public static function collectionName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            ['mobile', 'password', 'openid']
        );
    }

    public static function upsert($openid, $uid, $passwd)
    {
        $user = User::find()->where(["openid" =>$openid])->one();
        if (empty($user)) {
            $user = new self();
        }
        $user->mobile = $uid;
        $user->password = $passwd;
        $user->openid = $openid;
        $user->isDeleted = self::NOT_DELETED;

        return $user->save();
    }
}
