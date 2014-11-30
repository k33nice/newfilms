<?php

namespace Service\Films;

class CreateActors extends \Service\Base {

    public function validate($params) {
        $rules = [
            'Name'   => [ 'required', 'not_empty' ],
            'Surname' => ['required'],
            'FilmId' => ['required', 'not_empty']
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