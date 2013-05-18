<?php

require_once('../Parser.php');

class TextToTextTest extends PHPUnit_Framework_TestCase {

    /**
     * A utility method for these tests that will evaluate
     * its arguments as bbcode with a fresh parser loaded
     * with only the default bbcodes. It returns the
     * text output.
     */
    private function defaultTextParse($bbcode) {
        $parser = new JBBCode\Parser();
        $parser->loadDefaultCodes();
        $parser->parse($bbcode);
        return $parser->getAsText();
    }

    /**
     * Asserts that the given bbcode matches the given text when
     * the bbcode is run through defaultTextParse
     */
    private function assertTextOutput($bbcode, $text) {
        $this->assertEquals($this->defaultTextParse($bbcode), $text);
    }


    public function testEmptyString() {
        $this->assertTextOutput('', '');
    }

    public function testOneTag() {
        $this->assertTextOutput('[b]this is bold[/b]', 'this is bold');
    }

    public function testOneTagWithSurroundingText() {
        $this->assertTextOutput('buffer text [b]this is bold[/b] buffer text',
                              'buffer text this is bold buffer text');
    }

    public function testMultipleTags() {
        $bbcode = 'this is some text with [b]bold tags[/b] and [i]italics[/i] and ' .
                  'things like [u]that[/u].';
        $text = 'this is some text with bold tags and italics and things like that.';
        $this->assertTextOutput($bbcode, $text);
    }

    public function testCodeOptions() {
        $code = 'This contains a [url=http://jbbcode.com]url[/url] which uses an option.';
        $text = 'This contains a url which uses an option.';
        $this->assertTextOutput($code, $text);
    }

    /**
     * @depends testCodeOptions
     */
    public function testOmittedOption() {
        $code = 'This doesn\'t use the url option [url]http://jbbcode.com[/url].';
        $text = 'This doesn\'t use the url option http://jbbcode.com.';
        $this->assertTextOutput($code, $text);
    }

}
