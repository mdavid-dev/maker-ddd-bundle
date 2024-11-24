<?= "<?php declare(strict_types=1);\n" ?>

namespace <?= $namespace ?>;

<?= $use_statements; ?>

final readonly class <?= $class_name ?> implements ProcessorInterface
{
public function __construct(private CommandBusInterface $commandBus) {
}

public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [],): mixed
{
}
}
