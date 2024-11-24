<?php declare(strict_types=1);

namespace MDD\MakerDddBundle\Constante;

final class MakerDddConstantes
{
    public const PATH_API_PLATFORM_YAML = 'config/packages/api_platform.yaml';
    public const DDD = 'ddd';
    public const RAD = 'rad';
    public const PATH_DOCTRINE_MAPPING_YAML = 'config/packages/doctrine_mappings.yaml';

    public const TPL_MODEL = 'EntityModel.tpl.php';
    public const TPL_COMMAND = 'CommandModel.tpl.php';
    public const TPL_COMMAND_HANDLER = 'CommandHandlerModel.tpl.php';
    public const TPL_QUERY = 'QueryModel.tpl.php';
    public const TPL_QUERY_HANDLER = 'QueryHandlerModel.tpl.php';
    public const TPL_PROVIDER = 'ProviderModel.tpl.php';
    public const TPL_PROCESSOR = 'ProcessorModel.tpl.php';

    public const SKELETON_RESOURCE = 'model/' . self::TPL_MODEL;
    public const SKELETON_PROVIDER = 'provider/' . self::TPL_PROVIDER;
    public const SKELETON_PROCESSOR = 'processor/' . self::TPL_PROCESSOR;

    public const COMMAND = 'Command';
    public const COMMAND_HANDLER = 'CommandHandler';
    public const COMMAND_INTERFACE = 'CommandInterface';
    public const COMMAND_HANDLER_INTERFACE = 'CommandHandlerInterface';
    public const QUERY = 'Query';
    public const QUERY_HANDLER = 'QueryHandler';
    public const QUERY_INTERFACE = 'QueryInterface';
    public const QUERY_HANDLER_INTERFACE = 'QueryHandlerInterface';

    public const PROVIDER = 'Provider';
    public const PROCESSOR = 'Processor';

    public const ROOT_DIRECTORY = '/../../../../../';
}
