<?php

namespace App\Observers;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use App\Models\ProductRentHouse;
use App\Models\Products;
class ProductRentHouseObserver implements  ShouldHandleEventsAfterCommit
{
    /**
     * Handle the ProductRentHouse "created" event.
     */
    public function created(ProductRentHouse $productRentHouse): void
    {
        Products::create([
            'product_id' => $productRentHouse->id,
            'user_id' => $productRentHouse->user_id,
            'type' => $productRentHouse->type_product, // 1
        ]);
    }
}
