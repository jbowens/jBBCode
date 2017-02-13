<?php


class ParsingUpperCaseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var JBBCode\Parser
     */
    private $_parser;

    protected function setUp()
    {
        $this->_parser = new JBBCode\Parser();
        $this->_parser->addCodeDefinitionSet(new JBBCode\UppercasedCodeDefinitionSet());
    }

    /**
     * @param string $code
     * @param string[] $expected
     * @dataProvider textCodeProvider
     */
    public function testParse($code, $expected)
    {
        $parser = $this->_parser->parse($code);
        $this->assertEquals($expected['text'], $parser->getAsText());
        $this->assertEquals($expected['html'], $parser->getAsHTML());
        $this->assertEquals($expected['bbcode'], $parser->getAsBBCode());
    }

    public function textCodeProvider()
    {
        return array(
            array(
                'foo',
                array(
                    'text' => 'foo',
                    'html' => 'foo',
                    'bbcode' => 'foo',
                )
            ),
            array(
                '[B]this is bold[/B]',
                array(
                    'text' => 'this is bold',
                    'html' => '<strong>this is bold</strong>',
                    'bbcode' => '[B]this is bold[/B]',
                )
            ),
            array(
                '[B]this is bold',
                array(
                    'text' => 'this is bold',
                    'html' => '<strong>this is bold</strong>',
                    'bbcode' => '[B]this is bold[/B]',
                )
            ),
            array(
                'buffer text [B]this is bold[/B] buffer text',
                array(
                    'text' => 'buffer text this is bold buffer text',
                    'html' => 'buffer text <strong>this is bold</strong> buffer text',
                    'bbcode' => 'buffer text [B]this is bold[/B] buffer text',
                )
            ),
            array(
                'this is some text with [B]bold tags[/B] and [I]italics[/I] and things like [U]that[/U].',
                array(
                    'text' => 'this is some text with bold tags and italics and things like that.',
                    'html' => 'this is some text with <strong>bold tags</strong> and <em>italics</em> and things like <u>that</u>.',
                    'bbcode' => 'this is some text with [B]bold tags[/B] and [I]italics[/I] and things like [U]that[/U].',
                )
            ),
            array(
                'This contains a [URL=http://jbbcode.com]url[/URL] which uses an option.',
                array(
                    'text' => 'This contains a url which uses an option.',
                    'html' => 'This contains a <a href="http://jbbcode.com">url</a> which uses an option.',
                    'bbcode' => 'This contains a [URL=http://jbbcode.com]url[/URL] which uses an option.',
                )
            ),
            array(
                'This doesn\'t use the url option [URL]http://jbbcode.com[/URL].',
                array(
                    'text' => 'This doesn\'t use the url option http://jbbcode.com.',
                    'html' => 'This doesn\'t use the url option <a href="http://jbbcode.com">http://jbbcode.com</a>.',
                    'bbcode' => 'This doesn\'t use the url option [URL]http://jbbcode.com[/URL].',
                )
            ),
        );
    }

    /**
     * @param string $code
     *
     * @dataProvider textCodeProviderWithInvalidCode
     */
    public function testParseInvalidCode($code)
    {
        $parser = $this->_parser->parse($code);
        $this->assertEquals($code, $parser->getAsText());
        $this->assertEquals($code, $parser->getAsHTML());
        $this->assertEquals($code, $parser->getAsBBCode());
    }

    public function textCodeProviderWithInvalidCode()
    {
        return array(
            array('This is some text with an [URL]I N V A L I D[/URL] URL tag.'),
            array('This is some text with an [URL foo=bar]INVALID[/URL] URL tag.'),
            array('This is some text with an invalid [URL=INVALID]URL[/URL] tag.')
        );
    }
}
