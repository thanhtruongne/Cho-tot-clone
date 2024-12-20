<?php

namespace App\Observers;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use App\Models\Products;
use App\Models\ProductVehicle;

class ProductVehicleObserver implements  ShouldHandleEventsAfterCommit
{
    public function created(ProductVehicle $productElectronics): void
    {
        Products::create([
            'product_id' => $productElectronics->id,
            'user_id' => $productElectronics->user_id,
            'type' => $productElectronics->type_product, // 3
        ]);
    }

  
}
