<?php declare(strict_types=1);

namespace MDD\MakerDddBundle\Command;

use MDD\MakerDddBundle\Constante\MakerDddConstantes;
use MDD\MakerDddBundle\Factory\BusMessageFactory;
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

final class MakeDddCommandMessenger extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:ddd:command';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command->setDescription('Permet de généner une classe command messenger pour une feature')
            ->addArgument('nomFeature', InputArgument::REQUIRED, 'Nom de la feature?')
            ->addArgument('nomCommand', InputArgument::REQUIRED, 'Nom de la command messenger?');
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
        // Pour l'interface
    }

    /**
     * @throws Exception
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $nomFeature = ucfirst($input->getArgument('nomFeature'));
        $nomCommand = ucfirst($input->getArgument('nomCommand'));
        
        if (!FileSystemService::isDirectoryExist($nomFeature)) {
            throw new RuntimeException("La feature $nomFeature n'existe pas.");
        }

        $this->creerCommandPhp($nomFeature, $nomCommand, $generator);
        $this->creerCommandHandlerPhp($nomFeature, $nomCommand, $generator);
    }

    /**
     * @throws Exception
     */
    public function creerCommandPhp(string $nomFeature, string $nomCommand, Generator $generator): void
    {
        BusMessageFactory::creerCommandOrQueryPhp(
            $nomFeature,
            $nomCommand,
            $generator,
            MakerDddConstantes::COMMAND,
            MakerDddConstantes::TPL_COMMAND,
            MakerDddConstantes::COMMAND_INTERFACE
        );
    }

    /**
     * @throws Exception
     */
    public function creerCommandHandlerPhp(string $nomFeature, string $nomCommand, Generator $generator): void
    {
        BusMessageFactory::creerCommandOrQueryPhp($nomFeature,
            $nomCommand,
            $generator,
            MakerDddConstantes::COMMAND_HANDLER,
            MakerDddConstantes::TPL_COMMAND_HANDLER,
            MakerDddConstantes::COMMAND_HANDLER_INTERFACE
        );
    }
}
