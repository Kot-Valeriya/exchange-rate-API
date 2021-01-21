<?php

namespace App\GraphQL\Type;

use GraphQL\Error\Error;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;

final class DateTimeType extends ScalarType implements AliasedInterface {
	public function __construct() {
		$this->name = 'DateTime';
		$this->description = 'The `DateTime` scalar type represents time data.';

		parent::__construct();
	}

	public function getName(): string {
		return $this->name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function serialize($value) {
		// Already serialized.
		if (\is_string($value)) {
			return (new \DateTime($value))->format('Y-m-d');
		}

		if (!($value instanceof \DateTime)) {
			throw new Error(sprintf('Value must be an instance of DateTime to be represented by DateTime: %s', Utils::printSafe($value)));
		}

		return $value->format(\DateTime::ATOM);
	}

	/**
	 * {@inheritdoc}
	 */
	public function parseValue($value) {
		if (!\is_string($value)) {
			throw new Error(sprintf('DateTime cannot represent non string value: %s', Utils::printSafeJson($value)));
		}

		if (false === \DateTime::createFromFormat(\DateTime::ATOM, $value)) {
			throw new Error(sprintf('DateTime cannot represent non date value: %s', Utils::printSafeJson($value)));
		}

		// Will be denormalized into a \DateTime.
		return $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function parseLiteral($valueNode,  ? array $variables = null) {
		if ($valueNode instanceof StringValueNode && false !== \DateTime::createFromFormat(\DateTime::ATOM, $valueNode->value)) {
			return $valueNode->value;
		}

		throw new \Exception();
	}
	public static function getAliases() : array{
		return ['DateTime'];
	}
}