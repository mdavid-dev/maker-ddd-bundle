<?php declare(strict_types=1);

namespace MDD\MakerDddBundle\Factory;

use MDD\MakerDddBundle\Constante\MakerDddConstantes;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Component\Filesystem\Filesystem;

final class FolderFactory
{
    /**
     * Fonction qui permet de créer un dossier
     */
    public static function createFolder(string $nomDossier, ConsoleStyle $io): void
    {
        $filesystem = new Filesystem();
        $directoryPath = __DIR__ . MakerDddConstantes::ROOT_DIRECTORY . "src/$nomDossier";

        if (!$filesystem->exists($directoryPath)) {
            $filesystem->mkdir($directoryPath);
            $gitKeepFile = "$directoryPath/.gitkeep";
            $filesystem->touch($gitKeepFile);
            $io->success("Dossier \"$directoryPath\" créé avec succès.");
            return;
        }
        $io->error("Dossier \"$directoryPath\" existe déjà.");
    }
}
