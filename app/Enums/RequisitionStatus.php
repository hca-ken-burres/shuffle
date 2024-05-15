<?php

namespace App\Enums;

enum RequisitionStatus : string 
{

    case DRAFT = 'Draft';
    case SUBMITTED = 'Submitted';
    case APPROVED = 'Approved';
    case ORDERED = 'Ordered';
    case RECEIVED = 'Received';
    case REJECTED = 'Rejected';

    public function getColor(): string {
        return match($this) {
            self::DRAFT => 'gray',
            self::SUBMITTED => 'warning',
            self::APPROVED => 'info',
            self::ORDERED => 'primary',
            self::RECEIVED => 'success',
            self::REJECTED => 'danger'
        };
    }

}