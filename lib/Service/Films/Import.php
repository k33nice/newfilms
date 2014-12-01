<?php

namespace Service\Films;

class Import extends \Service\Base {

    public function validate($params) {
        $rules = [
            'Content' => [ 'required', 'not_empty' ],
        ];

        return \Service\Validator::validate($params, $rules);
    }

    public function execute($params) {
        $content = $params['Content'];

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

                    $film = [
                        'Name'   => $resultName,
                        'Year'   => $resultYear,
                        'Format' => $resultFormat,
                    ];

                        try {
                            $fil = new \Engine\Films();
                            $fil->setName($film['Name']);
                            $fil->setYear($film['Year']);
                            $fil->setFormat($film['Format']);
                            $fil->save();
                        } catch (\Engine\X\AcessDenied $e) {
                            throw new \Service\X([
                                'type'    => 'ACCESS_DENIED',
                                'fields'  => [ 'UserID' => 'ACCESS_DENIED' ],
                                'message' => 'Employee can\'t read this'
                            ]);
                        }
                    break;

            }
        } //End Foreach
    }
}