<?php

use yii\db\Migration;

/**
 * Handles the creation of table `question`.
 */
class m170927_133411_create_question_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('question', [
            'id' => $this->primaryKey(),
            'title' => $this->text(),
            'author_id' => $this->integer()->notNull(),
        ]);

        // creates index for column 'author_id'
        $this->createIndex(
            'idx-question-author_id',
            'question',
            'author_id'
        );

        // add foreign key for table 'user'
        $this->addForeignKey(
            'fk-question-author_id',
            'question',
            'author_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table 'user'
        $this->dropForeignKey(
            'fk-question-author_id',
            'question'
        );

        // drops index for column 'author_id'
        $this->dropIndex(
            'idx-question-author_id',
            'question'
        );

        $this->dropTable('question');
    }
}
