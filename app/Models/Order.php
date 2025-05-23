<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'expire_time' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Relation to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation to Event
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relation to OrderItems
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Check if the order is expired
     */
    public function isExpired()
    {
        return $this->expire_time < now() && $this->payment_status == 'pending';
    }

    /**
     * Check if the order is paid
     */
    public function isPaid()
    {
        return $this->payment_status == 'paid';
    }

    /**
     * Check if the order is pending
     */
    public function isPending()
    {
        return $this->payment_status == 'pending' && !$this->isExpired();
    }

    /**
     * Check if the order is canceled
     */
    public function isCanceled()
    {
        return in_array($this->payment_status, ['canceled', 'failed']);
    }

    /**
     * Get payment status with badge color
     */
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'paid' => 'success',
            'failed' => 'danger',
            'expired' => 'secondary',
            'canceled' => 'danger',
        ];

        $status = $this->isExpired() ? 'expired' : $this->payment_status;

        return [
            'status' => $status,
            'color' => $colors[$status] ?? 'primary',
        ];
    }

    /**
     * Get formatted price with currency
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y, H:i');
    }

    /**
     * Get formatted status
     */
    public function getFormattedStatusAttribute()
    {
        $statusMap = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Pembayaran Berhasil',
            'failed' => 'Pembayaran Gagal',
            'expired' => 'Kedaluwarsa',
            'canceled' => 'Dibatalkan',
        ];

        $status = $this->isExpired() ? 'expired' : $this->payment_status;

        return $statusMap[$status] ?? ucfirst($status);
    }

    /**
     * Get time remaining until expiry
     */
    public function getTimeRemainingAttribute()
    {
        if (!$this->isPending()) {
            return null;
        }

        $now = now();
        $timeRemaining = $now->diffInSeconds($this->expire_time, false);

        if ($timeRemaining <= 0) {
            return '00:00:00';
        }

        $hours = floor($timeRemaining / 3600);
        $minutes = floor(($timeRemaining % 3600) / 60);
        $seconds = $timeRemaining % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}
