<?php

namespace App\DTO;

use Carbon\Carbon;

class BalanceRequestDTO
{
    public $id;
    public $fromDate;
    public $toDate;
    public $type;
    public $page;
    public $perPage;

    public function __construct($id, $fromDate = null, $toDate = null, $type = null, $page = 1, $perPage = 10)
    {
        $this->id = $id;
        $this->fromDate = $fromDate ?? Carbon::now()->startOfMonth()->toDateString();
        $this->toDate = $toDate ?? Carbon::now()->endOfMonth()->toDateString();
        $this->type = $type;
        $this->page = (int) $page;
        $this->perPage = (int) $perPage;
    }

    public static function fromRequest($id, array $data)
    {
        return new self(
            $id,
            $data['from_date'] ?? null,
            $data['to_date'] ?? null,
            $data['type'] ?? null,
            $data['page'] ?? 1,
            $data['perPage'] ?? 10
        );
    }
}
