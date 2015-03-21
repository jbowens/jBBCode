<?php

require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Parser.php');

/**
 * Test cases testing the ability to parse bbcode and retrieve a
 * plain text representation without any markup.
 *
 * @author jbowens
 */
class BBCodeToTextTest extends PHPUnit_Framework_TestCase
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

    public function testEmptyString()
    {
        $this->assertEmpty($this->_parser->parse('')->getAsText());
    }

    public function testContentCleared()
    {
        $this->assertEquals('foo', $this->_parser->parse('foo')->getAsText());
        $this->assertEquals('bar', $this->_parser->parse('bar')->getAsText());
    }

    /**
     * @param string $code
     * @param string $expected
     * @dataProvider codeProvider
     */
    public function testParse($code, $expected)
    {
        $this->assertEquals($expected, $this->_parser->parse($code)->getAsText());
    }

    public function codeProvider()
    {
        return array(
            array('foo', 'foo'),
            array('[b]this is bold[/b]', 'this is bold'),
            array('[b]this is bold', 'this is bold'),
            array(
                'buffer text [b]this is bold[/b] buffer text',
                'buffer text this is bold buffer text'
            ),
            array(
                'this is some text with [b]bold tags[/b] and [i]italics[/i] and things like [u]that[/u].',
                'this is some text with bold tags and italics and things like that.'
            ),
            array(
                'This contains a [url=http://jbbcode.com]url[/url] which uses an option.',
                'This contains a url which uses an option.'
            ),
            array(
                'This doesn\'t use the url option [url]http://jbbcode.com[/url].',
                'This doesn\'t use the url option http://jbbcode.com.'
            ),
        );
    }
}
