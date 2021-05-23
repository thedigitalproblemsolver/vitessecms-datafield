<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Listeners;

use Phalcon\Events\Manager;
use VitesseCms\Datafield\Controllers\AdmindatafieldController;
use VitesseCms\Datafield\Listeners\Models\DatafieldListener;
use VitesseCms\Datafield\Models\Datafield;
use VitesseCms\Datafield\Models\FieldModel;
use VitesseCms\Datafield\Models\FieldSizeAndColor;
use VitesseCms\Shop\Enums\SizeAndColorEnum;

class InitiateAdminListeners
{
    public static function setListeners(Manager $eventsManager): void
    {
        $eventsManager->attach('adminMenu', new AdminMenuListener());
        $eventsManager->attach(AdmindatafieldController::class, new AdmindatafieldControllerListener());
        $eventsManager->attach(SizeAndColorEnum::class, new FieldSizeAndColorListener());
        $eventsManager->attach(Datafield::class, new DatafieldListener());
    }
}
