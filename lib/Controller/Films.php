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
        $self->action("Service\Actors\Create")->run($data);
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
            $data['Content'] = file('uploads/' . $entry);

            $self->action("Service\Films\Import")->run($data);
            $self->action("Service\Actors\Import")->run($data);
        } // End while
    }
}