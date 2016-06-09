<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tag}}".
 *
 * @property integer $id
 * @property string $tagName
 * @property string $info
 * @property boolean $isDelete
 * @property string $createTime
 * @property string $updateTime
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tagName'], 'required'],
            [['info'], 'string'],
            [['isDelete'], 'boolean'],
            [['createTime', 'updateTime'], 'safe'],
            [['tagName'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tagName' => 'Tag Name',
            'info' => 'Info',
            'isDelete' => 'Is Delete',
            'createTime' => 'Create Time',
            'updateTime' => 'Update Time',
        ];
    }

    public function getImages()
    {
        return $this->hasMany(Image::className(), ['id' => 'imageId'])
            ->viaTable('image_tag', ['tagId' => 'id']);
    }
}
