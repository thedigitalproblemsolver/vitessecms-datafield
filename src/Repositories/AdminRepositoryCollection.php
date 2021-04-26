<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Repositories;

use VitesseCms\Block\Interfaces\RepositoryInterface;

class AdminRepositoryCollection implements RepositoryInterface
{
    /**
     * @var DatafieldRepository
     */
    public $datafield;

    public function __construct(
        DatafieldRepository $datafieldRepository
    )
    {
        $this->datafield = $datafieldRepository;
    }
}
