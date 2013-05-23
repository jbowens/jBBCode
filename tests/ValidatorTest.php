<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Parser.php';
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'validators' . DIRECTORY_SEPARATOR . 'UrlValidator.php';

/**
 * Test cases for InputValidators.
 *
 * @author jbowens
 * @since May 2013
 */
class ValidatorTest extends PHPUnit_Framework_TestCase
{

    /**
     * Tests an invalid url directly on the UrlValidator.
     */
    public function testInvalidUrl()
    {
        $urlValidator = new \JBBCode\validators\UrlValidator();
        $this->assertFalse($urlValidator->validate('#yolo#swag'));
        $this->assertFalse($urlValidator->validate('giehtiehwtaw352353%3'));
    }

    /**
     * Tests a valid url directly on the UrlValidator.
     */
    public function testValidUrl()
    {
        $urlValidator = new \JBBCode\validators\UrlValidator();
        $this->assertTrue($urlValidator->validate('http://google.com'));
        $this->assertTrue($urlValidator->validate('http://jbbcode.com/docs'));
        $this->assertTrue($urlValidator->validate('https://www.maps.google.com'));
    }

    /**
     * Tests an invalid url as an option to a url bbcode.
     *
     * @depends testInvalidUrl
     */
    public function testInvalidOptionUrlBBCode()
    {
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->parse('[url=javascript:alert("HACKED!");]click me[/url]');
        $this->assertEquals('[url=javascript:alert("HACKED!");]click me[/url]',
                $parser->getAsHtml());
    }

    /**
     * Tests an invalid url as the body to a url bbcode.
     *
     * @depends testInvalidUrl
     */
    public function testInvalidBodyUrlBBCode()
    {
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->parse('[url]javascript:alert("HACKED!");[/url]');
        $this->assertEquals('[url]javascript:alert("HACKED!");[/url]', $parser->getAsHtml());
    }

    /**
     * Tests a valid url as the body to a url bbcode.
     *
     * @depends testValidUrl
     */
    public function testValidUrlBBCode()
    {
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->parse('[url]http://jbbcode.com[/url]');
        $this->assertEquals('<a href="http://jbbcode.com">http://jbbcode.com</a>',
                $parser->getAsHtml());
    }

}
