<?php declare(strict_types=1);

namespace MDD\MakerDddBundle\Service;

use MDD\MakerDddBundle\Constante\MakerDddConstantes;
use Symfony\Component\Filesystem\Filesystem;

final class FileSystemService
{
    public static function isDirectoryExist(string $nomFeature): bool
    {
        $filesystem = new Filesystem();
        $directoryPath = __DIR__ . MakerDddConstantes::ROOT_DIRECTORY . "src/$nomFeature";

        return $filesystem->exists($directoryPath);
    }
}
