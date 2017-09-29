<?php

use yii\db\Migration;

/**
 * Handles adding date to table `answer`.
 */
class m170928_134150_add_date_column_to_answer_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('answer', 'date', $this->dateTime());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('answer', 'date');
    }
}
