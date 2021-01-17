<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Listeners;

use VitesseCms\Admin\Models\AdminMenu;
use VitesseCms\Admin\Models\AdminMenuNavBarChildren;
use VitesseCms\Core\Models\Datagroup;
use Phalcon\Events\Event;

class AdminMenuListener
{
    public function AddChildren(Event $event, AdminMenu $adminMenu): void
    {
        if ($adminMenu->getUser()->getPermissionRole() === 'superadmin') :
            $children = new AdminMenuNavBarChildren();
            $children->addChild('Data fields','admin/field/admindatafield/adminList');
            var_dump('fff');
            die();
            /*$formOptionsGroups = $adminMenu->getGroups()->getByKey('formOptions');
            if ($formOptionsGroups !== null) :
                $children->addLine();
                $datagroups = $formOptionsGroups->getDatagroups();
                while ($datagroups->valid()) :
                    $formOptionGroup = $datagroups->current();
                    $children->addChild(
                        $formOptionGroup->getNameField(),
                        'admin/content/adminitem/adminList/?filter[datagroup]='.$formOptionGroup->getId()
                    );
                    $datagroups->next();
                endwhile;
            endif;*/

            $adminMenu->addDropdown('DataDesign',$children);
        endif;
    }
}
