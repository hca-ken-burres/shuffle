<?php

namespace App\Models;

use App\Enums\RequisitionCategory;
use App\Enums\RequisitionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requisition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'submitted_at' => 'datetime',
        'admin_id' => 'integer',
        'approved_at' => 'datetime',
        'orderer_id' => 'integer',
        'ordered_at' => 'datetime',
        'received_at' => 'datetime',
        'user_id' => 'integer',
        'vendor_id' => 'integer',
        'year_id' => 'integer',
        'category' => RequisitionCategory::class,
        'status' => RequisitionStatus::class,
        'items' => 'array',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function year(): BelongsTo
    {
        return $this->belongsTo(Year::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function totalCost() {
        return number_format(
            (collect($this->items))
                ->map(fn($item) => $item['quantity'] * $item['unit_price'])
                ->sum(),
            2);
    }
}
