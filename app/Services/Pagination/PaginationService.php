<?php

namespace App\Services\Pagination;

use App\Data\PaginationData;
use Illuminate\Database\Eloquent\Builder;

class PaginationService
{

    public function paginate(Builder $queryBuilder, int $perPage, int $page): PaginationData
    {
        $paginator = $queryBuilder->paginate($perPage, ['*'], 'page', $page);

        return PaginationData::fromPaginator($paginator);
    }
}
