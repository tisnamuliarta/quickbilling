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
                return 'Sales Quotations';
            case 'SO':
                return 'Sales Order';
            case 'SD':
                return 'Sales Delivery';
            case 'BL':
            case 'IN':
                return 'Invoice';
            case 'RC':
                return 'Incoming Payment';
            case 'CN':
                return 'Credit Note';
            case 'SR':
                return 'Sales Return';
            case 'PQ':
                return 'Purchase Quotations';
            case 'PO':
                return 'Purchase Order';
            case 'GR':
                return 'Goods Receipt Purchase Order';
            case 'PY':
                return 'Outgoing Payment';
            case 'DN':
                return 'Debit Note';
            case 'GN':
                return 'Goods Return';
            case 'GI':
                return 'Goods Issue';
            case 'GC':
                return 'Goods Receipt';
            case 'CS':
                return 'Cash Sale';
            case 'CP':
                return 'Cash Purchase';
            case 'JN':
                return 'Journal Entry';
            case 'CE':
                return 'Bank Transfer';
        }
    }
}
