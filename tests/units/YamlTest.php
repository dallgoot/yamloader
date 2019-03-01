<?php

namespace Test\Dallgoot\Yaml;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Dallgoot\Yaml;

/**
 * Class YamlTest.
 *
 * @author Stephane Rebai <stephane.rebai@gmail.com>.
 * @license https://opensource.org/licenses/MIT The MIT license.
 * @link https://github.com/john-doe/my-awesome-project
 * @since File available since Release 1.0.0
 *
 * @covers \Dallgoot\Yaml\Yaml
 */
class YamlTest extends TestCase
{
    /**
     * @var Yaml $yaml An instance of "Yaml" to test.
     */
    private $yaml;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        /** @todo Maybe add some arguments to this constructor */
        $this->yaml = new Yaml();
    }

    /**
     * Covers the global function "isOneOf".
     */
    public function testIsOneOf(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \Dallgoot\Yaml\Yaml::parse
     */
    public function testParse(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \Dallgoot\Yaml\Yaml::parseFile
     */
    public function testParseFile(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \Dallgoot\Yaml\Yaml::dump
     */
    public function testDump(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \Dallgoot\Yaml\Yaml::dumpFile
     */
    public function testDumpFile(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }
}
