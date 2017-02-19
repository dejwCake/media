<?php
namespace DejwCake\Media\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use DejwCake\Media\Model\Table\MediaTable;

/**
 * DejwCake\Media\Model\Table\MediaTable Test Case
 */
class MediaTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \DejwCake\Media\Model\Table\MediaTable
     */
    public $Media;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.dejw_cake/media.media',
        'plugin.dejw_cake/media.media_title_translation',
        'plugin.dejw_cake/media.media_i18n',
        'plugin.dejw_cake/media.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Media') ? [] : ['className' => 'DejwCake\Media\Model\Table\MediaTable'];
        $this->Media = TableRegistry::get('Media', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Media);

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
