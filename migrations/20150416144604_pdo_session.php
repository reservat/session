<?php

use Phinx\Migration\AbstractMigration;

class PdoSession extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('session');
        $table
            ->addColumn('session_id', 'string', array('limit' => 30))
            ->addColumn('data', 'text')
            ->addColumn('user_id', 'integer')
            ->addColumn('expires', 'datetime')
            ->create()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('session')->drop();
    }
}
