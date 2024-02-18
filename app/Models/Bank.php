<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'banks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'slug', 'logo', 'website', 'phone_number', 'email', 'legal_address', 'rating'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Get branches for the bank.
     */
    public function branches(): HasMany
    {
        return $this->hasMany(BankBranch::class);
    }

    /**
     * Get currencies for the bank.
     */
    public function currencyRates(): HasMany
    {
        return $this->hasMany(CurrencyRate::class);
    }
}
