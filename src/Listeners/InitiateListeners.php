<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Listeners;

use VitesseCms\Core\Interfaces\InitiateListenersInterface;
use VitesseCms\Core\Interfaces\InjectableInterface;
use VitesseCms\Datafield\Listeners\Admin\AdminMenuListener;
use VitesseCms\Datafield\Listeners\ContentTags\TagDatafieldListener;
use VitesseCms\Datafield\Repositories\DatafieldRepository;

class InitiateListeners implements InitiateListenersInterface
{
    public static function setListeners(InjectableInterface $di): void
    {
        if($di->user->hasAdminAccess()) :
            $di->eventsManager->attach('adminMenu', new AdminMenuListener());
        endif;
        $di->eventsManager->attach('contentTag', new TagDatafieldListener(
            new DatafieldRepository(),
            $di->eventsManager,
            $di->assets
        ));
    }
}
