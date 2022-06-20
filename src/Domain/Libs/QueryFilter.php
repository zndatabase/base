<?php

namespace ZnDatabase\Base\Domain\Libs;

use Illuminate\Support\Collection;
use ZnBundle\Eav\Domain\Repositories\Eloquent\FieldRepository;
use ZnCore\Domain\Helpers\Repository\RelationHelper;
use ZnCore\Domain\Interfaces\ReadAllInterface;
use ZnCore\Base\Libs\Query\Entities\Query;
use ZnCore\Domain\Relations\libs\RelationLoader;

class QueryFilter
{

    private $repository;
    private $query;
    private $with;

    public function __construct(ReadAllInterface $repository, Query $query)
    {
        $this->repository = $repository;
        $this->query = $query;
    }

    public function loadRelations(Collection $collection)
    {
        if (method_exists($this->repository, 'relations2')) {
            $relationLoader = new RelationLoader;
            $relationLoader->setRelations($this->repository->relations2());
            $relationLoader->setRepository($this->repository);
            $relationLoader->loadRelations($collection, $this->query);
            return $collection;
        }

        if (empty($this->with)) {
            return $collection;
        }
        /*if($this->repository instanceof FieldRepository) {
            prr($with);
        }*/
//        $collection = RelationHelper::load($this->repository, $this->query, $collection);
        return $collection;
    }
}
