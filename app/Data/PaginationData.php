<?php

namespace App\Data;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaginationData
{
    public function __construct(
        public int $currentPage,
        public int $perPage,
        public int $total,
        public int $lastPage,
        public array $items
    ){}

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        return  new self(
            $paginator->currentPage(),
            $paginator->perPage(),
            $paginator->total(),
            $paginator->lastPage(),
            $paginator->items()
        );
    }

    public function toArray(): array
    {
        return [
            'current_page' => $this->currentPage,
            'per_page' => $this->perPage,
            'total' => $this->total,
            'last_page' => $this->lastPage,
            'items' => $this->items
        ];
    }

    public function toMetaArray(): array
    {
        return [
            'current_page' => $this->currentPage,
            'per_page' => $this->perPage,
            'total' => $this->total,
            'last_page' => $this->lastPage
        ];
    }

    public function toDataArray(string $key = 'items'): array
    {
        return [
            $key => $this->items
        ];
    }
}
