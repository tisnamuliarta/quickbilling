<?php

namespace App\Traits;

use App\Models\Inventory\ItemWarehouse;
use Illuminate\Support\Str;

trait InventoryHelper
{
    /**
     * @param $line_item
     * @param $document
     * @return void
     */
    public function processOnHandQty($line_item, $document)
    {
        $item = $line_item->item_id;
        $warehouse = $line_item->warehouse_id;
        $price = $line_item->price;
        $quantity = $line_item->quantity;

        // get item warehouse
        $item_warehouse = $this->getItemWarehouse($item, $warehouse);

        $prev_cost = floatval($item_warehouse->item_cost);

        $temp_cost = $quantity * $price;
        // calculate qty
        switch ($document->transaction_type) {
            // goods receipt PO and a/p invoice
            case 'GR':
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
        }

        $item_warehouse->save();
        // calculate cost
        $this->calculateCost($item, $warehouse, $temp_cost, $prev_cost, $document);
    }

    /**
     * @param $item
     * @param $warehouse
     * @return mixed
     */
    public function getItemWarehouse($item, $warehouse): mixed
    {
        return ItemWarehouse::where('item_id', $item->id)
            ->where('warehouse_id', $warehouse)
            ->first();
    }

    /**
     * @param $item
     * @param $warehouse
     * @param $temp_cost
     * @param $prev_cost
     * @param $document
     */
    public function calculateCost($item, $warehouse, $temp_cost, $prev_cost, $document)
    {
        // calculate with average cost
        $item_warehouse = $this->getItemWarehouse($item, $warehouse);

        $sales = ['SO', 'SD', 'IN', 'RC', 'CN', 'SR'];
        if (!Str::contains($document->transaction_type, $sales)) {
            $item_cost = ($temp_cost + $prev_cost) / $item_warehouse->available_qty;

            $item_warehouse->item_cost = $item_cost;
            $item_warehouse->save();
        }
    }
}
