<?php

namespace Service\Films;

class Create extends \Service\Base {

    public function validate($params) {
        $rules = [
            'Name'   => [ 'not_empty' ],
            'Year'   => [ 'not_empty'/*, 'positive_integer', number_between => [1900, 2020], 'length_equal' => 4*/],
            'Format' => [ 'not_empty'/*'one_of' => ['VHS', 'DVD', 'BluRay'] */],
        ];

        return \Service\Validator::validate($params, $rules);
    }

    public function execute($params) {
        try {
            // $params += ['SubsidiaryID' => ''];

            $query = \Engine\FilmsQuery::create()->save();

            if ($query->count() == 0) {
                throw new \Service\X([
                    'type'    => 'NOT_FOUND',
                    'fields'  => [ 'Email' => 'NOT_FOUND' ],
                    'message' => 'Cannot find client with email = '.$params['Email']
                ]);
            }

            // $employee->save();
            // $user->save();

            return [
                'Id' => $query,
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