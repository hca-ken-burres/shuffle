<?php

namespace App\Models;

use App\Enums\RequisitionCategory;
use App\Enums\RequisitionStatus;
use App\Notifications\Requisition\RequisitionSubmitted;
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

    public function hasItems() {

        return count($this->items) > 0;

    }

    public function isDraft() {
        return $this->status === RequisitionStatus::DRAFT;
    }

    public function isSubmitted() {
        return $this->status === RequisitionStatus::SUBMITTED;
    }


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

    public function submit() {

        $this->submitted_at = now();
        $this->status = RequisitionStatus::SUBMITTED;
        $this->save();

        $req = $this;
        User::managers()->each(function($m) use ($req) {
            $m->notify( new RequisitionSubmitted($req) );
        });

    }

    public function totalCost() {
        return number_format(
            (collect($this->items))
                ->map(fn($item) => $item['quantity'] * $item['unit_price'])
                ->sum(),
            2);
    }

    public function unsubmit() {
        if($this->status === RequisitionStatus::SUBMITTED) {
            $this->status = RequisitionStatus::DRAFT;
            $this->submitted_at = null;
            $this->save();
        }
    }
}
