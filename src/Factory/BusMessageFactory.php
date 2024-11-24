<?php declare(strict_types=1);

namespace MDD\MakerDddBundle\Factory;

use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Bundle\MakerBundle\Util\UseStatementGenerator;

final class BusMessageFactory
{
    /**
     * Permet de générer un les fichiers command ou query pour l'envoi de message dans le bus messenger
     */
    public static function creerCommandOrQueryPhp(string $nomFeature, string $nomClass, Generator $generator, string $suffix, string $template, string $interface): void
    {
        $className = BusMessageFactory::buildClassName($nomFeature, $nomClass, $suffix);
        BusMessageFactory::generateClass($className, $generator, $template, $interface, $nomClass);
    }

    /**
     * Permet de générer le class name
     */
    public static function buildClassName(string $nomFeature, string $nomClass, string $suffix): string
    {
        return sprintf('App\\%s\\Application\\%s\\%s%s', $nomFeature, preg_replace('/(Handler)$/', '', $suffix), $nomClass, $suffix);
    }

    /**
     * @throws Exception
     */
    public static function generateClass(string $className, Generator $generator, string $template, string $useStatement, string $commandOrQueryName = null): void
    {
        $namespacePrefix = 'src/' . str_replace("\\", "/", $className);
        $classNameDetails = new ClassNameDetails($className, $namespacePrefix);

        $useStatements = new UseStatementGenerator([]);
        $folderCommandOrQuery = preg_replace('/(Interface|HandlerInterface)$/', '', $useStatement);
        $useStatements->addUseStatement('App\Shared\Application\\' . $folderCommandOrQuery . '\\' . $useStatement);

        $parameters = [
            'namespace' => $classNameDetails->getRelativeName(),
            'class_name' => $classNameDetails->getShortName(),
            'use_statements' => $useStatements,
        ];

        if ($commandOrQueryName !== null) {
            $parameters['message_bus_name'] = $commandOrQueryName;
        }

        $generator->generateClass(
            $classNameDetails->getFullName(),
            __DIR__ . '/../Resources/skeleton/' . strtolower($folderCommandOrQuery) . '/' . $template,
            $parameters
        );

        $generator->writeChanges();
    }
}
