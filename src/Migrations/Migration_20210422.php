<?php

declare(strict_types=1);

namespace VitesseCms\Datafield\Migrations;

use stdclass;
use VitesseCms\Database\AbstractMigration;
use VitesseCms\Datafield\Enum\DatafieldEnum;
use VitesseCms\Datafield\Repositories\DatafieldRepository;

class Migration_20210422 extends AbstractMigration
{
    private readonly DatafieldRepository $datafieldRepository;

    public function up(): bool
    {
        $this->datafieldRepository = $this->eventsManager->fire(DatafieldEnum::GET_REPOSITORY->value, new stdClass());

        $result = true;
        if (!self::parseDatafieldType()) :
            $result = false;
        endif;
        if (!self::parseDatafieldModels()) :
            $result = false;
        endif;

        return $result;
    }

    private function parseDatafieldType(): bool
    {
        $result = true;
        $datafields = $this->datafieldRepository->findAll(null, false);
        $search = ['Modules\Datafield\Models\\'];
        $replace = ['VitesseCms\Datafield\Models\\'];

        while ($datafields->valid()):
            $datafield = $datafields->current();
            $type = str_replace($search, $replace, $datafield->getType());
            if (substr_count($type, '\\') === 0):
                $type = 'VitesseCms\Datafield\Models\\' . $type;
            endif;
            if (substr_count($type, 'VitesseCms\Datafield\Models') === 1):
                $datafield->setType($type)->save();
            else :
                $this->terminalService->printError(
                    'Wrong type "' . str_replace(
                        $search,
                        $replace,
                        $datafield->getType()
                    ) . '" for datafield "' . $datafield->getNameField() . '"'
                );
                $result = false;
            endif;
            $datafields->next();
        endwhile;

        $this->terminalService->printMessage('datafields type repaired');

        return $result;
    }

    private function parseDatafieldModels(): bool
    {
        echo 'b';
        $result = true;
        $datafields = $this->datafieldRepository->findAll(null, false);
        $search = ['Modules\\'];
        $replace = ['VitesseCms\\'];
        while ($datafields->valid()):
            $datafield = $datafields->current();
            $model = str_replace($search, $replace, $datafield->getModel());
            if (!empty($model) && str_starts_with($model, 'VitesseCms')) :
                $datafield->setModel($model)->save();
            elseif (!empty($model)) :
                $this->terminalService->printError(
                    'wrong model "' . $model . '" for datafield "' . $datafield->getNameField() . '"'
                );
                $result = false;
            endif;

            $datafields->next();
        endwhile;
        $this->terminalService->printMessage('datafields model repaired');

        return $result;
    }
}