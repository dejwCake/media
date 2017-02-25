<?php

use Phinx\Migration\AbstractMigration;

class CreateGalleries extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('galleries');
        $table->addColumn('title', 'string', [
            'limit' => 255,
        ]);
        $table->addColumn('slug', 'string', [
            'limit' => 255,
        ]);
        $table->addColumn('text', 'text', [
            'null' => true,
        ]);
        $table->addColumn('enabled_in_locales', 'string', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('sort', 'integer');
        $table->addColumn('created', 'datetime');
        $table->addColumn('modified', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('deleted', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addIndex(['title', 'deleted'], [
            'unique' => true,
        ]);
        $table->addIndex(['slug', 'deleted'], [
            'unique' => true,
        ]);
        $table->create();

        $table = $this->table('galleries_i18n');
        $table->addColumn('locale', 'string', [
            'default' => null,
            'limit' => 6,
            'null' => false,
        ]);
        $table->addColumn('model', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('foreign_key', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('field', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('content', 'text', [
            'default' => null,
            'null' => false,
        ]);

        $table->addIndex(['locale',], [
            'name' => 'locale',
            'unique' => false,
        ]);
        $table->addIndex(['model',], [
            'name' => 'model',
            'unique' => false,
        ]);
        $table->addIndex(['foreign_key',], [
            'name' => 'row_id',
            'unique' => false,
        ]);
        $table->addIndex(['field',], [
            'name' => 'field',
            'unique' => false,
        ]);
        $table->addIndex(['locale', 'model', 'foreign_key', 'field',], [
            'name' => 'I18N_LOCALE_FIELD',
            'unique' => true,
        ]);
        $table->addIndex(['model', 'foreign_key', 'field',], [
            'name' => 'I18N_FIELD',
            'unique' => false,
        ]);
        $table->create();
    }
}
