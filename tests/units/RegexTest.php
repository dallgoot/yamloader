<?php

namespace Test\Dallgoot\Yaml;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Dallgoot\Yaml\Regex;

/**
 * Class RegexTest.
 *
 * @author Stephane Rebai <stephane.rebai@gmail.com>.
 * @license https://opensource.org/licenses/MIT The MIT license.
 * @link https://github.com/john-doe/my-awesome-project
 * @since File available since Release 1.0.0
 *
 * @covers \Dallgoot\Yaml\Regex
 */
class RegexTest extends TestCase
{
    /**
     * @var Regex $regex An instance of "Regex" to test.
     */
    private $regex;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        /** @todo Maybe add some arguments to this constructor */
        $this->regex = new Regex();
    }

    /**
     * @covers \Dallgoot\Yaml\Regex::isDate
     */
    public function testIsDate(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \Dallgoot\Yaml\Regex::isNumber
     */
    public function testIsNumber(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \Dallgoot\Yaml\Regex::isProperlyQuoted
     */
    public function testIsProperlyQuoted(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }
}
