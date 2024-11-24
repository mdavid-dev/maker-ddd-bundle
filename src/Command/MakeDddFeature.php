<?php declare(strict_types=1);

namespace MDD\MakerDddBundle\Command;

use MDD\MakerDddBundle\Constante\MakerDddConstantes;
use MDD\MakerDddBundle\Factory\FolderFactory;
use MDD\MakerDddBundle\Factory\ResourceFactory;
use MDD\MakerDddBundle\Service\EditConfigService;
use Exception;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * @method string getCommandDescription()
 */
final class MakeDddFeature extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:ddd:feature';
    }

    /**
     * Définition des paramètres et option de la commande
     *      nomFeature : nom de la feature a créer
     *      mode: ddd / rad / ?
     *      api-resource: ajout de l'annotation pour exposer en tant que resouce
     */
    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command->setDescription('Création de la structure d\'une feature en DDD')
            ->addArgument('nomFeature', InputArgument::REQUIRED, 'Nom de la feature?')
            ->addOption('mode', 'a', InputOption::VALUE_REQUIRED, 'Mode en DDD ou RAD? (<fg=yellow>' . MakerDddConstantes::DDD . '</>, <fg=yellow>' . MakerDddConstantes::RAD . '</>, <fg=yellow>?</> pour plus d\'informations)')
            ->addOption('api-resource', 'b', InputOption::VALUE_REQUIRED, 'Marquer cette feature comme ressource API Platform');
    }

    /**
     * Intéraction avec l'utilisateur
     */
    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
        if (!in_array($input->getOption('mode'), [MakerDddConstantes::DDD, MakerDddConstantes::RAD], true)) {
            $question = new Question($command->getDefinition()->getOption('mode')->getDescription());
            $isValid = false;
            do {
                $response = $io->askQuestion($question);
                if (in_array($response, [MakerDddConstantes::DDD, MakerDddConstantes::RAD], true)) {
                    $isValid = true;
                    $input->setOption('mode', $response);
                    continue;
                }
                if ($response === '?') {
                    $io->info(MakerDddConstantes::DDD . ": Création d\'une structure en DDD\n" . MakerDddConstantes::RAD . ": Création d\'une structure en RAD");
                    continue;
                }
                $io->error('Réponse invalide. Veuillez saisir "ddd" ou "rad" ou "?" pour plus d\'informations.');
            } while (!$isValid);
        }

        if(is_null($input->getOption('api-resource'))) {
            $description = $command->getDefinition()->getOption('api-resource')->getDescription();
            $question = new ConfirmationQuestion($description, true);
            $isApiResource = $io->askQuestion($question);

            $input->setOption('api-resource', $isApiResource);
        }
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
        // necessaire pour l'interface
    }

    /**
     * Execution de la commande
     * @throws Exception
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $nomFeature = ucfirst($input->getArgument('nomFeature'));
        $mode = $input->getOption('mode');

        FolderFactory::createFolder($nomFeature, $io);

        if ($mode === MakerDddConstantes::DDD) {
            FolderFactory::createFolder("$nomFeature/Application", $io);
            FolderFactory::createFolder("$nomFeature/Domain/Model", $io);
            FolderFactory::createFolder("$nomFeature/Infrastructure/ApiPlatform/OpenApi", $io);
            FolderFactory::createFolder("$nomFeature/Infrastructure/ApiPlatform/Resource", $io);
            FolderFactory::createFolder("$nomFeature/Infrastructure/ApiPlatform/State", $io);

            if ($input->getOption('api-resource')) {
                ResourceFactory::generateResource($nomFeature, $generator);
            }
        }

        if ($input->getOption('api-resource')) {
            EditConfigService::addConfigForApiPlatform($nomFeature, $io, $mode);
        }

        EditConfigService::addConfigDoctrineMapping($nomFeature, $io, $mode);

        if ($mode === MakerDddConstantes::RAD) {
            FolderFactory::createFolder("$nomFeature/Entity", $io);
            FolderFactory::createFolder("$nomFeature/Repository", $io);

            $io->info(sprintf("Pour lancer la création de l'entity avec Maker, voici la commande à jouer:\n\n\tMAKER_NAMESPACE=App\\\%s php bin/console make:entity %s %s", $nomFeature, $nomFeature, $input->getOption('api-resource') ? "--api-resource" : ""));
        }
    }
}
