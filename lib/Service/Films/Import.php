<?php

namespace Service\Films;

class Import extends \Service\Base {

    public function validate($params) {
        $rules = [
            'Name'   => [ 'required', 'not_empty' ],
            'Year'   => [ 'required', 'not_empty'/*, 'positive_integer', number_between => [1900, 2020], 'length_equal' => 4*/],
            'Format' => [ 'required', 'not_empty'/*'one_of' => ['VHS', 'DVD', 'BluRay'] */],
        ];

        return \Service\Validator::validate($params, $rules);
    }

    public function execute($params) {
        try {

            $film = new \Engine\Films();
            $film->setName($params['Name']);
            $film->setYear($params['Year']);
            $film->setFormat($params['Format']);
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