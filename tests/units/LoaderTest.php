<?php

namespace Test\Dallgoot\Yaml;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Dallgoot\Yaml\Loader;
use Generator;
use Dallgoot\Yaml\Node;

/**
 * Class LoaderTest.
 *
 * @author Stephane Rebai <stephane.rebai@gmail.com>.
 * @license https://opensource.org/licenses/MIT The MIT license.
 * @link https://github.com/john-doe/my-awesome-project
 * @since File available since Release 1.0.0
 *
 * @covers \Dallgoot\Yaml\Loader
 */
class LoaderTest extends TestCase
{
    /**
     * @var Loader $loader An instance of "Loader" to test.
     */
    private $loader;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        /** @todo Maybe check arguments of this constructor. */
        $this->loader = new Loader("a string to test", "a string to test", "a string to test");
    }

    /**
     * @covers \Dallgoot\Yaml\Loader::__construct
     */
    public function testConstruct(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \Dallgoot\Yaml\Loader::load
     */
    public function testLoad(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \Dallgoot\Yaml\Loader::getSourceGenerator
     */
    public function testGetSourceGenerator(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \Dallgoot\Yaml\Loader::parse
     */
    public function testParse(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \Dallgoot\Yaml\Loader::attachBlankLines
     */
    public function testAttachBlankLines(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \Dallgoot\Yaml\Loader::needsSpecialProcess
     */
    public function testNeedsSpecialProcess(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \Dallgoot\Yaml\Loader::onError
     */
    public function testOnError(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }
}
