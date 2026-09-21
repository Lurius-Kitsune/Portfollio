<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class TagsToStringTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): string
    {
        if (!$value) {
            return '';
        }

        return implode(', ', $value);
    }

    public function reverseTransform(mixed $value): array
    {
        if (!$value) {
            return [];
        }

        return array_values(
            array_filter(
                array_map(
                    'trim',
                    explode(',', $value)
                ),
                static fn(string $tag): bool => $tag !== ''
            )
        );
    }
}
