<?php

namespace Service;

abstract class Base {
    private $log;

    public function __construct($attrs) {
        if ( isset($attrs['log']) ) {
            $this->log = $attrs['log'];
        }
    }

    protected function log() {
        return $this->log;
    }

    public function run( $params = [] ) {
        try {

            $validated = $this->validate( $params );
            $result = $this->execute( $validated );

            return $result;
        }
        catch ( Exception $e ) {
            throw $e;
        }
    }

}

?>