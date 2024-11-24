<?php declare(strict_types=1);

namespace MDD\MakerDddBundle\Factory;

use ApiPlatform\Metadata\ApiResource;
use MDD\MakerDddBundle\Constante\MakerDddConstantes;
use Exception;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Bundle\MakerBundle\Util\UseStatementGenerator;

final class ResourceFactory
{
    /**
     * Fonction qui permet de générer une resource php pour api platform
     * @throws Exception
     */
    public static function generateResource(string $nomFeature, Generator $generator): void
    {
        $fullClassName = sprintf('App\\%s\\Infrastructure\\ApiPlatform\\Resource\\%s', $nomFeature, $nomFeature);
        $namespacePrefix = "src/$nomFeature/Infrastructure/ApiPlatform/Resource";
        $classNameDetails = new ClassNameDetails($fullClassName, $namespacePrefix);

        $useStatements = new UseStatementGenerator([]);
        $useStatements->addUseStatement(class_exists(ApiResource::class) ? ApiResource::class : \ApiPlatform\Core\Annotation\ApiResource::class);

        $generator->generateClass(
            $classNameDetails->getFullName(),
            __DIR__ . '/../Resources/skeleton/' . MakerDddConstantes::SKELETON_RESOURCE,
            [
                'namespace' => $classNameDetails->getRelativeName(),
                'class_name' => $classNameDetails->getShortName(),
                'use_statements' => $useStatements,
            ]
        );

        $generator->writeChanges();
    }
}
