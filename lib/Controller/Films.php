<?php

namespace Controller;

class Films extends Base {

    public function index()
    {
        $self = $this;
        $data = $self->request()->params();

        $this->run(function() use ($self, $data) {
            return $self->action("Service\Films\Index")->run($data);
        });
    }

    public function show($Id)
    {
        $self = $this;
        $data = [ 'Id' => $Id ];

        $this->run(function() use ($self, $data) {
            return $self->action("Service\Films\Show")->run($data);
        });
    }

    public function delete($Id)
    {
        $self = $this;
        $data = [ 'Id' => $Id ];

        $this->run(function() use ($self, $data) {
            return $self->action("Service\Films\Delete")->run($data);
        });
    }

    public function create()
    {
        $self = $this;
        $data = $self->request()->params();
        $self->action("Service\Films\Create")->run($data);
        $actors = $data['ActorName'];

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
            $data = [
                'Name'    => $actorName,
                'Surname' => $actorSurname,
                'FilmId'  => $id,
            ];
            $self->action("Service\Actors\Create")->run($data);
        }
    }

    public function update($Id)
    {
        $self = $this;

        $data = $self->request()->params();
        $data['Id'] = $Id;

        $this->run(function() use ($self, $data) {
            return $self->action("Service\Films\Update")->run($data);
        });
    }

    public function import()
    {
        $self = $this;

        $handle = opendir('uploads/');
        while (false !== ($entry = readdir($handle))) {
            $content = file('uploads/' . $entry);

            $title  = 'Title';
            $year   = 'Release Year';
            $format = 'Format';

            $act = implode($content);
            $blocks = explode('Title:', $act);

            foreach ($content as $line) {

                $result = explode(': ', $line, 2);

                switch ($result[0]) {
                    case $title:
                        $resultName = $result[1];
                        continue;
                    case $year:
                        $resultYear = $result[1];
                        continue;
                    case $format:
                        $resultFormat = $result[1];

                        $resultName   = trim($resultName);
                        $resultYear   = trim($resultYear);
                        $resultFormat = trim($resultFormat);
                        $aName = null;


                        $data = [
                            'Name'   => $resultName,
                            'Year'   => $resultYear,
                            'Format' => $resultFormat,
                        ];

                        $self->action("Service\Films\Import")->run($data);

                        break;

                }
            }

            $films = \Engine\FilmsQuery::create()->find();
            $nameArr = array();

            foreach ($films as $film) {
                $row = [
                    "Id"   => $film->getId(),
                    "Name" => $film->getName(),
                ];
                array_push($nameArr, $row);
            }

            foreach ($blocks as $block) {
                //Заменяем в каждом блоке с информацией о фильме, которая имеет вид
                // ('Blazing SaddlesRelease Year: 1974Format: VHSStars: Mel Brooks, Clevon Little,
                // Harvey Korman, Gene Wilder, Slim Pickens, Madeline Kahn')
                // 'Release Year:', 'Format:', 'Stars:' на разделитель '/*/' - чтобы была извлечь имена актеров
                // '/*/' такой разделитель врятли встретится в информации о фильме.
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

                                $data = [
                                    'Name'    => $resultName,
                                    'Surname' => $resultSurname,
                                    'FilmId'  => $resultFilmId,
                                ];

                                $self->action("Service\Actors\Import")->run($data);
                            }

                        }
                    }
                }
            } //End Foreach blocks
        } // End while

    }
}

?>