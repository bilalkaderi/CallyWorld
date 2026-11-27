<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

/**
 * Turns the session cart into a Cart and its Orders.
 *
 * Every amount is computed here from the product table. Prices and totals
 * sent by the browser (or stored in the session) are never trusted.
 */
class CartCheckout
{
    public const DELIVERY_FEE = 10;

    /** @var array<int, array{product: Product, quantity: int, unit: float}> */
    public array $lines = [];

    public float $total = 0;

    public function __construct(?array $sessionItems)
    {
        $subtotal = 0;
        foreach ($sessionItems ?? [] as $item) {
            $p = Product::find($item['id'] ?? null);
            if (!$p) {
                continue; // placeholder entry ('0') or a deleted product
            }
            $quantity = max(1, (int) ($item['quantity'] ?? 1));
            $unit = $p->finalPrice();
            $this->lines[] = ['product' => $p, 'quantity' => $quantity, 'unit' => $unit];
            $subtotal += $unit * $quantity;
        }

        // Same delivery rule the cart page shows (cart.blade.php).
        $this->total = $this->lines ? round($subtotal + self::DELIVERY_FEE, 2) : 0;
    }

    public function isEmpty(): bool
    {
        return !$this->lines;
    }

    /** Total in cents, for Stripe. */
    public function totalInCents(): int
    {
        return (int) round($this->total * 100);
    }

    /** Saves the Cart and one or two Orders per line, all priced from the database. */
    public function place(Client $client, string $paymentMethod, ?string $address, Carbon $expectedDate): Cart
    {
        $cart = new Cart();
        $cart->clientId = $client->id;
        $cart->items = json_encode(array_map(fn ($line) => [
            'id' => (string) $line['product']->id,
            'quantity' => $line['quantity'],
            'price' => $line['unit'],
        ], $this->lines));
        $cart->status = 'Registered';
        $cart->total_amount = $this->total;
        $cart->pref_address = $address;
        $cart->payment_method = $paymentMethod;
        $cart->expecting_delivery_date = $expectedDate;
        $cart->save();

        $today = now()->format('Y-m-d');
        foreach ($this->lines as $line) {
            $p = $line['product'];
            $quantity = $line['quantity'];

            if ($p->our_stock < $quantity) {
                // Part comes from the supplier (pending), the rest from our own stock.
                $inStock = (int) $p->our_stock;
                $this->saveOrder($cart, $client, $line, $quantity - $inStock, 'pending', 'yes', $expectedDate);
                $this->saveOrder($cart, $client, $line, $inStock, 'Delivered', 'no', $today);
            } else {
                $this->saveOrder($cart, $client, $line, $quantity, 'Delivered', 'no', $today);
            }
        }

        return $cart;
    }

    private function saveOrder(Cart $cart, Client $client, array $line, int $quantity, string $status, string $visible, $expectedDate): void
    {
        $order = new Order();
        $order->productId = $line['product']->id;
        $order->cartId = $cart->id;
        $order->clientId = $client->id;
        $order->userId = $line['product']->userId;
        $order->quantity = $quantity;
        $order->total_price = round($line['unit'] * $quantity, 2);
        $order->status = $status;
        $order->visible = $visible;
        $order->expecting_delivery_date = $expectedDate;
        $order->save();
    }
}
