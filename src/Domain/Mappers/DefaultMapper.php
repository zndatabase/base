<?php

namespace ZnDatabase\Base\Domain\Mappers;

use ZnCore\Base\Helpers\ClassHelper;
use ZnCore\Contract\Mapper\Interfaces\MapperInterface;
use ZnCore\Base\Libs\Entity\Helpers\EntityHelper;

/**
 * Class DefaultMapper
 * @deprecated
 */
class DefaultMapper implements MapperInterface
{

    private $entityClass;

    public function __construct(string $entityClass)
    {
        $this->entityClass = $entityClass;
    }

    public function encode($entity): array
    {
        $data = EntityHelper::toArrayForTablize($entity);
        return $data;
    }

    public function decode(array $row)
    {
        $entity = ClassHelper::createInstance($this->entityClass);
        EntityHelper::setAttributes($entity, $row);
        return $entity;
    }
}
