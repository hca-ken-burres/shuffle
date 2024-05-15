<?php

namespace App\Enums;

enum PaymentMethod : string {

    case CASH = 'Cash';
    case CHECK = 'Check';
    case CREDIT = 'Credit';
    case PO = 'PO';
    case WIRE = 'Wire';

}