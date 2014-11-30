<?php

namespace Service\Films;

class ImportActors extends \Service\Base {

    public function validate($params) {
        $rules = [
            'Name'    => [ 'required', 'not_empty' ],
            'Surname' => [ 'required', 'not_empty' ],
            'FilmId'  => [ 'required', 'not_empty' ],
        ];

        return \Service\Validator::validate($params, $rules);
    }

    public function execute($params) {
        try {

            $film = new \Engine\Actors();
            $film->setName($params['Name']);
            $film->setSurname($params['Surname']);
            $film->setFilmId($params['FilmId']);
            $film->save();

            return [
                'Id' => $film->getId(),
                'status' => 1
            ];
        } catch (\Engine\X\AcessDenied $e) {
            throw new \Service\X([
                'type'    => 'ACCESS_DENIED',
                'fields'  => [ 'UserID' => 'ACCESS_DENIED' ],
                'message' => 'Employee can\'t read this'
            ]);
        }
    }
}

?>