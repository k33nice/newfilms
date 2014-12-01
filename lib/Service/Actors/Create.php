<?php

namespace Service\Actors;

class Create extends \Service\Base
{
    public function validate($params)
    {
        $rules = [
            'ActorName'    => [ 'required', 'not_empty' ],
        ];

        return \Service\Validator::validate($params, $rules);
    }

    public function execute($params)
    {

        $actors = $params['ActorName'];
        $array = \Engine\FilmsQuery::create()->find();
        foreach ($array as $film) {
            $idArray[] = [
                "Id" => $film->getId(),
            ];
        }
        $id = array_pop($idArray);
        $id = $id['Id'];
        $actorsArray = explode(', ', $actors);

        foreach ($actorsArray as $actor) {
            $actor = explode(' ', $actor, 2);
            $actorName = $actor[0];
            if (!empty($actor[1])) {
                $actorSurname = $actor[1];
            } else {
                $actorSurname = ' ';
            }
            $params = [
                'Name'    => $actorName,
                'Surname' => $actorSurname,
                'FilmId'  => $id,
            ];

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
}