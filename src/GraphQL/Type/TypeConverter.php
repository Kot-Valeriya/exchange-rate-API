<?php

namespace App\GraphQL\Type;

use ApiPlatform\Core\GraphQl\Type\TypeConverterInterface;
use App\Entity\ExchangeRate;
use GraphQL\Type\Definition\Type as GraphQLType;
use Symfony\Component\PropertyInfo\Type;

final class TypeConverter implements TypeConverterInterface {
	private $defaultTypeConverter;

	public function __construct(TypeConverterInterface $defaultTypeConverter) {
		$this->defaultTypeConverter = $defaultTypeConverter;
	}

	/**
	 * {@inheritdoc}
	 */
	public function convertType(Type $type, bool $input,  ? string $queryName,  ? string $mutationName, string $resourceClass, string $rootResource,  ? string $property, int $depth) {
		if ('date' === $property
			&& ExchangeRate::class === $resourceClass
		) {
			return 'DateTime';
		}

		return $this->defaultTypeConverter->convertType($type, $input, $queryName, $mutationName, $resourceClass, $rootResource, $property, $depth);
	}

	/**
	 * {@inheritdoc}
	 */
	public function resolveType(string $type) :  ? GraphQLType {
		return $this->defaultTypeConverter->resolveType($type);
	}
}