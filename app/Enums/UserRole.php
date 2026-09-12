<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case AttractionOwner = 'attraction_owner';
    case AttractionAdmin = 'attraction_admin';
    case AttractionReceptionist = 'attraction_receptionist';
    case SchoolUser = 'school_user';
    case Individual = 'individual';
}
