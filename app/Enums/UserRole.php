<?php

namespace App\Enums;

enum UserRole: string {
    case Admin = 'admin';
    case Owner = 'owner';
    case Manager = 'manager';
    case Receptionist = 'receptionist';
    case Customer = 'customer';
}
