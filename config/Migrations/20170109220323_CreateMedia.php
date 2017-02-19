<?php
use Migrations\AbstractMigration;

class CreateMedia extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('media');
        $table->addColumn('entity_id', 'integer', [
            'null' => true,
        ]);
        $table->addIndex(['entity_id',]);
        $table->addColumn('entity_class', 'string', [
            'null' => true,
        ]);
        $table->addIndex(['entity_class',]);
        $table->addIndex(['entity_id', 'entity_class'], [
            'unique' => true,
        ]);
        $table->addColumn('title', 'string', [
            'limit' => 255,
        ]);
        $table->addColumn('file_name', 'string', [
            'limit' => 255,
        ]);
        $table->addColumn('collection_name', 'string', [
            'limit' => 255,
        ]);
        $table->addColumn('size', 'integer', [
//            'limit' => 255,
        ]);
        $table->addColumn('mime_type', 'string', [
            'limit' => 255,
        ]);
        $table->addColumn('manipulations', 'text', [
            'null' => true,
        ]);
        $table->addColumn('properties', 'text', [
            'null' => true,
        ]);
        $table->addColumn('disk', 'string', [
            'limit' => 255,
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
        $table->create();

        $table = $this->table('media_i18n');
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
