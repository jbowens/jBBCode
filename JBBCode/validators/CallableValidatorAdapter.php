<?php

namespace JBBCode\validators;

use JBBCode,
	JBBCode\InputValidator;

require_once __DIR__ . '/../InputValidator.php';

/**
 * An adapter for callable input validator.
 * 
 * This class is most often created by {@link CodeDefinitionBuilder}
 * and used as wrapper for different types of `callable` validators.
 *
 * @author   Kubo2
 * @since    August 2015
 */
final class CallableValidatorAdapter implements InputValidator {

	/** @var callable */
	private $validator;

	/**
	 * Constructs adapter's instance, taking `callable` validator as parameter.
	 * 
	 * @param callable $validator   The callable validator
	 */
	// public function __construct(callable $validator) { // PHP 5.3 is end-of-life, why do I have to bother with this
	public function __construct($validator) {
		if(!is_callable($validator)) {
			throw new \InvalidArgumentException('$validator must be callable, ' .
				(is_object($validator) ? get_class($validator) : gettype($validator)) . ' given');
		}

		$this->validator = $validator;
	}

	/**
	 * {@inheritdoc}
	 * @return boolean   Anything returned by the callable validator, coerced to boolean
	 */
	public function validate($input) {
		return (boolean) call_user_func($this->validator, $input);
	}
}
