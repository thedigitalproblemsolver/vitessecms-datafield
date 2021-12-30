<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Listeners\ContentTags;

use VitesseCms\Content\Helpers\EventVehicleHelper;
use VitesseCms\Content\Listeners\ContentTags\AbstractTagListener;
use VitesseCms\Content\Models\Item;
use VitesseCms\Datafield\Models\Datafield;
use VitesseCms\Datafield\Repositories\DatafieldRepository;

class TagDatafieldListener extends AbstractTagListener
{
    /**
     * @var DatafieldRepository
     */
    private $datafieldRepository;

    public function __construct(DatafieldRepository $datafieldRepository)
    {
        $this->name = 'DATAFIELD';
        $this->datafieldRepository = $datafieldRepository;
    }

    protected function parse(EventVehicleHelper $contentVehicle, string $tagString): void
    {
        $tagOptions = explode(';', $tagString);
        $field = $this->datafieldRepository->getById($tagOptions[1]);
        $replace = $contentVehicle->getView()->getCurrentItem()->_($field->getCallingName());

        $contentVehicle->setContent(
            str_replace(
                '{' . $this->name . $tagString . '}',
                $replace,
                $contentVehicle->_('content')
            )
        );
    }
}