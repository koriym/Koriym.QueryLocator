<?php
namespace MyVendor\MyPacakge;

use Koriym\QueryLocator\QueryProviderInterface;

class RegisterUser implements QueryProviderInterface
{
    /**
     * @inheritDoc
     */
    public function get() : string
    {
        return 'REGISTER-USER-SQL';
    }
}
