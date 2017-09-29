<?php

use yii\db\Migration;

/**
 * Handles adding photo to table `user`.
 */
class m170928_113956_add_photo_column_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user', 'photo', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('user', 'photo');
    }
}
