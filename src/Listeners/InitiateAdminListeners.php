<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Listeners;

use Phalcon\Events\Manager;
use VitesseCms\Datafield\Controllers\AdmindatafieldController;
use VitesseCms\Datafield\Models\FieldModel;
use VitesseCms\Datafield\Models\FieldSizeAndColor;

class InitiateAdminListeners
{
    public static function setListeners(Manager $eventsManager): void
    {
        $eventsManager->attach('adminMenu', new AdminMenuListener());
        $eventsManager->attach(AdmindatafieldController::class, new AdmindatafieldControllerListener());
        $eventsManager->attach(FieldModel::class, new FieldModelListener());
        $eventsManager->attach(FieldSizeAndColor::class, new FieldSizeAndColorListener());
    }
}
