<?php

namespace Service\Films;

class Count extends \Service\Base {

    public function validate($params) {
        $rules = [
            // 'Search'    => [ 'max_length' => 100 ],

            // 'Limit'     => [ 'integer', ['min_number' => 0] ],
            // 'Offset'    => [ 'integer', ['min_number' => 0] ],

            // 'SortField' => [ 'one_of' => ['Name', 'Id', 'Year', 'Format'] ],
            // 'SortOrder' => [ 'one_of' => ['asc', 'desc'] ]
        ];

        return \Service\Validator::validate($params, $rules);
    }

    public function execute($params) {

        $query = \Engine\FilmsQuery::create();

        $totalCount = $query->count();

        return [
            'TotalCount' => $totalCount,
            'status'     => 1
        ];
    }
}