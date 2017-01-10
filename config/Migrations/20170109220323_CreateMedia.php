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
        $table->string('disk');
        $table->text('manipulations');
        $table->text('custom_properties');
        $table->unsignedInteger('order_column')->nullable();
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();

        $table = $this->table('media');
        $table->addColumn('entity_id', 'integer', [
            'null' => true,
        ]);
        $table->addIndex(['entity_id',]);
        $table->addColumn('entity_class', 'integer', [
            'null' => true,
        ]);
        $table->addIndex(['entity_class',]);
        $table->addIndex(['entity_id', 'entity_class'], [
            'name' => 'I18N_ENTITY_UNIQUE',
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
        $table->addColumn('manipulations', 'text', [
//            'limit' => 255,
        ]);
        $table->addColumn('properties', 'text', [
//            'limit' => 255,
        ]);
        $table->addColumn('sort', 'integer');
        $table->addColumn('created_by', 'integer', [
            'null' => true,
        ]);
        $table->addForeignKey('created_by', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->addIndex(['created_by',]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('deleted', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
