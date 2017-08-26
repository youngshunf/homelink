<?php

namespace common\models;

use Yii;
use backend\models\AdminUser;

/**
 * This is the model class for table "auth_user".
 *
 * @property integer $id
 * @property string $name
 * @property string $big_district
 * @property string $business_district
 * @property string $shop
 * @property string $role_name
 * @property string $work_number
 * @property string $up_work_number
 * @property string $created_at
 */
class AuthUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at','pid'], 'integer'],
            [['name', 'big_district', 'business_district'], 'string', 'max' => 20],
            [['shop'], 'string', 'max' => 255],
            [['role_name', 'work_number', 'up_work_number'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '姓名',
            'big_district' => '运营管理大区',
            'business_district' => '业务大区',
            'shop' => '门店',
            'role_name' => '角色名称',
            'work_number' => '工号',
            'up_work_number' => '上级工号',
            'created_at' => '导入时间',
        ];
    }
    
    public function getPuser(){
        return $this->hasOne(AdminUser::className(), ['id'=>'pid']);
    }
}
