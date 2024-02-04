<?php
spl_autoload_register( function ( $class ) {
    require substr( $class, strlen( $prefix ) ) . '.php';
} );
?>