<?php
namespace DejwCake\Media\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use DejwCake\Media\Model\Table\GalleriesTable;

/**
 * DejwCake\Media\Model\Table\GalleriesTable Test Case
 */
class GalleriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \DejwCake\Media\Model\Table\GalleriesTable
     */
    public $Galleries;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.dejw_cake/media.galleries',
        'plugin.dejw_cake/media.galleries_title_translation',
        'plugin.dejw_cake/media.galleries_slug_translation',
        'plugin.dejw_cake/media.galleries_text_translation',
        'plugin.dejw_cake/media.galleries_i18n'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Galleries') ? [] : ['className' => 'DejwCake\Media\Model\Table\GalleriesTable'];
        $this->Galleries = TableRegistry::get('Galleries', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Galleries);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
