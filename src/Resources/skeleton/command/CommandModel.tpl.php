<?= "<?php declare(strict_types=1);\n" ?>

namespace <?= $namespace ?>;

<?= $use_statements; ?>

final readonly class <?= $class_name ?> implements CommandInterface
{
public function __construct()
{
}
}
