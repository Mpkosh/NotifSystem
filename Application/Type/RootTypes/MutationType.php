<?php

namespace GraphQL\Application\Type;

use GraphQL\Application\Database\DataSource;
use GraphQL\Application\Types;
use GraphQL\Type\Definition\ObjectType;

class MutationType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'fields' => [
                    'changeTeacherStat' => [
                        'type' => Types::proposal(),
                        'description' => 'Changes teacher`s status of a proposal',
                        'args' => [
                            'id' => Types::nonNull(Types::id()),
                            'status' => Types::string()
                        ],
                        'resolve' => function ($root, $args) {
                            //update status
                            DataSource::updateStatus("status_teacher", $args['status'], $args['id']);
                            //return new data
                            $proposal = DataSource::find('Proposal', $args['id']);
                            return $proposal;
                        }
                    ],
                    
                    'changeAdminStat' => [
                        'type' => Types::proposal(),
                        'description' => 'Changes admin`s status of a proposal',
                        'args' => [
                            'id' => Types::nonNull(Types::id()),
                            'status' => Types::string()
                        ],
                        'resolve' => function ($root, $args) {
                            DataSource::updateStatus("status_admin", $args['status'], $args['id']);
                            $proposal = DataSource::find('Proposal', $args['id']);
                            return $proposal;
                        }
                    ],

                    'changeParentStat' => [
                        'type' => Types::proposal(),
                        'description' => 'Changes parent`s status of a proposal',
                        'args' => [
                            'id' => Types::nonNull(Types::id()),
                            'status' => Types::string()
                        ],
                        'resolve' => function ($root, $args) {
                            DataSource::updateStatus("status_parent", $args['status'], $args['id']);
                            $proposal = DataSource::find('Proposal', $args['id']);
                            return $proposal;
                        }
                    ],
                ],
        ];
        parent::__construct($config);
    }

    
}
