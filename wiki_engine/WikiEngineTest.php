<?php
require_once 'WikiEngine.php';

class WikiEngineTest extends PHPUnit_Framework_TestCase
{
    protected $wiki_engine;

    public function setUp()
    {
        $this->wiki_engine = new WikiEngine();
    }

    public function testToHtmlReturnArgumentValue()
    {
        $input    = 'TDD Bootcamp';
        $expected = 'TDD Bootcamp';

        $this->assertEquals($expected, $this->wiki_engine->toHtml($input));
    }

    /**
     * @dataProvider notStringTypesProvider
     */
    public function testToHtmlArgumentNotStringType($not_str_type)
    {
        try {
            $this->wiki_engine->toHtml($not_str_type);
        }
        catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
            return;
        }

        $this->fail();
    }

    public function notStringTypesProvider()
    {
        return array(
            array(null),            // Null
            array(5),               // Integer
            array(3.14),            // Float
            array(array()),         // Array
            array(true),            // Boolean
            array(new stdClass),    // Object
        );
    }

    public function testToHtmlHeading()
    {
        $input    = '= Heading =';
        $expected = '<h1>Heading</h1>';

        $this->assertEquals($expected, $this->wiki_engine->toHtml($input));

        $input    = '=== Heading3 ===';
        $expected = '<h3>Heading3</h3>';

        $this->assertEquals($expected, $this->wiki_engine->toHtml($input));
    }

    public function testToHtmlHeadingLevelOver7UnSupport()
    {
        $input    = '======= HeadingLevel7 =======';
        $expected = '======= HeadingLevel7 =======';

        $this->assertEquals($expected, $this->wiki_engine->toHtml($input));
    }

    public function testToHtmlMultiLines()
    {
        $input = <<<'EOL'
== Heading2 ==
Hello World :)
EOL;
        $expected = <<<'EOL'
<h2>Heading2</h2>
Hello World :)
EOL;

        $this->assertEquals($expected, $this->wiki_engine->toHtml($input));
    }
}
