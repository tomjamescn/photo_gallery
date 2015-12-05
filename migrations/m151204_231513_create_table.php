<?php

use yii\db\Schema;
use yii\db\Migration;

class m151204_231513_create_table extends Migration
{
    public function up()
    {
        $this->createTable('image', [
            'id' => Schema::TYPE_PK,
            'filePath' => Schema::TYPE_STRING . ' NOT NULL',
            'thumbnailFilePath' => Schema::TYPE_STRING . ' NOT NULL',
            'rawFilePath' => Schema::TYPE_STRING . ' NOT NULL',
            'isDelete' => Schema::TYPE_BOOLEAN,
            'exif' => Schema::TYPE_TEXT,
            'createTime' => Schema::TYPE_DATETIME,
            'updateTime' => Schema::TYPE_DATETIME,
        ]);

        $this->createTable('favorite', [
            'id' => Schema::TYPE_PK,
            'imageId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'note' => Schema::TYPE_TEXT,
            'isDelete' => Schema::TYPE_BOOLEAN,
            'createTime' => Schema::TYPE_DATETIME,
            'updateTime' => Schema::TYPE_DATETIME,
        ]);
    }

    public function down()
    {
        $this->dropTable('image');
        $this->dropTable('favorite');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
