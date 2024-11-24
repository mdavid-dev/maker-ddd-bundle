<?php declare(strict_types=1);

namespace MDD\MakerDddBundle\Factory;

use MDD\MakerDddBundle\Constante\MakerDddConstantes;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Bundle\MakerBundle\Util\UseStatementGenerator;

class StateFactory
{
    /**
     * @throws Exception
     */
    private static function creerClassePhp(string $nomFeature, string $nomState, string $typeState, string $templatePath, array $useStatements, Generator $generator): void
    {
        $typeNamespace = $typeState === MakerDddConstantes::PROCESSOR ? 'State\\Processor' : 'State\\Provider';
        $fullClassName = sprintf('App\\%s\\Infrastructure\\ApiPlatform\\%s\\%s%s', $nomFeature, $typeNamespace, $nomState, $typeState);
        $namespacePrefix = "src/$nomFeature/Infrastructure/ApiPlatform/$typeNamespace";
        $classNameDetails = new ClassNameDetails($fullClassName, $namespacePrefix);

        $generator->generateClass(
            $classNameDetails->getFullName(),
            __DIR__ . '/../Resources/skeleton/' . $templatePath,
            [
                'namespace' => $classNameDetails->getRelativeName(),
                'class_name' => $classNameDetails->getShortName(),
                'use_statements' => new UseStatementGenerator($useStatements),
            ]
        );

        $generator->writeChanges();
    }

    public static function creerProcessorPhp(string $nomFeature, string $nomProcessor, Generator $generator): void
    {
        $useStatements = [
            'ApiPlatform\Metadata\Operation',
            'ApiPlatform\State\ProcessorInterface',
            'App\Shared\Application\Command\CommandBusInterface',
        ];

        StateFactory::creerClassePhp($nomFeature, $nomProcessor, MakerDddConstantes::PROCESSOR, MakerDddConstantes::SKELETON_PROCESSOR, $useStatements, $generator);
    }

    public static function creerProviderPhp(string $nomFeature, string $nomProvider, Generator $generator): void
    {
        $useStatements = [
            'ApiPlatform\Metadata\Operation',
            'ApiPlatform\State\ProviderInterface',
            'App\Shared\Application\Query\QueryBusInterface',
        ];

        StateFactory::creerClassePhp($nomFeature, $nomProvider, MakerDddConstantes::PROVIDER, MakerDddConstantes::SKELETON_PROVIDER, $useStatements, $generator);
    }
}
