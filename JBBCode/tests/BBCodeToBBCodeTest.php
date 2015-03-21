<?php

require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Parser.php');

/**
 * Test cases testing the functionality of parsing bbcode and
 * retrieving a bbcode well-formed bbcode representation.
 *
 * @author jbowens
 */
class BBCodeToBBCodeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var JBBCode\Parser
     */
    private $_parser;

    protected function setUp()
    {
        $this->_parser = new JBBCode\Parser();
        $this->_parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
    }

    /**
     * A utility method for these tests that will evaluate its arguments as bbcode with
     * a fresh parser loaded with only the default bbcodes. It returns the
     * bbcode output, which in most cases should be in the input itself.
     */
    private function defaultBBCodeParse($bbcode)
    {
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->parse($bbcode);
        return $parser->getAsBBCode();
    }

    public function testEmptyString()
    {
        $this->assertEmpty($this->_parser->parse('')->getAsBBCode());
    }

    public function testContentCleared()
    {
        $this->assertEquals('foo', $this->_parser->parse('foo')->getAsBBCode());
        $this->assertEquals('bar', $this->_parser->parse('bar')->getAsBBCode());
    }

    /**
     * @param string $code
     * @param string $expected
     * @dataProvider codeProvider
     */
    public function testParse($code, $expected)
    {
        $this->assertEquals($expected, $this->_parser->parse($code)->getAsBBCode());
    }

    public function codeProvider()
    {
        return array(
            array('foo', 'foo'),
            array('[b]this is bold[/b]', '[b]this is bold[/b]'),
            array('[b]bold', '[b]bold[/b]'),
            array(
                'buffer text [b]this is bold[/b] buffer text',
                'buffer text [b]this is bold[/b] buffer text'
            ),
            array(
                'this is some text with [b]bold tags[/b] and [i]italics[/i] and things like [u]that[/u].',
                'this is some text with [b]bold tags[/b] and [i]italics[/i] and things like [u]that[/u].',
            ),
            array(
                'This contains a [url=http://jbbcode.com]url[/url] which uses an option.',
                'This contains a [url=http://jbbcode.com]url[/url] which uses an option.'
            ),
            array(
                'This doesn\'t use the url option [url]http://jbbcode.com[/url].',
                'This doesn\'t use the url option [url]http://jbbcode.com[/url].'
            ),
        );
    }
}
