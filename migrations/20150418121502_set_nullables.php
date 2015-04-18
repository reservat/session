<?php

use Phinx\Migration\AbstractMigration;

class SetNullables extends AbstractMigration
{

    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('session');
        $table
            ->changeColumn('data', 'text', ['null' => true])
            ->changeColumn('user_id', 'text', ['null' => true])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table('session');
        $table
            ->changeColumn('data', 'text', ['null' => false])
            ->changeColumn('user_id', 'text', ['null' => false])
            ->save();
    }
    
}