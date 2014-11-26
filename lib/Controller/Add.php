<?php

namespace Controller;

class Add extends Base {

    public function index() {
        $self = $this;
        $data = $self->request()->params();

        $this->run(function() use ($self, $data) {
            return $self->action("Service\Add\Index")->run($data);
        });
    }

    public function show($movieId) {
        $self = $this;
        $data = [ 'movieId' => $movieId ];

        $this->run(function() use ($self, $data) {
            return $self->action("Service\Add\Show")->run($data);
        });
    }

    public function delete($movieId) {
        $self = $this;
        $data = [ 'movieId' => $movieId ];

        $this->run(function() use ($self, $data) {
            return $self->action("Service\Add\Delete")->run($data);
        });
    }

    public function create() {
        $self = $this;
        $data = $self->request()->params();

        $this->run(function() use ($self, $data) {
            return $self->action("Service\Add\Create")->run($data);
        });
    }

    public function update($movieId) {
        $self = $this;

        $data = $self->request()->params();
        $data['movieId'] = $movieId;

        $this->run(function() use ($self, $data) {
            return $self->action("Service\Add\Update")->run($data);
        });
    }
}

?>