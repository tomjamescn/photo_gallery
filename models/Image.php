<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property string $filePath
 * @property string $thumbnailFilePath
 * @property string $rawFilePath
 * @property boolean $isDelete
 * @property string $exif
 * @property string $createTime
 * @property string $updateTime
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filePath', 'thumbnailFilePath', 'rawFilePath'], 'required'],
            [['isDelete'], 'boolean'],
            [['exif'], 'string'],
            [['createTime', 'updateTime'], 'safe'],
            [['filePath', 'thumbnailFilePath', 'rawFilePath'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filePath' => 'File Path',
            'thumbnailFilePath' => 'Thumbnail File Path',
            'rawFilePath' => 'Raw File Path',
            'isDelete' => 'Is Delete',
            'exif' => 'Exif',
            'createTime' => 'Create Time',
            'updateTime' => 'Update Time',
        ];
    }
}
