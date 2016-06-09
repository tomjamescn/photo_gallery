<?php

use yii\db\Schema;
use yii\db\Migration;

class m151206_122542_add_tag_table extends Migration
{
    public function up()
    {
        $this->createTable('tag', [
            'id' => Schema::TYPE_PK,
            'tagName' => Schema::TYPE_STRING . ' NOT NULL',
            'info' => Schema::TYPE_TEXT,
            'isDelete' => Schema::TYPE_BOOLEAN,
            'createTime' => Schema::TYPE_DATETIME,
            'updateTime' => Schema::TYPE_DATETIME,
        ]);

        $this->createTable('image_tag', [
            'imageId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'tagId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'isDelete' => Schema::TYPE_BOOLEAN,
            'createTime' => Schema::TYPE_DATETIME,
            'updateTime' => Schema::TYPE_DATETIME,
        ]);

        $this->createIndex('index_image_tag', 'image_tag', ['imageId', 'tagId'], true);
    }

    public function down()
    {
        $this->dropTable('image_tag');
        $this->dropTable('tag');
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
