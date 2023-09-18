<?php
declare(strict_types=1);

namespace VitesseCms\Datafield\Listeners;

use VitesseCms\Cli\ConsoleApplication;
use VitesseCms\Cli\Interfaces\CliListenersInterface;
use VitesseCms\Datafield\Enum\DatafieldEnum;
use VitesseCms\Datafield\Listeners\Models\DatafieldListener;
use VitesseCms\Datafield\Repositories\DatafieldRepository;

class CliListeners implements CliListenersInterface
{
    public static function setListeners(ConsoleApplication $di): void
    {
        $di->eventsManager->attach(DatafieldEnum::LISTENER->value, new DatafieldListener(new DatafieldRepository()));
    }
}
