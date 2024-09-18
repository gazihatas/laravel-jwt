<?php

namespace App\Enums;

enum RoleType : string
{
    case ADMIN = 'Admin';
    case USER = 'User';
    case WRITER = 'Writer';
    case AUTHOR = 'Author';


}
