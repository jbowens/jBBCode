<?php

namespace JBBCode;

/**
 * @author Jackson Owens
 * 
 * The TokenManager is used when constructing the parse tree. Before parsing begins, the TokenManager separates the string into
 * left and right brackets ("[", "]") and string to make parsing easier.
 * 
 */
class TokenManager {
    
    protected $tokens;
    protected $i = 0;
    
    /**
     * Tokenizes the inputted string
     * 
     * @param string $str   the string to tokenize
     */
    public function __construct( $str ) {
        $this->tokens = array();
        foreach(preg_split('/([\[\]])/', $str, -1, PREG_SPLIT_DELIM_CAPTURE) as $s)
        {
            if( $s != "" )
                array_push($this->tokens, $s);
        }
        $this->restart();
    }
    
    /**
     * Returns true if there is another token after the current one.
     * 
     * @return true if there is another token to be read
     */
    public function hasNext() {
        return count( $this->tokens ) > ($this->i + 1);
    }
    
    /**
     * Returns true if there is a current token.
     *
     * @return true if there is a current token
     */
    public function hasCurrent() {
        return count( $this->tokens ) > $this->i;
    }

    /**
     * Returns the current token.
     * 
     * @return the current token
     */
    public function current() {
        return $this->tokens[$this->i];
    }   
    
    /**
     * Alias for getCurrent()
     *
     * @return the current token
     */
    public function getCurrent() {
        return $this->current();
    }
    
    /**
     * Returns the next token.
     * 
     * @return the next token
     */
    public function next() {
        if( $this->hasNext() )
            return $this->tokens[$this->i+1];
        else
            return null;
    }
    
    /**
     * Alias for next().
     *
     * @return the next token
     */
    public function getNext() {
        return $this->next();
    }
    
    /**
     * Returns the array of all the tokens.
     * 
     * @return all tokens
     */
    public function getAllTokens() {
        return $this->tokens;
    }
    
    /**
     * Moves the pointer back to the first token.
     */
    public function restart() {
        $this->i = 0;
    }
    
    /**
     * Advances the token pointer to the next token.
     */
    public function advance() {
        $this->i++;
    }
    
}

