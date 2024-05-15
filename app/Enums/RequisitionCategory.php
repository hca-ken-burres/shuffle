<?php

namespace App\Enums;

enum RequisitionCategory : string {

    case BOOKS = "Books";
    case SUPPLIES = "Supplies";
    case EQUIPMENT = "Equipment";
    case OTHER = "Other";

    public function getColor() {
        return match($this) {
            self::BOOKS => 'info',
            self::SUPPLIES => 'warning',
            self::EQUIPMENT => 'success',
            self::OTHER => 'gray'
        };
    }

}