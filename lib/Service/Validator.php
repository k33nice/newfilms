<?php

namespace Service;

class Validator {

    static public function validate( $data, $livr ) {

        $validator = new \Validator\LIVR( $livr );

        $validated = $validator->validate($data);
        $errors    = $validator->getErrors();

        if ( $errors ) {
            throw new X([ 'type' => 'FORMAT_ERROR', 'fields' => $errors ]);
        }

        return $validated;
    }
}

?>