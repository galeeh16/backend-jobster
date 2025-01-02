<?php declare(strict_types=1);

namespace App\Contracts;

use App\Models\JobTitle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface JobTitleContract
{
    public function getListPaginate(int $page = 10): LengthAwarePaginator;

    public function findByID(int $id): JobTitle;

    public function add(string $title_name): JobTitle;

    public function update(int $id, string $title_name): JobTitle;

    public function delete(int $id): bool|null;
}