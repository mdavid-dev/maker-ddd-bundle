<?php declare(strict_types=1);

namespace MDD\MakerDddBundle\Command;

use MDD\MakerDddBundle\Factory\StateFactory;
use MDD\MakerDddBundle\Service\FileSystemService;
use Exception;
use RuntimeException;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

final class MakeDddProcessor extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:ddd:processor';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command->setDescription('Permet de généner une classe api processor pour une feature')
            ->addArgument('nomFeature', InputArgument::REQUIRED, 'Nom de la feature?')
            ->addArgument('nomProcessor', InputArgument::REQUIRED, 'Nom du processor de l\'api?');
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
        // necessaire pour l'interface
    }

    /**
     * @throws Exception
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $nomFeature = ucfirst($input->getArgument('nomFeature'));
        $nomProcessor = ucfirst($input->getArgument('nomProcessor'));

        if (!FileSystemService::isDirectoryExist($nomFeature)) {
            throw new RuntimeException("La feature {$nomFeature} n'existe pas.");
        }

        StateFactory::creerProcessorPhp($nomFeature, $nomProcessor, $generator);
    }
}
