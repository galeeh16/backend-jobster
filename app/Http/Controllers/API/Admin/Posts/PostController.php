<?php declare(strict_types=1);

namespace App\Http\Controllers\API\Admin\Posts;

use Exception;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @group Admin
 * 
 * @subgroup Post
 */
final class PostController extends Controller
{
    public function __construct() {}

    public function index(): JsonResponse
    {
        return $this->successResponse();
    }

    /**
     * Add a post or job
     * 
     * @header Authorization Bearer <token>
     *
     * @bodyParam job_title_id integer required Job Title. No-example
     * @bodyParam post_title string required Post Title. No-example
     * @bodyParam location string required Location. No-example
     * @bodyParam overview string required Overview. No-example
     * @bodyParam responsabilities string required Responsabilites. No-example
     * @bodyParam requirements string required Requirements. No-example
     * @bodyParam skills string required Skills. No-example
     * @bodyParam experience_year integer required Experience Year. No-example
     * @bodyParam employment_type string required Employment Type. No-example
     * @bodyParam level_type string required Level Type. No-example
     * @bodyParam salary integer required Salary. No-example
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'job_title_id'      => 'required',
            'post_title'        => 'required|min:3|max:255',
            'location'          => 'required|min:3|max:255',
            'overview'          => 'required|min:3|max:1000',
            'responsabilities'  => 'required|min:3|max:11000',
            'requirements'      => 'required|min:3|max:11000',
            'skills'            => 'required|min:3|max:11000',
            'experience_year'   => 'required|integer|gt:0',
            'employment_type'   => 'required|string|in:full_time,in:work_from_home,full_time,remote',
            'level_type'        => 'required|string|in:junior,middle,senior,head',
            'salary'            => 'required|integer|gt:0',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), 'Invalid Data', 422);
        }

        DB::beginTransaction();

        try {
            $user = Auth::user();

            // cek jika user_type bukan company
            if ($user->user_type !== 'company') {
                return $this->errorResponse(null, 'Permission denied, you can not add post', 403);
            }

            $post = Post::create([
                'company_id' => $user->id,
                'job_title_id' => $request->job_title_id,
                'post_title' => $request->post_title,
                'location' => $request->location,
                'overview' => $request->overview,
                'responsabilities' => $request->responsabilities,
                'requirements' => $request->requirements,
                'skills' => $request->skills,
                'experience_year' => $request->experience_year,
                'employment_type' => $request->employment_type,
                'level_type' => $request->level_type,
                'salary' => $request->salary,
            ]);

            DB::commit();
            return $this->successResponse($post, 'Success Create Post', 201);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse(errors: $this->formatError($e), status: 500);
        }
    }
}