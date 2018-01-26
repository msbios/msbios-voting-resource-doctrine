<?php

/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Voting\Resource\Doctrine;

use MSBios\Doctrine\Initializer\ObjectManagerInitializer;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'doctrine' => [
        'driver' => [
            // defines an annotation driver with two paths, and names it `my_annotation_driver`
            Module::class => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Entity'
                ],
            ],

            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => [
                'drivers' => [
                    Entity::class => Module::class,
                ],
            ],
            'entity_resolver' => [
                'orm_default' => [
                    'resolvers' => [
                        // \MSBios\Guard\Resource\Doctrine\UserInterface::class =>
                        //     Entity\User::class
                    ],
                ],
            ],

        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\PollController::class =>
                InvokableFactory::class,
            Controller\RelationController::class =>
                InvokableFactory::class
        ],
        'initializers' => [
            new ObjectManagerInitializer
        ]
    ],

    'form_elements' => [
        'factories' => [
            Form\OptionForm::class =>
                InvokableFactory::class,
            Form\Element\PollSelect::class,
                InvokableFactory::class
        ],
        'aliases' => [
            \MSBios\Voting\Resource\Form\OptionForm::class =>
                Form\OptionForm::class
        ]
    ],

    'console' => [
        'router' => [
            'routes' => [
                'polls' => [
                    'options' => [
                        // 'route' => 'show [all|disabled|deleted]:mode users [--verbose|-v]',
                        'route' => 'polls',
                        'defaults' => [
                            'controller' => Controller\PollController::class,
                            'action' => 'index'
                        ]
                    ]
                ],
                'poll-votes' => [
                    'options' => [
                        'route' => 'poll votes <pollid>',
                        'defaults' => [
                            'controller' => Controller\PollController::class,
                            'action' => 'votes'
                        ]
                    ]
                ],
                'poll-relations' => [
                    'options' => [
                        // 'route' => 'show [all|disabled|deleted]:mode users [--verbose|-v]',
                        'route' => 'poll-relations',
                        'defaults' => [
                            'controller' => Controller\RelationController::class,
                            'action' => 'index'
                        ]
                    ]
                ],

                'poll-relation-votes' => [
                    'options' => [
                        // 'route' => 'show [all|disabled|deleted]:mode users [--verbose|-v]',
                        'route' => 'poll-relation votes <pollid>',
                        'defaults' => [
                             'controller' => Controller\RelationController::class,
                            'action' => 'votes'
                        ]
                    ]
                ],
            ]
        ]
    ],
];
