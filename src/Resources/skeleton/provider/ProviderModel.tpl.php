<?= "<?php declare(strict_types=1);\n" ?>

namespace <?= $namespace ?>;

<?= $use_statements; ?>

final readonly class <?= $class_name ?> implements ProviderInterface
{
public function __construct(private QueryBusInterface $queryBus) {
}

public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
{
}
}
