<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurrencyRate extends Model
{
    use HasFactory;

    const RATE_NBU = 1;
    const RATE_MINFIN = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'currency_rates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['currency_id', 'rate', 'bank_id', 'resource_type'];

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
     * Get currency info for currency rate.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}
