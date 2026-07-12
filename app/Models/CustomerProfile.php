<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerProfile extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'user_id',
        'customer_type',
        'first_name',
        'last_name',
        'company_name',
        'rut',
        'business_activity',
        'email',
        'phone',
        'mobile',
        'birth_date',
        'gender',
        'notes',
        'newsletter',
        'accept_promotions',
        'accept_sms',
        'accept_whatsapp',
        'accept_email_marketing',
        'accept_cookies',
        'consent_accepted_at',
        'reward_points',
        'preferred_price_list_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'newsletter' => 'boolean',
            'accept_promotions' => 'boolean',
            'accept_sms' => 'boolean',
            'accept_whatsapp' => 'boolean',
            'accept_email_marketing' => 'boolean',
            'accept_cookies' => 'boolean',
            'consent_accepted_at' => 'datetime',
            'reward_points' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function preferredPriceList(): BelongsTo
    {
        return $this->belongsTo(PriceList::class, 'preferred_price_list_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function companies(): HasMany
    {
        return $this->hasMany(CustomerCompany::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CustomerDocument::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(CustomerFavorite::class);
    }

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function rewardTransactions(): HasMany
    {
        return $this->hasMany(CustomerRewardTransaction::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(CustomerNote::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(CustomerTag::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name) ?: $this->company_name ?: $this->email;
    }
}
