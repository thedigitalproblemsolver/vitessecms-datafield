<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Listeners\Models;

use Phalcon\Events\Event;
use VitesseCms\Datafield\Models\Datafield;

class DatafieldListener
{
    public function beforeSave(Event $event, Datafield $datafield): void
    {
        $datafield->getDi()->eventsManager->fire($datafield->getType().':beforeSave', $datafield);
    }
}