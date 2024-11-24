<?= "<?php declare(strict_types=1);\n" ?>

namespace <?= $namespace ?>;

<?= $use_statements; ?>

final readonly class <?= $class_name ?> implements CommandHandlerInterface
{
public function __construct()
{
}

public function __invoke(<?= $message_bus_name ?>Command $command)
{
}
}
