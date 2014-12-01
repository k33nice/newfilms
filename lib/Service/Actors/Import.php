<?php

namespace Service\Actors;

class Import extends \Service\Base {

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

            $actor = new \Engine\Actors();
            $actor->setName($params['Name']);
            $actor->setSurname($params['Surname']);
            $actor->setFilmId($params['FilmId']);
            $actor->save();

            return [
                'Id' => $actor->getId(),
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