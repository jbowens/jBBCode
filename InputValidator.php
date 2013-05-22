<?php

namespace JBBCode;

/**
 * Defines an interface for validation filters for bbcode options and
 * parameters.
 *
 * @author jbowens
 * @since MAy 2013
 */
interface InputValidator
{

    /**
     * Returns true iff the given input is valid, false otherwise.
     */
    public function validate($input); 

}
