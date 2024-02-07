<?php
declare(strict_types=1);

namespace VitesseCms\Datafield\Listeners;

use VitesseCms\Core\Interfaces\InitiateListenersInterface;
use VitesseCms\Core\Interfaces\InjectableInterface;
use VitesseCms\Datafield\Enum\DatafieldEnum;
use VitesseCms\Datafield\Listeners\Admin\AdminMenuListener;
use VitesseCms\Datafield\Listeners\ContentTags\TagDatafieldListener;
use VitesseCms\Datafield\Listeners\Models\DatafieldListener;
use VitesseCms\Datafield\Repositories\DatafieldRepository;

class InitiateListeners implements InitiateListenersInterface
{
    public static function setListeners(InjectableInterface $injectable): void
    {
        if ($injectable->user->hasAdminAccess()) :
            $injectable->eventsManager->attach('adminMenu', new AdminMenuListener());
        endif;
        $injectable->eventsManager->attach(
            'contentTag',
            new TagDatafieldListener(
                new DatafieldRepository(),
                $injectable->eventsManager,
                $injectable->assets
            )
        );
        $injectable->eventsManager->attach(
            DatafieldEnum::LISTENER->value,
            new DatafieldListener(new DatafieldRepository())
        );
    }
}
