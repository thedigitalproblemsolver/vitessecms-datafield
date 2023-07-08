<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Listeners;

use VitesseCms\Core\Interfaces\InitiateListenersInterface;
use VitesseCms\Core\Interfaces\InjectableInterface;
use VitesseCms\Datafield\Controllers\AdmindatafieldController;
use VitesseCms\Datafield\Enum\DatafieldEnum;
use VitesseCms\Datafield\Listeners\Admin\AdminMenuListener;
use VitesseCms\Datafield\Listeners\Controllers\AdmindatafieldControllerListener;
use VitesseCms\Datafield\Listeners\Models\DatafieldListener;
use VitesseCms\Datafield\Models\Datafield;
use VitesseCms\Datafield\Repositories\DatafieldRepository;

class InitiateAdminListeners implements InitiateListenersInterface
{
    public static function setListeners(InjectableInterface $di): void
    {
        $di->eventsManager->attach('adminMenu', new AdminMenuListener());
        $di->eventsManager->attach(AdmindatafieldController::class, new AdmindatafieldControllerListener());
        $di->eventsManager->attach(DatafieldEnum::LISTENER->value, new DatafieldListener(new DatafieldRepository()));

        /** @deprecated if used move rename trigger to DatafieldEnum::LISTENER */
        $di->eventsManager->attach(Datafield::class, new DatafieldListener(new DatafieldRepository()));
    }
}
