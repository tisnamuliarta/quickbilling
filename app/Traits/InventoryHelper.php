<?php

namespace App\Traits;

use App\Models\Inventory\Item;
use App\Models\Inventory\ItemWarehouse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

trait InventoryHelper
{
    /**
     * @param $line_item
     * @param $document
     *
     * @return void
     * @throws \Exception
     */
    public function processOnHandQty($line_item, $document)
    {
        if ($line_item->item->item_group == 'Inventory') {
            $item = $line_item->item_id;
            $warehouse = $line_item->warehouse_id;
            $price = $line_item->amount;
            $quantity = $line_item->quantity;

            // get item warehouse
            $item_warehouse = $this->getItemWarehouse($item, $warehouse);

            if (!$item_warehouse) {
                throw new \Exception('Item warehouse not found', 1);
            }

            $prev_cost = round(floatval($item_warehouse->item_cost), 2);

            $temp_cost = round($quantity * $price, 2);
            // calculate qty
            switch ($document->transaction_type) {
                // goods receipt PO and a/p invoice
                case 'GR':
                case 'CP':
                    $item_warehouse->on_hand_qty = $item_warehouse->on_hand_qty + $quantity;
                    if ($document->base_type == 'PO') {
                        $item_warehouse->ordered_qty = $item_warehouse->ordered_qty - $quantity;
                    }
                    break;

                case 'BL':
                    if (!$document->base_type) {
                        $item_warehouse->on_hand_qty = $item_warehouse->on_hand_qty + $quantity;
                    }

                    if ($document->base_type == 'PO') {
                        $item_warehouse->on_hand_qty = $item_warehouse->on_hand_qty + $quantity;
                        $item_warehouse->ordered_qty = $item_warehouse->ordered_qty - $quantity;
                    }
                    break;

                // goods return and debit note / a/p credit memo
                case 'GN':
                case 'DN':
                    $item_warehouse->on_hand_qty = $item_warehouse->on_hand_qty - $quantity;

                    // TODO open purchase order
//                if ($document->transaction_type == 'GN') {
//                    $item_warehouse->ordered_qty = $item_warehouse->ordered_qty + $quantity;
//                }
                    break;

                case 'SD':
                case 'CS':
                    $item_warehouse->on_hand_qty = $item_warehouse->on_hand_qty - $quantity;
                    if ($document->base_type == 'SO') {
                        $item_warehouse->committed_qty = $item_warehouse->committed_qty - $quantity;
                    }
                    break;

                case 'IN':
                    if (!$document->base_type) {
                        $item_warehouse->on_hand_qty = $item_warehouse->on_hand_qty - $quantity;
                    }

                    if ($document->base_type == 'SO') {
                        $item_warehouse->on_hand_qty = $item_warehouse->on_hand_qty - $quantity;
                        $item_warehouse->committed_qty = $item_warehouse->committed_qty - $quantity;
                    }
                    break;

                case 'SR':
                case 'CN':
                    $item_warehouse->on_hand_qty = $item_warehouse->on_hand_qty + $quantity;

                    // TODO open purchase order
//                if ($document->transaction_type == 'GN') {
//                    $item_warehouse->ordered_qty = $item_warehouse->ordered_qty + $quantity;
//                }
                    break;

                // Goods receipt
                case 'GE':
                case 'PR':
                    $item_warehouse->on_hand_qty = $item_warehouse->on_hand_qty + $quantity;
                    break;
                // Goods issue
                case 'GI':
                    $item_warehouse->on_hand_qty = $item_warehouse->on_hand_qty - $quantity;
                    break;
            }

            $item_warehouse->save();
            // calculate cost
            $this->calculateCost($item, $warehouse, $temp_cost, $prev_cost, $document, $price);
        }
    }

    /**
     * @param $item
     * @param $warehouse
     *
     * @return mixed
     */
    public function getItemWarehouse($item, $warehouse): mixed
    {
        $item_warehouse = ItemWarehouse::where('item_id', $item)
            ->where('warehouse_id', $warehouse)
            ->first();

        if (!$item_warehouse) {
            $item_warehouse = ItemWarehouse::updateOrCreate([
                'item_id' => $item,
                'warehouse_id' => $warehouse
            ]);
        }
        return $item_warehouse;
    }

    /**
     * @param $item
     * @param $warehouse
     * @param $temp_cost
     * @param $prev_cost
     * @param $document
     * @param $amount
     */
    public function calculateCost($item, $warehouse, $temp_cost, $prev_cost, $document, $amount)
    {
        // calculate with average cost
        $item_warehouse = $this->getItemWarehouse($item, $warehouse);

        $sales = ['SO', 'SD', 'IN', 'RC', 'CN', 'SR', 'GI', 'PI', 'CS'];
        if (!Str::contains($document->transaction_type, $sales)) {
            if ($item_warehouse->available_qty) {
                $item_cost = round(($temp_cost + $prev_cost) / $item_warehouse->available_qty, 2);
            } else {
                $item_cost = round($amount, 2);
            }

            $item_warehouse->item_cost = $item_cost;
            $item_warehouse->save();
        }
    }

    /**
     * @param $request
     *
     * @return void
     */
    protected function validateRequest($request)
    {
        App::setLocale(auth()->user()->locale);
        $request->validate([
            'transaction_no' => 'required',
            'narration' => 'required',
            'contact_id' => Rule::requiredIf(!Str::contains($request->transaction_type, ['GI', 'GE'])),
        ], [
            'transaction_no.required' => __('validation')['required'],
            'narration.required' => __('validation')['required'],
            'contact_id.required' => __('document')['contactRequired'],
        ]);
    }

    /**
     * @param $details
     * @param $transaction_type
     * @param $doc_id
     * @param $action
     *
     * @return array
     */
    protected function validateDetails($details, $transaction_type, $doc_id, $action): array
    {
        if (!Str::contains($transaction_type, ['RC', 'PY'])) {
            if (count($details) == 0) {
                return ['error' => true, 'message' => 'Details cannot empty!'];
            }
        }

        foreach ($details as $index => $detail) {
            $lines = $index + 1;

            if (!Str::contains($transaction_type, ['RC', 'PY'])) {
                if (!array_key_exists('item_id', $detail)) {
                    return ['error' => true, 'message' => "Line $lines: Item cannot empty!"];
                } elseif (empty($detail['item_id'])) {
                    return ['error' => true, 'message' => "Line $lines: Item cannot empty!"];
                }

                if (empty($detail['whs_name'])) {
                    return ['error' => true, 'message' => "Line $lines: Warehouse cannot empty!"];
                }

                if (empty($detail['quantity'])) {
                    return ['error' => true, 'message' => "Line $lines: Quantity cannot empty!"];
                }
                if ($detail['quantity'] == 0) {
                    return ['error' => true, 'message' => "Line $lines: Quantity cannot 0!"];
                }

                if (empty($detail['amount'])) {
                    return ['error' => true, 'message' => "Line $lines: Price cannot empty!!"];
                }
                if ($detail['amount'] == 0) {
                    return ['error' => true, 'message' => "Line $lines: Price cannot 0!"];
                }
            }

            $sales = ['SO', 'SD', 'IN', 'CN', 'SR'];

            if (Str::contains($transaction_type, $sales)) {
                $item = Item::find($detail['item_id']);

                if (!Str::contains($action, ['closed', 'canceled'])) {
                    if ($doc_id == 0) {
                        // item warehouse
                        if ($item->item_group == 'Inventory') {
                            if (count($item->itemWarehouse) < 1) {
                                return [
                                    'error' => true,
                                    'message' => "Line $lines: Cannot find item in this warehouse "
                                        . $detail['whs_name']
                                ];
                            }
                        }

                        foreach ($item->itemWarehouse as $item) {
                            if ($detail['whs_name'] === $item->whs_name) {
                                if ($detail['quantity'] > $item->available_qty) {
                                    return [
                                        'error' => true,
                                        'message' => "Line $lines: Available quantity for item "
                                            . $item->code . " is " . $item->available_qty
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        return ['error' => false];
    }
}
