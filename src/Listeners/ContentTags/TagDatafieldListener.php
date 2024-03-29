<?php

declare(strict_types=1);

namespace VitesseCms\Datafield\Listeners\ContentTags;

use MongoDB\BSON\UTCDateTime;
use Phalcon\Events\Manager;
use VitesseCms\Content\DTO\TagListenerDTO;
use VitesseCms\Content\Helpers\EventVehicleHelper;
use VitesseCms\Content\Listeners\ContentTags\AbstractTagListener;
use VitesseCms\Datafield\Repositories\DatafieldRepository;
use VitesseCms\Media\Enums\MediaEnum;
use VitesseCms\Media\Services\AssetsService;
use VitesseCms\Mustache\DTO\RenderPartialDTO;
use VitesseCms\Mustache\Enum\ViewEnum;

class TagDatafieldListener extends AbstractTagListener
{
    public function __construct(
        private readonly DatafieldRepository $datafieldRepository,
        private readonly Manager $eventsManager,
        private readonly AssetsService $assetsService
    ) {
        $this->name = 'DATAFIELD';
    }

    protected function parse(EventVehicleHelper $contentVehicle, TagListenerDTO $tagListenerDTO): void
    {
        $tagOptions = explode(';', $tagListenerDTO->getTagString());
        $field = $this->datafieldRepository->getById($tagOptions[1]);
        $replace = '';

        if ($field !== null) {
            $types = array_reverse(explode('\\', $field->getType()));

            if (isset($tagOptions[2])):
                $replace = $this->eventsManager->fire(
                    ViewEnum::RENDER_PARTIAL_EVENT,
                    new RenderPartialDTO(strtolower($types[0]) . '/' . $tagOptions[2]),
                    $contentVehicle->getView()->getCurrentItem()
                );
                $this->assetsService->setEventLoader(MediaEnum::ASSETS_LISTENER . ':' . $tagOptions[2]);
            else :
                $replace = $contentVehicle->getView()->getCurrentItem()->_($field->getCallingName());
            endif;

            if ($replace instanceof UTCDateTime) {
                $dateFormat = 'Y-m-d';
                if ($field->has('date_format')) {
                    $dateFormat = $field->getString('date_format');
                }
                $replace = $replace->toDateTime()->format($dateFormat);
            }
        }

        $contentVehicle->setContent(
            str_replace(
                '{' . $this->name . $tagListenerDTO->getTagString() . '}',
                $replace,
                $contentVehicle->getContent()
            )
        );
    }
}