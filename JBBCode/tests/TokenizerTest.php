<?php

require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Tokenizer.php');

/**
 * Test cases testing the functionality of the Tokenizer. The tokenizer
 * is used by the parser to make parsing simpler.
 *
 * @author jbowens
 */
class TokenizerTest extends PHPUnit_Framework_TestCase
{

    public function testEmptyString()
    {
        $tokenizer = new JBBCode\Tokenizer('');
        $this->assertEmpty($tokenizer->toString());
        $this->assertFalse($tokenizer->hasNext());
        $this->assertNull($tokenizer->next());
        $this->assertEmpty($tokenizer->toString());
    }

    public function testPlainTextOnly()
    {
        $tokenizer = new JBBCode\Tokenizer('this is some plain text.');
        $this->assertEquals('this is some plain text.', $tokenizer->toString());
        $this->assertTrue($tokenizer->hasNext());
        $this->assertEquals('this is some plain text.', $tokenizer->next());
        $this->assertFalse($tokenizer->hasNext());
        $this->assertEquals('this is some plain text.', $tokenizer->current());
        $this->assertFalse($tokenizer->hasNext());
        $this->assertNull($tokenizer->next());
    }

    public function testStartingBracket()
    {
        $tokenizer = new JBBCode\Tokenizer('[this has a starting bracket.');
        $this->assertTrue($tokenizer->hasNext());
        $this->assertEquals('[', $tokenizer->next());
        $this->assertEquals('[', $tokenizer->current());
        $this->assertTrue($tokenizer->hasNext());
        $this->assertEquals('this has a starting bracket.', $tokenizer->next());
        $this->assertEquals('this has a starting bracket.', $tokenizer->current());
        $this->assertFalse($tokenizer->hasNext());
        $this->assertNull($tokenizer->next());
    }

    public function testOneTag()
    {
        $tokenizer = new JBBCode\Tokenizer('[b]');
        $this->assertTrue($tokenizer->hasNext());
        $this->assertEquals('[', $tokenizer->next());
        $this->assertTrue($tokenizer->hasNext());
        $this->assertEquals('b', $tokenizer->next());
        $this->assertTrue($tokenizer->hasNext());
        $this->assertEquals(']', $tokenizer->next());
        $this->assertFalse($tokenizer->hasNext());
        $this->assertNull($tokenizer->next());
    }

    public function testMatchingTags()
    {
        $tokenizer = new JBBCode\Tokenizer('[url]http://jbbcode.com[/url]');
        $this->assertEquals('[', $tokenizer->next());
        $this->assertEquals('url', $tokenizer->next());
        $this->assertEquals(']', $tokenizer->next());
        $this->assertEquals('http://jbbcode.com', $tokenizer->next());
        $this->assertEquals('[', $tokenizer->next());
        $this->assertEquals('/url', $tokenizer->next());
        $this->assertEquals(']', $tokenizer->next());
        $this->assertFalse($tokenizer->hasNext());
    }

    public function testLotsOfBrackets()
    {
        $tokenizer = new JBBCode\Tokenizer('[[][]][');
        $this->assertEquals('[', $tokenizer->next());
        $this->assertEquals('[', $tokenizer->next());
        $this->assertEquals(']', $tokenizer->next());
        $this->assertEquals('[', $tokenizer->next());
        $this->assertEquals(']', $tokenizer->next());
        $this->assertEquals(']', $tokenizer->next());
        $this->assertEquals('[', $tokenizer->next());
        $this->assertFalse($tokenizer->hasNext());
    }
}

