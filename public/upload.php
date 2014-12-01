<?php
    // $uploaddir = "uploads/";
    // $uploadfile = $uploaddir.basename($_FILES['file']['name']);

    // move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile);
    if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
        move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $_FILES['file']['name']);
    }