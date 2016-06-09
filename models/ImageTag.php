<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%image_tag}}".
 *
 * @property integer $imageId
 * @property integer $tagId
 * @property boolean $isDelete
 * @property string $createTime
 * @property string $updateTime
 */
class ImageTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%image_tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imageId', 'tagId'], 'required'],
            [['imageId', 'tagId'], 'integer'],
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
            'imageId' => 'Image ID',
            'tagId' => 'Tag ID',
            'isDelete' => 'Is Delete',
            'createTime' => 'Create Time',
            'updateTime' => 'Update Time',
        ];
    }
}
