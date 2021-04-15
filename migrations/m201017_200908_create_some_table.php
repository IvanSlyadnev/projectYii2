<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%some}}`.
 */
class m201017_200908_create_some_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%some}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'description' => $this->text(),
              'content' => $this->text(),
              'date' => $this->text(),
              'image' => $this->string(),
              'viewed' => $this->integer(),
              'user_id' => $this->integer(),
              'status' => $this->integer(),
              'category_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%some}}');
    }
}
