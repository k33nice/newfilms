<?php

namespace Service\Films;

class Index extends \Service\Base {

    public function validate($params) {
        $rules = [
            'Search'    => [ 'max_length' => 100 ],

            'Limit'     => [ 'integer', ['min_number' => 0] ],
            'Offset'    => [ 'integer', ['min_number' => 0] ],

            'SortField' => [ 'one_of' => ['Name', 'Id', 'Year', 'Format'] ],
            'SortOrder' => [ 'one_of' => ['asc', 'desc'] ]
        ];

        return \Service\Validator::validate($params, $rules);
    }

    public function execute($params) {
        $params += [
            'Search' => '',

            'Limit'  => 25,
            'Offset' => 0,

            'SortField' => 'Name',
            'SortOrder' => 'desc',
        ];

        $query = \Engine\FilmsQuery::create();

        // if ( $params['Search'] ) {
        //     $query->filterByName($params['Search'] . '%' . Criteria::LIKE); // todo
        // }

        $totalCount = $query->count();
        $films      = $query->limit(  $params['Limit']  )
                            ->offset( $params['Offset'] )
                            ->filterByName($params['Search'] . '%')
                            ->orderByName()
                            ->find();

        $rows = [];

        foreach ($films as $film) {
            $rows[] = [
                "Id"     => $film->getId(),
                "Name"   => $film->getName(),
                "Year"   => $film->getYear(),
                "Format" => $film->getFormat(),
            ];
        }

        return [
            'Films'      => $rows,
            'TotalCount' => $totalCount,
            'status'     => 1
        ];
    }
}

?>