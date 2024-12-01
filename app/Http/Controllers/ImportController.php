<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Services\ShopifyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    private ShopifyService $shopifyService;

    public function __construct(ShopifyService $shopifyService)
    {
        $this->shopifyService = $shopifyService;
    }

    public function import(Request $request): JsonResponse
    {
        try {
            $data = $this->shopifyService->getOrders();

            if (empty($data['orders'])) {
                return response()->json(['message' => 'No orders found in Shopify'], 404);
            }

            // Truncate existing data
            Order::truncate();
            Customer::truncate();

            // Import orders and customers
            foreach ($data['orders'] as $orderData) {
                if (!empty($orderData['customer'])) {
                    $customerData = $orderData['customer'];

                    // Create or update customer
                    $customer = Customer::updateOrCreate(
                        ['email' => $customerData['email']],
                        [
                            'first_name' => $customerData['first_name'] ?? null,
                            'last_name' => $customerData['last_name'] ?? null,
                        ]
                    );

                    // Create order
                    Order::create([
                        'customer_id' => $customer->id,
                        'total_price' => $orderData['total_price'] ?? 0,
                        'financial_status' => $orderData['financial_status'] ?? 'pending',
                        'fulfillment_status' => $orderData['fulfillment_status'] ?? null,
                        'currency' => $orderData['currency'] ?? 'USD',
                        'order_number' => $orderData['order_number'] ?? null,
                        'processed_at' => $orderData['processed_at'] ?? null,
                    ]);
                }
            }

            return response()->json(['message' => 'Data imported successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}
