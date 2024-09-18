<?php

namespace App\Enums;

enum PermissionType :string
{
    case CREATE_POSTS = 'create posts';
    case EDIT_POSTS = 'edit posts';
    case DELETE_POSTS = 'delete posts';
    case PUBLISH_POSTS = 'publish posts';
    case UNPUBLISH_POSTS = 'unpublish posts';
}
