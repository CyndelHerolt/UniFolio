<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class GroupByFilter extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('group_by', [$this, 'groupBy']),
        ];
    }

    public function groupBy(array $elements, string $property): array
    {
        $grouped = [];
        foreach ($elements as $element) {
            if (defined(get_class($element) . '::' . $property)) {
                $key = constant(get_class($element) . '::' . $property);
            } else {
                $key = $element->$property;
            }
            $grouped[$key][] = $element;
        }

        return $grouped;
    }
}