<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * API /v1.1/product/list 輸出資料過濾
 */
class BookCollection extends ResourceCollection
{
    public $totalRecords = 0;
    public $per_page = 0;
    public $current_page = 0;

    public function __construct($dataSource, $totalRecords)
    {
        parent::__construct($dataSource);
        $rpp = (int) env('RECORDS_PER_PAGE', 10);
        $this->per_page = $rpp;
        $this->totalRecords = $totalRecords;
        $this->current_page = ceil($this->totalRecords / $rpp);
    }

    public function toArray($request)
    {
        $records = [];
        foreach ($this->collection as $book) {
            $record = new BookResource($book);
            $records[] = $record;
        }

        return [
            'data' => $records,
            'total' => $this->totalRecords,
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
        ];
    }
}
