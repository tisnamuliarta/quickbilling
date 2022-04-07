<?php

namespace App\Traits;

trait DocumentHelper
{
    /**
     * @param $type
     * @return string|void
     */
    public function mapping($type)
    {
        switch ($type) {
            case 'SQ':
                return 'Sales quotations';
            case 'SO':
                return 'sales order';
            case 'SD':
                return 'sales delivery';
            case 'SI':
                return 'A/R invoice';
            case 'SP':
                return 'incoming payment';
            case 'SR':
                return 'sales return';
            case 'PQ':
                return 'purchase quotations';
            case 'PO':
                return 'purchase order';
            case 'PR':
                return 'goods receipt';
            case 'PI':
                return 'A/P invoice';
            case 'PP':
                return 'outgoing payment';
            case 'PN':
                return 'goods return';
        }
    }
}
