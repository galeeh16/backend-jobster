<?php declare(strict_types=1);

namespace App\Http\Controllers\API\Admin\JobTitle;

use Exception;
use Illuminate\Http\Request;
use App\Contracts\JobTitleContract;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\JobTitle\JobTitleCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/** 
 * @group Admin
 * 
 * Admin Management
 * 
 * @subgroup Job Title
 * @subgroupDescription CRUD Job Title
 */
final class JobTitleController extends Controller
{
    public function __construct(private readonly JobTitleContract $jobTitleService) {} 

    /** 
     * Get list job title paginate
     * 
     * @header Authorization Bearer <token>
     */
    public function index(Request $request)
    {
        $job_titles = $this->jobTitleService->getListPaginate();

        return new JobTitleCollection($job_titles);
    }

    /**
     * Create Job Title
     *
     * @header Authorization Bearer <token>
     * @bodyParam title_name string required The title name of job. No-Example
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_name' => 'required|string|unique:job_title,title_name'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), 'Invalid Data', 422);
        }

        try {
            $job_title = $this->jobTitleService->add($request->title_name);
            
            return $this->successResponse($job_title, 'Success Create Job Title', 201);
        } catch (Exception $e) {
            return $this->errorResponse($this->formatError($e));
        }
    }

    /**
     * Get job title by ID
     *
     * @header Authorization Bearer <token>
     * @urlParam id integer required The ID of job title. No-Example
     */
    public function show($id)
    {
        try {
            $job_title = $this->jobTitleService->findByID(intval($id));
            
            return $this->successResponse($job_title);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(null, $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->errorResponse($this->formatError($e), $e->getMessage());
        }
    }

    /**
     * Update job title by ID
     *
     * @header Authorization Bearer <token>
     * @bodyParam title_name string required The title name. No-Example
     * @urlParam id integer required the ID of job title. No-Example
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title_name' => 'required|string|unique:job_titles,title_name,'.$id
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), 'Invalid Data', 422);
        }

        try {
            $job_title = $this->jobTitleService->update(intval($id), $request->title_name);
            
            return $this->successResponse($job_title, 'Success Update Job Title', 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(null, $e->getMessage(), 404);
        } catch (Exception $e) {
            return $this->errorResponse($this->formatError($e));
        }
    }

    /**
     * Delete job title by ID
     *
     * @header Authorization Bearer <token>
     * @urlParam id integer required the ID of job title. No-Example
     */
    public function destroy($id)
    {
        try {
            $job_title = $this->jobTitleService->delete(intval($id));
            
            return $this->successResponse($job_title, 'Success Dselete Job Title', 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(null, $e->getMessage(), 404);
        } catch (Exception $e) {
            return $this->errorResponse($this->formatError($e));
        }
    }
}
