<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "favorite".
 *
 * @property integer $id
 * @property integer $imageId
 * @property string $note
 * @property boolean $isDelete
 * @property string $createTime
 * @property string $updateTime
 */
class Favorite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'favorite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imageId'], 'required'],
            [['imageId'], 'integer'],
            [['note'], 'string'],
            [['isDelete'], 'boolean'],
            [['createTime', 'updateTime'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'imageId' => 'Image ID',
            'note' => 'Note',
            'isDelete' => 'Is Delete',
            'createTime' => 'Create Time',
            'updateTime' => 'Update Time',
        ];
    }
}
