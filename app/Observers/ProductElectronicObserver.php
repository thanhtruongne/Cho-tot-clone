<?php

namespace App\Observers;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use App\Models\ProductElectronics;
use App\Models\Products;
class ProductElectronicObserver implements  ShouldHandleEventsAfterCommit
{
    public function created(ProductElectronics $productElectronics): void
    {
        Products::create([
            'product_id' => $productElectronics->id,
            'user_id' => $productElectronics->user_id,
            'type' => $productElectronics->type_product, // 2
        ]);
    }

  
}
