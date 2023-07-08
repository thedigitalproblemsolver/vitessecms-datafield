<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Listeners\Models;

use Phalcon\Events\Event;
use VitesseCms\Datafield\Models\Datafield;
use VitesseCms\Datafield\Repositories\DatafieldRepository;

class DatafieldListener
{
    public function __construct(private readonly DatafieldRepository $datafieldRepository)
    {
    }

    public function getRepository(): DatafieldRepository
    {
        return $this->datafieldRepository;
    }

    public function beforeSave(Event $event, Datafield $datafield): void
    {
        var_dump('DatafieldListener - beforeSave: '.$datafield->getType());
        die();
        $datafield->getDi()->eventsManager->fire($datafield->getType().':beforeSave', $datafield);
    }
}