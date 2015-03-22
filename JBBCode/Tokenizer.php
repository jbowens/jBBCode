<?php

namespace JBBCode;

/**
 * This Tokenizer is used while constructing the parse tree. The tokenizer
 * handles splitting the input into brackets and miscellaneous text. The
 * parser is then built as a FSM ontop of these possible inputs.
 *
 * @author jbowens
 */
class Tokenizer
{

    /**
     * @var integer[]
     */
    protected $tokens = array();

    /**
     * @var integer current position in the tokens[] array
     */
    protected $currentPosition = -1;
    protected $string;

    /**
     * Constructs a tokenizer from the given string. The string will be tokenized
     * upon construction.
     *
     * @param string $str the string to tokenize
     */
    public function __construct($str)
    {
        $strLen = strlen($str);
        $this->string = $str;
        $position = 0;

        while($position < $strLen) {
            $offset = strcspn($this->string, '[]', $position);
            if($offset == 0) {
                $this->tokens[] = $position;
                $position++;
            } else {
                $this->tokens[] = $position;
                $position += $offset;
            }
        }
    }

    /**
     * Returns true if there is another token in the token stream.
     * @return boolean
     */
    public function hasNext()
    {
        return isset($this->tokens[$this->currentPosition + 1]);
    }

    /**
     * Advances the token stream to the next token and returns the new token.
     * @return null|string
     */
    public function next()
    {
        if (!$this->hasNext()) {
            return null;
        } else {
            $this->currentPosition++;
            return $this->current();
        }
    }

    /**
     * Retrieves the current token.
     * @return null|string
     */
    public function current()
    {
        if ($this->currentPosition < 0) {
            return null;
        } else {
            $start = $this->tokens[$this->currentPosition];
            if($this->hasNext()) {
                $length = $this->tokens[$this->currentPosition + 1] - $start;
                return substr($this->string, $start, $length);
            } else {
                return substr($this->string, $start);
            }
        }
    }

    /**
     * Moves the token stream back a token.
     */
    public function stepBack()
    {
        if ($this->currentPosition > -1) {
            $this->currentPosition--;
        }
    }

    /**
     * Restarts the tokenizer, returning to the beginning of the token stream.
     */
    public function restart()
    {
        $this->currentPosition = -1;
    }

    /**
     * toString method that returns the entire string from the current index on.
     * @return string
     */
    public function toString()
    {
        if($this->hasNext()) {
            return substr($this->string, $this->tokens[$this->currentPosition + 1]);
        }
        return '';
    }
}
