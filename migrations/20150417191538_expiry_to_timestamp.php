<?php

use Phinx\Migration\AbstractMigration;

namespace Reservat\Migrations;

class ExpiryToTimestamp extends AbstractMigration
{
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('session');
        $table
            ->changeColumn('expires', 'integer')
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table('session');
        $table
            ->changeColumn('expires', 'datetime')
            ->save();
    }
}
