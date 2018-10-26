<?php
namespace Woocommerce\Moip\Exceptions;

class Parse_Exception
{
    private $exception;

    public function __construct( $exception )
    {
        $this->exception = $exception;
    }

    public function get_errors( $with_path = false )
    {
        $erros = '';
        $i     = 0;

        foreach ( $this->exception->getErrors() as $error ) {
            $i++;

            $path = $error->getPath();
            $desc = $error->getDescription();

            if ( $with_path ) {
                $erros .= "{$path}: {$desc}\n";
            } else {
                $erros .= "{$i}: {$desc}\n";
            }
        }

        return $erros;
    }
}