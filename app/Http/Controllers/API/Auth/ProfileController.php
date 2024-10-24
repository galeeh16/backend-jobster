<?php declare(strict_types=1);

namespace App\Http\Controllers\API\Auth;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Contracts\StorageContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Validator;

/**
 * @group Auth
 *
 * APIs for auth
 */
final class ProfileController extends Controller
{
    public function __construct(private StorageContract $storageService) {}

    /**
     * Get Profile
     * 
     * @header Authorization Bearer <token>
     */
    public function me(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->user_type === 'company') {
            $user = $user->load(['companyProfile']);
            $user = new UserResource($user);
        } else {
            $user = $user->load('userProfile');
            $user = new UserResource($user);
        }
        return $this->successResponse($user, 'Get Profile Success');
    }

     /**
     * Update Profile 
     * 
     * @header Authorization Bearer <token>
     * Company or User are same, different case by user_type in JWT Token
     */
    public function update(Request $request)
    {   
        /** @var \App\Models\User $user */
        $user = $request->user();
        $rules_validator = [];

        // company
        if ($user->user_type === 'company') {
            $rules_validator = [
                'address'       => 'required|string',
                'location'      => 'required|string',
                'about_company' => 'required|string',
                'company_size'  => 'required|string',
                'founded_in'    => 'required|date_format:Y-m-d',
                'photo'         => 'required|mimes:png,jpg,jpeg',
                'website_url'   => 'nullable|string|max:100',
                'facebook_url'  => 'nullable|string|max:100',
                'instagram_url' => 'nullable|string|max:100',
                'twitter_url'   => 'nullable|string|max:100',
                'linked_in_url' => 'nullable|string|max:100',
            ];
        } 
        // user
        else {
            $rules_validator = [
                'job_title_id'          => 'required',
                'location'              => 'required|string|max:100',
                'full_address'          => 'required|string|max:100',
                'about_me'              => 'required|string|max:100',
                'phone'                 => 'required|string|max:100',
                'photo'                 => 'required|mimes:png,jpg,jpeg',
                'cv'                    => 'required|mimes:pdf',
                'portfolio'             => 'required|mimes:pdf',
                'experience_year'       => 'required|string|max:100',
                'availibilty_for_work'  => 'required|boolean',
            ];
        }

        $validator = Validator::make($request->all(), $rules_validator);

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), 'Invalid Data', 422);
        }

        DB::beginTransaction();

        try {
            $id = $user->id;

            // company profile
            if ($user->user_type === 'company') {
                $dir = 'companies/' . date('Y/m/d') . '/' . $id; 

                // update photo company
                $photo = $request->file('photo');
                $new_photo_name = 'photo_'.rand() . '.' . $photo->getClientOriginalExtension();
                $this->storageService->upload($photo, $dir, $new_photo_name);

                $user->companyProfile()->updateOrCreate([
                    'company_id' => $id
                ], [
                    'address' => $request->address,
                    'location' => $request->location,
                    'about_company' => $request->about_company,
                    'company_size' => $request->company_size,
                    'founded_in' => $request->founded_in,
                    'photo' => $dir . '/' . $new_photo_name,
                    'website_url' => $request->website_url,
                    'facebook_url' => $request->facebook_url,
                    'instagram_url' => $request->instagram_url,
                    'twitter_url' => $request->twitter_url,
                    'linked_in_url' => $request->linked_in_url,
                ]);
            } 
            // user profile
            else {
                $dir = 'users/' . date('Y/m/d') . '/' . $id; 

                // update photo user
                $photo = $request->file('photo');
                $new_photo_name = 'photo_'.rand() . '.' . $photo->getClientOriginalExtension();
                $this->storageService->upload($photo, $dir, $new_photo_name);

                // update cv
                $cv = $request->file('cv');
                $new_cv_name = 'cv_'.rand() . '.' . $cv->getClientOriginalExtension();
                $this->storageService->upload($cv, $dir, $new_cv_name);

                // update portfolio
                $portfolio = $request->file('portfolio');
                $new_portfolio_name = 'portfolio_'.rand() . '.' . $portfolio->getClientOriginalExtension();
                $this->storageService->upload($cv, $dir, $new_portfolio_name);

                $user->userProfile()->updateOrCreate([
                    'user_id' => $id
                ], [
                    'job_title_id' => $request->job_title_id,
                    'location' => $request->location,
                    'full_address' => $request->full_address,
                    'about_me' => $request->about_me,
                    'phone' => $request->phone,
                    'photo' => $dir . '/' . $new_photo_name,
                    'cv' => $dir . '/' . $new_cv_name,
                    'portfolio' => $dir . '/' . $new_portfolio_name,
                    'experience_year' => $request->experience_year,
                    'availibilty_for_work' => $request->availibilty_for_work,
                ]);
            }

            DB::commit();
            return $this->successResponse(null, 'Success Update Profile', 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error Update Profile', ['error' => $this->formatError($e)]);
            return $this->errorResponse($this->formatError($e));
        }
    }
}