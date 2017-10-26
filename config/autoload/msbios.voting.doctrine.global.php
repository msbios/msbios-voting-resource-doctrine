<?php

/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Voting\Doctrine;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\IndexController::class =>
                InvokableFactory::class,
        ],
        'aliases' => [
            \MSBios\Application\Controller\IndexController::class =>
                Controller\IndexController::class
        ]
    ],
    'view_manager' => [
        'template_map' => [
            // Template map
        ],
        'template_path_stack' => [
            __DIR__ . '/../../view',
        ],
    ],
];
