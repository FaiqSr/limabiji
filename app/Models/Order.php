<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'city',
        'province',
        'shipping_district_id',
        'weight_grams',
        'postal_code',
        'courier',
        'notes',
        'subtotal',
        'shipping_cost',
        'total_amount',
        'payment_method',
        'payment_status',
        'payment_method_type',
        'midtrans_transaction_id',
        'midtrans_status',
        'payment_instructions',
        'expires_at',
        'snap_token',
        'payment_reference',
        'paid_at',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'shipping_cost' => 'integer',
        'total_amount' => 'integer',
        'payment_instructions' => 'array',
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
        'weight_grams' => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedTotal(): string
    {
        return 'Rp '.number_format($this->total_amount, 0, ',', '.');
    }

    public function getInstructions(): array
    {
        return $this->payment_instructions ?? [];
    }

    public function getVaNumber(): ?string
    {
        $instructions = $this->getInstructions();

        if (! empty($instructions['permata_va_number'])) {
            return $instructions['permata_va_number'];
        }

        if (! empty($instructions['va_numbers'][0]['va_number'])) {
            return $instructions['va_numbers'][0]['va_number'];
        }

        return null;
    }

    public function getActionUrl(string $name): ?string
    {
        foreach ($this->getInstructions()['actions'] ?? [] as $action) {
            if (($action['name'] ?? null) === $name && ! empty($action['url'])) {
                return $action['url'];
            }
        }

        return null;
    }

    public function getQrUrl(): ?string
    {
        return $this->getActionUrl('generate-qr-code');
    }

    public function getDeepLinkUrl(): ?string
    {
        return $this->getActionUrl('deeplink-redirect');
    }

    public function isPaymentExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast() && $this->payment_status === 'pending';
    }

    public function getFormattedSubtotal(): string
    {
        return 'Rp '.number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedShipping(): string
    {
        return 'Rp '.number_format($this->shipping_cost, 0, ',', '.');
    }
}
