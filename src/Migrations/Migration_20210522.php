<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Migrations;

use VitesseCms\Cli\Services\TerminalServiceInterface;
use VitesseCms\Configuration\Services\ConfigServiceInterface;
use VitesseCms\Datafield\Repositories\AdminRepositoryCollection;
use VitesseCms\Datafield\Repositories\DatafieldRepository;
use VitesseCms\Install\Interfaces\MigrationInterface;

class Migration_20210522 implements MigrationInterface
{
    /**
     * @var AdminRepositoryCollection
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new AdminRepositoryCollection(
            new DatafieldRepository()
        );
    }

    public function up(ConfigServiceInterface $configService, TerminalServiceInterface $terminalService): bool
    {
        $result = true;
        if (!self::parseDatafieldType($terminalService)) :
            $result = false;
        endif;

        return $result;
    }

    private function parseDatafieldType(TerminalServiceInterface $terminalService): bool
    {
        $result = true;
        $datafields = $this->repository->datafield->findAll(null, false);
        $search = [
            'VitesseCms\Datafield\Models\FieldAddtocart',
            'VitesseCms\Datafield\Models\FieldAmazonBrowseNode',
            'VitesseCms\Datafield\Models\FieldAmazonCatalogGender',
            'VitesseCms\Datafield\Models\FieldAmazonCatalogType',
            'VitesseCms\Datafield\Models\FieldCheckbox',
            'VitesseCms\Datafield\Models\FieldDatagroup',
            'VitesseCms\Datafield\Models\FieldEtsyCategory',
            'VitesseCms\Datafield\Models\FieldEtsyListing',
            'VitesseCms\Datafield\Models\FieldFacebookCatalogGender',
        ];
        $replace = [
            'VitesseCms\Shop\Fields\ShopAddToCart',
            'VitesseCms\Shop\Fields\AmazonBrowseNode',
            'VitesseCms\Shop\Fields\AmazonCatalogGender',
            'VitesseCms\Shop\Fields\AmazonCatalogType',
            'VitesseCms\Content\Fields\Toggle',
            'VitesseCms\Datagroup\Fields\Datagroup',
            'VitesseCms\Etsy\Fields\EtsyCategory',
            'VitesseCms\Etsy\Fields\EtsyListing',
            'VitesseCms\Shop\Fields\FacebookCatalogGender',
        ];
        while ($datafields->valid()):
            $datafield = $datafields->current();
            $type = str_replace($search,$replace,$datafield->getType());
            if(substr_count($type,'VitesseCms\Datafield\Models') === 1 ):
                $result = false;
                $terminalService->printError('Wrong type "'.str_replace($search,$replace,$datafield->getType()).'" for datafield "'.$datafield->getNameField().'"');
            else :
                $datafield->setType($type)->save();
            endif;
            $datafields->next();
        endwhile;

        $terminalService->printMessage('datafields type repaired');
        return $result;
    }
}