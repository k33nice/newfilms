<?php

namespace Service\Films;

class Create extends \Service\Base {

    public function validate($params) {
        $rules = [
            'Name'   => [ 'required' ],
            'Year'   => [ 'not_empty', 'positive_integer', number_between => [1900, 2020], 'length_equal' => 4],
            'Format' => [ 'one_of' => ['VHS', 'DVD', 'BluRay'] ],
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

            if ($query->filterByRole('')->count() == 0 ) {
                throw new \Service\X([
                    'type'    => 'NOT_SUITABLE',
                    'fields'  => [ 'Email' => 'NOT_SUITABLE' ],
                    'message' => 'Client with email = '.$params['Email'].' already assigned with some dealer'
                ]);
            }

            // $dealer = $this->getDealer();

            $subsidiary = \Engine\ActorsQuery::create()
                ->filterBySubsidiaryID($params['SubsidiaryID'])
                ->filterByDealerID($dealer->getDealerID())
                ->findOne();

            if ($params['SubsidiaryID'] && !$subsidiary) {
                throw new \Service\X([
                    'type'    => 'WRONG_ID',
                    'fields'  => [ 'SubsidiaryID' => 'WRONG_ID' ],
                    'message' => 'Cannot find subsidiary with id = '.$params['SubsidiaryID']
                ]);
            }

            $films = $query->findOne();
            $user->setRole('employee');
            $user->setDealer($dealer);
            if ( $subsidiary ) $user->setSubsidiary($subsidiary);

            $employee = new \Engine\Films();

            $employee->fromArray($params);
            $employee->setRealUser($user);
            $employee->setDealer($dealer);
            $employee->setSubsidiary($subsidiary);

            // $employee->save();
            // $user->save();

            return [
                'Id' => $films->getId(),
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