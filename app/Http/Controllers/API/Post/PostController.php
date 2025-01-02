<?php declare(strict_types=1);

namespace App\Http\Controllers\API\Post;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Post\PostResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Post\PostCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @group Post
 */
final class PostController extends Controller
{
    public function __construct() {}

    /**
     * Get list post paginate
     * 
     * @queryParam page int The number page Example: 1
     * @queryParam per_page int The limit of posts or jobs displayed. Example: 10
     * @queryParam search string Search for posts or jobs No-example
     */
    public function index(Request $request)
    {
        // usleep(300_000);
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer|gt:0',
            'per_page' => 'nullable|integer|gt:0',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), 'Invalid Data', 422);
        }

        $per_page = $request->per_page ? (int) $request->per_page : 12;
        $posts = Post::query();

        $posts = $posts->with(['jobTitle', 'company.companyProfile']);

        $posts = $posts->orderBy('created_at', 'desc');

        $all_post = $posts->paginate($per_page);
        $collection = new PostCollection($all_post);

        return $collection;
    }

    /**
     * Get Post by ID
     *
     * @urlParam id integer required The ID of the post
     */
    public function show($id)
    {
        usleep(300_000);
        $post = Post::with(['company.companyProfile'])->where('id', $id)->first();
        if (!$post) {
            throw new ModelNotFoundException('Job '. $id .' Not Found');
        }
        return $this->successResponse(new PostResource($post));
    }

    /**
     * Apply Job
     * 
     * @header Authorization Bearer <token>
     * @urlParam id integer required The ID of the post
     */
    public function apply($id)
    {
        $post = Post::where('id', $id)->first();
        if (!$post) {
            throw new ModelNotFoundException('Job '. $id .' Not Found');
        }   
        
        $user = Auth::user();
        $user_id = $user->id;

        // cek apakah user yg apply sama dengan post.user_id
        if ($user_id == $post->company_id) {
            return $this->errorResponse(null, 'You can not apply for your own job', 400);
        }
 
        // cek apakah user sudah apply
        $is_applied = DB::table('post_applies')
                    ->where('post_id', $id)
                    ->where('user_id', $user_id)
                    ->exists();
        
        if ($is_applied) {
            return $this->errorResponse(null, 'You have applying for this job', 400);
        }

        DB::table('post_applies')->insert([
            'post_id' => $id,
            'user_id' => $user_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->successResponse(null, 'Success Apply Job');
    }
}