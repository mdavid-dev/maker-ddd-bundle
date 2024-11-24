<?php declare(strict_types=1);

namespace MDD\MakerDddBundle\Service;

use MDD\MakerDddBundle\Constante\MakerDddConstantes;
use Exception;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Component\Yaml\Yaml;

final class EditConfigService
{
    /**
     * Fonction qui permet d'ajouter la configuration nécessaire dans api_resouce.yaml pour mapper une resource
     * @throws Exception
     */
    public static function addConfigForApiPlatform(string $nomFeature, ConsoleStyle $io, string $mode): void
    {
        $yamlPath = MakerDddConstantes::PATH_API_PLATFORM_YAML;

        if (!file_exists($yamlPath)) {
            throw new RuntimeException("Le fichier api_platform.yaml n'existe pas.");
        }

        $yamlContents = file_get_contents($yamlPath);
        $yamlArray = Yaml::parse($yamlContents);
        $newPathEntry = '%kernel.project_dir%/src/' . $nomFeature . ($mode === MakerDddConstantes::DDD ? '/Infrastructure/ApiPlatform/Resource/' : '/Entity/');
        $yamlArray['api_platform']['mapping']['paths'][] = $newPathEntry;
        $updatedContents = Yaml::dump($yamlArray, 4, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);

        if (file_put_contents($yamlPath, $updatedContents)) {
            $io->success('Le fichier "api_platform.yaml" a été mis à jour.');
            return;
        }
        $io->error('Échec de la mise à jour du fichier "api_platform.yaml".');
    }

    /**
     * Fonction qui permet d'ajouter la configuration nécessaire dans doctrine_mapping.yaml.yaml pour le mapping doctrine
     * @throws Exception
     */
    public static function addConfigDoctrineMapping(string $nomFeature, ConsoleStyle $io, string $mode): void
    {
        $filePath = MakerDddConstantes::PATH_DOCTRINE_MAPPING_YAML;
        $folderByMode = $mode === MakerDddConstantes::DDD ? 'Domain' : 'Entity';

        if (!file_exists($filePath)) {
            throw new RuntimeException("Le fichier doctrine_mappings.yaml n'existe pas.");
        }

        $content = file_get_contents($filePath);
        $yamlArray = Yaml::parse($content);
        $codeToAdd = [
            $nomFeature => [
                'is_bundle' => false,
                'dir' => "%kernel.project_dir%/src/$nomFeature/$folderByMode",
                'prefix' => "App\\$nomFeature\\$folderByMode",
                'alias' => $nomFeature,
            ],
        ];

        $yamlArray['doctrine']['orm']['mappings'] = array_merge($yamlArray['doctrine']['orm']['mappings'], $codeToAdd);
        $updatedContents = Yaml::dump($yamlArray, 10, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);
        if (false === file_put_contents($filePath, $updatedContents)) {
            throw new RuntimeException("Le path $$filePath n'existe pas.");
        }

        $io->success('Le fichier "octrine_mappings.yaml" a été mis à jour.');
    }
}
