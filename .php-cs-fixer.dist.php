<?php

declare(strict_types=1);

// Базовый стиль PSR-12 плюс набор Symfony как проектное расширение.
// Рискованные правила выключены, чтобы исключить тихое изменение логики.

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__ . '/src', __DIR__ . '/tests'])
    ->exclude(['vendor', 'var'])
    ->name('*.php');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(false)
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true,
    ])
    ->setFinder($finder);
