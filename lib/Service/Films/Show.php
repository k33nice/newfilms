<?php

namespace Service\Films;

class Show extends \Service\Base {

    public function validate($params)
    {
        $rules = [
            'Id' => [ 'required', 'positive_integer' ],
        ];

        return \Service\Validator::validate($params, $rules);
    }

    public function execute($params)
    {
        try {
            $film = \Engine\FilmsQuery::create()
                ->findOneById($params['Id']);

            if (!$film) {
                throw new \Service\X([
                    'type'    => 'WRONG_ID',
                    'fields'  => [ 'Id' => 'WRONG_ID' ],
                    'message' => 'Cannot find film with id = '.$params['Id']
                ]);
            }

            $row = [
                "Id"     => $film->getId(),
                "Name"   => $film->getName(),
                "Year"   => $film->getYear(),
                "Format" => $film->getFormat(),
            ];

            return [
                'Film'   => $row,
                'status' => 1
            ];
        } catch (\Engine\X\AcessDenied $e) {
            throw new \Service\X([
                'type'    => 'ACCESS_DENIED',
                'fields'  => [ 'FilmId' => 'ACCESS_DENIED' ],
                'message' => 'Film can\'t read this'
            ]);
        }
    }
}

?>