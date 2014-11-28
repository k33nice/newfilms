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

        $this->run(function() use ($self, $data) {
            return $self->action("Service\Films\Create")->run($data);
        });
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

            $title = 'Title';
            $year = 'Release Year';
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

                        $data = [
                            'Name'   => $resultName,
                            'Year'   => $resultYear,
                            'Format' => $resultFormat,
                        ];

                        $self->action("Service\Films\Import")->run($data);

                        break;

                }
            }
        }
    }
}

?>