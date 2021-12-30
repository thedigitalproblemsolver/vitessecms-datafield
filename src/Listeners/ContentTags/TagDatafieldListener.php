<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Listeners\ContentTags;

use Phalcon\Events\Manager;
use VitesseCms\Content\Helpers\EventVehicleHelper;
use VitesseCms\Content\Listeners\ContentTags\AbstractTagListener;
use VitesseCms\Content\Models\Item;
use VitesseCms\Datafield\Models\Datafield;
use VitesseCms\Datafield\Repositories\DatafieldRepository;
use VitesseCms\Mustache\DTO\RenderTemplateDTO;
use VitesseCms\Mustache\Enum\ViewEnum;

class TagDatafieldListener extends AbstractTagListener
{
    /**
     * @var DatafieldRepository
     */
    private $datafieldRepository;

    /**
     * @var Manager
     */
    private $eventsManager;

    public function __construct(DatafieldRepository $datafieldRepository, Manager $eventsManager)
    {
        $this->name = 'DATAFIELD';
        $this->datafieldRepository = $datafieldRepository;
        $this->eventsManager = $eventsManager;
    }

    protected function parse(EventVehicleHelper $contentVehicle, string $tagString): void
    {
        $tagOptions = explode(';', $tagString);
        $field = $this->datafieldRepository->getById($tagOptions[1]);
        $types = array_reverse(explode('\\',$field->getType()));
        if(isset($tagOptions[2])):
            $replace = $this->eventsManager->fire(
                ViewEnum::RENDER_PARTIAL_EVENT,
                strtolower($types[0]).'/'.$tagOptions[2],
                $contentVehicle->getView()->getCurrentItem()
            );
        else :
            $replace = $contentVehicle->getView()->getCurrentItem()->_($field->getCallingName());
        endif;

        $contentVehicle->setContent(
            str_replace(
                '{' . $this->name . $tagString . '}',
                $replace,
                $contentVehicle->_('content')
            )
        );
    }
}