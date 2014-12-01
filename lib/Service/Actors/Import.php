<?php

namespace Service\Actors;

class Import extends \Service\Base {

    public function validate($params) {
        $rules = [
            'Content' => [ 'required', 'not_empty' ],
        ];

        return \Service\Validator::validate($params, $rules);
    }

    public function execute($params) {
        $content = $params['Content'];
        $act = implode($content);
        $blocks = explode('Title:', $act);

        $films = \Engine\FilmsQuery::create()->find();

        foreach ($films as $film) {
            $nameArr[] = [
                "Id"   => $film->getId(),
                "Name" => $film->getName(),
            ];
        }
        foreach ($blocks as $block) {
            $replace = array('Release Year:', 'Format:', 'Stars:');

            $block = str_replace($replace, "/*/", $block);
            $result = explode('/*/ ', $block);
            if (!empty($result[3])) {
                $resultStars = $result[3];
                for ($i = 0; $i < count($nameArr); $i++) {
                    $name = $nameArr[$i];
                    $name1 = $name['Name'];
                    $name1 = (string)$name1;
                    $id1 = $name['Id'];

                    if (strpos($block, $name1) === 1) {
                        $resultFilmId = $id1;
                        $arrStars = explode(', ', $resultStars);

                        foreach ($arrStars as $star) {

                            $star = explode(' ', $star, 2);
                            $resultName = $star[0];
                            $resultSurname = $star[1];

                            $resultName = trim($resultName);
                            $resultSurname = trim($resultSurname);
                            $resultFilmId = trim($resultFilmId);

                            $actor = [
                                'Name'    => $resultName,
                                'Surname' => $resultSurname,
                                'FilmId'  => $resultFilmId,
                            ];
                            try {

                                $ac = new \Engine\Actors();
                                $ac->setName($actor['Name']);
                                $ac->setSurname($actor['Surname']);
                                $ac->setFilmId($actor['FilmId']);
                                $ac->save();

                            } catch (\Engine\X\AcessDenied $e) {
                                throw new \Service\X([
                                    'type'    => 'ACCESS_DENIED',
                                    'fields'  => [ 'UserID' => 'ACCESS_DENIED' ],
                                    'message' => 'Employee can\'t read this'
                                ]);
                            }
                        } //End Foreach
                    }
                }
            }
        } //End Foreach blocks
    }
}