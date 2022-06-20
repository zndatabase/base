<?php

use Psr\Container\ContainerInterface;
use ZnCore\Base\Libs\EntityManager\Interfaces\EntityManagerInterface;
use ZnCore\Base\Libs\EntityManager\Libs\EntityManager;
use ZnDatabase\Eloquent\Domain\Capsule\Manager;
use ZnDatabase\Eloquent\Domain\Factories\ManagerFactory;
use Illuminate\Database\Capsule\Manager as CapsuleManager;

return [
    'definitions' => [],
    'singletons' => [
        /*EntityManagerInterface::class => EntityManager::class,
        EntityManager::class => function (ContainerInterface $container) {
            return EntityManager::getInstance($container);
        },*/
        /*EntityManagerInterface::class => function (ContainerInterface $container) {
            $em = EntityManager::getInstance($container);
//            $eloquentOrm = $container->get(EloquentOrm::class);
//            $em->addOrm($eloquentOrm);
            return $em;
        },*/
        Manager::class => function () {
            return ManagerFactory::createManagerFromEnv();
        },
        CapsuleManager::class => Manager::class,
    ],
];
