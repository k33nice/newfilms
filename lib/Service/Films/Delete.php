<?php

namespace Service\Films;

class Delete extends \Service\Base {

    public function validate($params) {
        $rules = [
            'Id' => [ 'required', 'positive_integer' ],
        ];

        return \Service\Validator::validate($params, $rules);
    }

    public function execute($params) {
        // $self = $this;
        try {

            $film = \Engine\FilmsQuery::create()
                ->findOneById($params['Id'])
                ->delete();

            if (!$film) {
                throw new \Service\X([
                    'type'    => 'WRONG_ID',
                    'fields'  => [ 'Id' => 'WRONG_ID' ],
                    'message' => 'Cannot find film with id = '.$params['Id']
                ]);
            }

            // $film->delete();

            return [
                'status' => 1
            ];
        } catch (\Engine\X\AcessDenied $e) {
            throw new \Service\X([
                'type'    => 'ACCESS_DENIED',
                'fields'  => [ 'Id' => 'ACCESS_DENIED' ],
                'message' => 'Film can\'t do this'
            ]);
        }
    }
}

?>