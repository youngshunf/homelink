<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vote_result".
 *
 * @property integer $id
 * @property integer $vote_id
 * @property string $user_guid
 * @property string $vote_item_id
 * @property string $created_at
 */
class VoteResult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vote_result';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
     
     
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vote_id' => 'Vote ID',
            'user_guid' => '用户',
            'vote_items' => '选项',
            'created_at' => '投票时间',
        ];
    }
}
