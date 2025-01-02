<?php declare(strict_types=1);

namespace App\Repositories;

use App\Models\JobTitle;
use App\Contracts\JobTitleContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class JobTitleRepository implements JobTitleContract
{
    public function getListPaginate(int $page = 10): LengthAwarePaginator
    {
        $job_titles = JobTitle::paginate(10);

        return $job_titles;
    }

    public function findByID(int $id): JobTitle
    {
        $job_title = JobTitle::where('id', $id)->first();
        if (!$job_title) {
            throw new ModelNotFoundException('Job Tilte Tidak Ditemukan');
        }
        return $job_title;
    }

    public function add(string $title_name): JobTitle
    {
        $job_title = new JobTitle;
        $job_title->title_name = $title_name;
        $job_title->save();

        return $job_title;
    }

    public function update(int $id, string $title_name): JobTitle
    {
        $job_title = $this->findByID($id);
        $job_title->title_name = $title_name;
        $job_title->save();

        return $job_title;
    }

    public function delete(int $id): bool|null
    {
        $job_title = $this->findByID($id);
        return $job_title->delete();
    }
}