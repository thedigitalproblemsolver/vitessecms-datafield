<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Migrations;

use VitesseCms\Cli\Services\TerminalServiceInterface;
use VitesseCms\Configuration\Services\ConfigServiceInterface;
use VitesseCms\Datafield\Repositories\AdminRepositoryCollection;
use VitesseCms\Datafield\Repositories\DatafieldRepository;
use VitesseCms\Install\Interfaces\MigrationInterface;

class Migration_20210422 implements MigrationInterface
{
    /**
     * @var AdminRepositoryCollection
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new AdminRepositoryCollection(
            new DatafieldRepository()
        );
    }

    public function up(ConfigServiceInterface $configService, TerminalServiceInterface $terminalService): bool
    {
        $result = true;
        if (!self::parseDatafieldType($terminalService)) :
            $result = false;
        endif;
        if (!self::parseDatafieldModels($terminalService)) :
            $result = false;
        endif;

        return $result;
    }

    private function parseDatafieldType(TerminalServiceInterface $terminalService): bool
    {
        $result = true;
        $datafields = $this->repository->datafield->findAll(null, false);
        $search = ['Modules\Datafield\Models\\'];
        $replace = ['VitesseCms\Datafield\Models\\'];
        while ($datafields->valid()):
            $datafield = $datafields->current();
            $type = str_replace($search,$replace,$datafield->getType());
            if(substr_count($type,'\\') === 0 ):
                $type = 'VitesseCms\Datafield\Models\\'.$type;
            endif;
            if(substr_count($type,'VitesseCms\Datafield\Models') === 1 ):
                $datafield->setType($type)->save();
            else :
                $terminalService->printError('srong type "'.str_replace($search,$replace,$datafield->getType()).'" for datafiel '.$datafield->getNameField());
            endif;
            $datafields->next();
        endwhile;

        $terminalService->printMessage('datafields type repaired');
        return $result;
    }

    private function parseDatafieldModels(TerminalServiceInterface $terminalService): bool
    {
        $result = true;
        $datafields = $this->repository->datafield->findAll(null, false);
        $search = [
            'Modules\\'
        ];
        $replace = [
            'VitesseCms\\'
        ];
        while ($datafields->valid()):
            $datafield = $datafields->current();
            $model = str_replace($search, $replace, $datafield->getModel());
            if (!empty($model) && substr($model, 0, 10) === 'VitesseCms') :
                $datafield->setModel($model)->save();
            elseif(!empty($model)) :
                $terminalService->printError('wrong model "' . $model . '" for datafield "' . $datafield->getNameField() . '"');
                $result = false;
            endif;

            $datafields->next();
        endwhile;
        $terminalService->printMessage('datafields model repaired');

        return $result;
    }
}