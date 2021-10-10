<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AppExtensions extends AbstractExtension
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pluriel', [$this, 'pluriel']),
        ];
    }

    public function pluriel(int $count, string $singulier, ?string $pluriel = null): string
    {
        $pluriel ??= $singulier .'s';

        $string = $count < 1 || $count === 1 ? $singulier : $pluriel;

        return "$count $string";
    }
}