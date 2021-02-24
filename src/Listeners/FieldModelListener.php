<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Listeners;

use Phalcon\Events\Event;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\Datafield\Models\Datafield;

class FieldModelListener
{
    public function beforeSave(Event $event, AbstractCollection $item, Datafield $datafield): void
    {
        $value = $item->_($datafield->getCallingName());
        if ($value) :
            if (!is_array($value)) :
                $object = $datafield->_('model');
                /** @var AbstractCollection $datafieldItem */
                $datafieldItem = $object::findById($value);
                $item->set(
                    $datafield->getCallingName() . 'Name',
                    $datafieldItem->getNameField()
                );
            endif;
        else :
            $item->set($datafield->getCallingName() . 'Name', '');
        endif;
    }
}