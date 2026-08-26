<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    protected string $sessionKey = 'limabiji_cart';

    public function getCart(): array
    {
        return session()->get($this->sessionKey, []);
    }

    public function addItem(int $productId, string $weight = '200g', string $grindSize = 'whole_bean', int $quantity = 1): array
    {
        $product = Product::active()->find($productId);
        if (! $product) {
            throw new \InvalidArgumentException('Product not found or inactive.');
        }

        $quantity = max(1, $quantity);
        $weight = in_array($weight, ['200g', '500g', '1kg']) ? $weight : '200g';
        $grindSize = in_array($grindSize, ['whole_bean', 'coarse', 'medium', 'fine']) ? $grindSize : 'whole_bean';

        $cart = $this->getCart();
        $cartKey = $this->generateKey($productId, $weight, $grindSize);
        $unitPrice = $product->getPriceForWeight($weight);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
            $cart[$cartKey]['subtotal'] = $cart[$cartKey]['quantity'] * $unitPrice;
        } else {
            $cart[$cartKey] = [
                'key' => $cartKey,
                'product_id' => $product->id,
                'name' => $product->name,
                'name_id' => $product->name_id,
                'slug' => $product->slug,
                'image' => $product->image,
                'weight' => $weight,
                'grind_size' => $grindSize,
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'subtotal' => $quantity * $unitPrice,
            ];
        }

        session()->put($this->sessionKey, $cart);

        return $cart;
    }

    public function updateQuantity(string $cartKey, int $quantity): array
    {
        $cart = $this->getCart();

        if (isset($cart[$cartKey])) {
            if ($quantity <= 0) {
                unset($cart[$cartKey]);
            } else {
                $cart[$cartKey]['quantity'] = $quantity;
                $cart[$cartKey]['subtotal'] = $cart[$cartKey]['quantity'] * $cart[$cartKey]['unit_price'];
            }
            session()->put($this->sessionKey, $cart);
        }

        return $cart;
    }

    public function removeItem(string $cartKey): array
    {
        $cart = $this->getCart();

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put($this->sessionKey, $cart);
        }

        return $cart;
    }

    public function clear(): void
    {
        session()->forget($this->sessionKey);
    }

    public function getItemCount(): int
    {
        $cart = $this->getCart();
        $count = 0;
        foreach ($cart as $item) {
            $count += (int) ($item['quantity'] ?? 1);
        }

        return $count;
    }

    public function getSubtotal(): int
    {
        $cart = $this->getCart();
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += (int) ($item['subtotal'] ?? 0);
        }

        return $subtotal;
    }

    public function getFormattedSubtotal(): string
    {
        return 'Rp '.number_format($this->getSubtotal(), 0, ',', '.');
    }

    protected function generateKey(int $productId, string $weight, string $grindSize): string
    {
        return md5("{$productId}_{$weight}_{$grindSize}");
    }
}
