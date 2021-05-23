<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Listeners;

use Phalcon\Events\Event;
use VitesseCms\Admin\AbstractAdminController;
use VitesseCms\Admin\Forms\AdminlistFormInterface;
use VitesseCms\Core\Utils\SystemUtil;
use VitesseCms\Datafield\Helpers\DatafieldUtil;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class AdmindatafieldControllerListener
{
    public function adminListFilter(Event $event, AbstractAdminController $controller, AdminlistFormInterface $form): string
    {
        $form->addNameField($form);

        $types = DatafieldUtil::getTypes(SystemUtil::getModules($controller->configuration),'/Fields/');
        $types = array_combine($types, $types);
        $form->addDropdown(
            '%ADMIN_DATAFIELD%',
            'filter[type]',
            (new Attributes())->setOptions(ElementHelper::arrayToSelectOptions($types))
        );

        $form->addPublishedField($form);

        return $form->renderForm(
            $controller->getLink() . '/' . $controller->router->getActionName(),
            'adminFilter'
        );
    }
}
