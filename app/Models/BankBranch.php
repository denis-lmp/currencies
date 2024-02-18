<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankBranch extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bank_branches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['bank_id', 'name', 'address', 'coordinates', 'phone_number'];

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
     * Get the bank that owns the branch.
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}
