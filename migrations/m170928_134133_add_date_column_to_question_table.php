<?php

use yii\db\Migration;

/**
 * Handles adding date to table `question`.
 */
class m170928_134133_add_date_column_to_question_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('question', 'date', $this->dateTime());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('question', 'date');
    }
}
