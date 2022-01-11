<?php

declare(strict_types=1);

namespace App\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;

final class DualSearchFilter extends AbstractContextAwareFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        // otherwise filter is applied to order and page as well
        if (
            !$this->isPropertyEnabled($property, $resourceClass) ||
            !$this->isPropertyMapped($property, $resourceClass)
        ) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $parameterName = $queryNameGenerator->generateParameterName($property); // Generate a unique parameter name to avoid collisions with other filters
        if ('^' === $value[0]) {
            $value = \substr($value, 1);
            $queryBuilder
                ->andWhere(\sprintf('%s.%s = :%s', $rootAlias, $property, $parameterName))
                ->setParameter($parameterName, $value);
        } else {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like(\sprintf('LOWER(%s.%s)', $rootAlias, $property), \sprintf(':%s', $parameterName)))
                ->setParameter($parameterName, \strtolower($value).'%');
        }
    }

    // This function is only used to hook in documentation generators (supported by Swagger and Hydra)
    public function getDescription(string $resourceClass): array
    {
        if (!$this->properties) {
            return [];
        }

        $description = [];
        foreach ($this->properties as $property => $strategy) {
            $description["dual_search_$property"] = [
                'property' => $property,
                'type' => 'string',
                'required' => false,
                'swagger' => [
                    'description' => 'This will apply dual search filter matching strategies (exact, start) on one property. Default matching strategy will be start. To use exact matching strategy, Prepend the caret(^) sign',
                    'name' => $property,
                    'type' => 'string',
                ],
            ];
        }

        return $description;
    }
}
