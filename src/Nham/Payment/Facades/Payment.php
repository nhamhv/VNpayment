<?php
/**
 * Created by PhpStorm.
 * User: nham
 * Email: hoangnham01@gmail.com
 * Date: 02/07/2015
 * Time: 4:23 CH
 */

namespace Nham\Payment\Facades;

use Illuminate\Support\Facades\Facade;

class Payment extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'payment'; }

}