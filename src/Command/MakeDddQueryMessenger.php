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

class MakeDddQueryMessenger extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:ddd:query';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command->setDescription('Permet de généner une classe query messenger pour une feature')
            ->addArgument('nomFeature', InputArgument::REQUIRED, 'Nom de la feature?')
            ->addArgument('nomQuery', InputArgument::REQUIRED, 'Nom de la query messenger?');
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
        $nomQuery = ucfirst($input->getArgument('nomQuery'));
        
        if (!FileSystemService::isDirectoryExist($nomFeature)) {
            throw new RuntimeException("La feature $nomFeature n'existe pas.");
        }

        $this->creerQueryPhp($nomFeature, $nomQuery, $generator);
        $this->creerQueryHandlerPhp($nomFeature, $nomQuery, $generator);
    }

    /**
     * @throws Exception
     */
    public function creerQueryPhp(string $nomFeature, string $nomQuery, Generator $generator): void {
        BusMessageFactory::creerCommandOrQueryPhp(
            $nomFeature,
            $nomQuery,
            $generator,
            MakerDddConstantes::QUERY,
            MakerDddConstantes::TPL_QUERY,
            MakerDddConstantes::QUERY_INTERFACE
        );
    }

    /**
     * @throws Exception
     */
    public function creerQueryHandlerPhp(string $nomFeature, string $nomQuery, Generator $generator): void {
        BusMessageFactory::creerCommandOrQueryPhp($nomFeature,
            $nomQuery,
            $generator,
            MakerDddConstantes::QUERY_HANDLER,
            MakerDddConstantes::TPL_QUERY_HANDLER,
            MakerDddConstantes::QUERY_HANDLER_INTERFACE
        );
    }
}
