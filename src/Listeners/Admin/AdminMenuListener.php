<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Listeners\Admin;

use VitesseCms\Admin\Models\AdminMenu;
use VitesseCms\Admin\Models\AdminMenuNavBarChildren;
use Phalcon\Events\Event;

class AdminMenuListener
{
    public function AddChildren(Event $event, AdminMenu $adminMenu): void
    {
        $children = new AdminMenuNavBarChildren();
        $children->addChild('Data fields', 'admin/datafield/admindatafield/adminList');
        $adminMenu->addDropdown('DataDesign', $children);
    }
}
