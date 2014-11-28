<?php

namespace Service\Films;

class Index extends \Service\Base {

    public function validate($params) {
        $rules = [
            'search'    => [ 'max_length' => 100 ],

            'Limit'     => [ 'integer', ['min_number' => 0] ],
            'Offset'    => [ 'integer', ['min_number' => 0] ],

            'SortField' => [ 'one_of' => ['Name', 'Id', 'Year', 'Format'] ],
            'SortOrder' => [ 'one_of' => ['asc', 'desc'] ]
        ];

        return \Service\Validator::validate($params, $rules);
    }

    public function execute($params) {
        $params += [
            'search' => '',

            'Limit'  => 0,
            'Offset' => 0,

            'SortField' => 'Name',
            'SortOrder' => 'desc',
        ];

        $query = \Engine\FilmsQuery::create();

        $totalCount = $query->count();
        $films =      $query->limit(  $params['Limit']  )
                            ->offset( $params['Offset'] )
                            // ->filterByName($params['search'] . '%')
                            ->orderByName()
                            ->find();

        $rows = array();

        foreach ($films as $film) {
            $row = [
                "Id"   => $film->getId(),
                "Name" => $film->getName(),
                // "Year"   => $film->getYear(),
                // "Format" => $film->getFormat(),
            ];

            array_push($rows, $row);
        }

        return [
            'Films'      => $rows,
            'TotalCount' => $totalCount,
            'status'     => 1
        ];
    }
}

?>