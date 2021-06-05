<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Show the email verification notice.
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect($this->redirectPath())
            : view('home.app');
    }

    /**
     * Resend the email verification notification.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function send(Request $request)
    {
        try {
            $curr_email = $request->user()->email;

            if ($request->user()->hasVerifiedEmail()) {
                return response()->json([
                    'status' => 0,
                    'email' => $curr_email,
                    'email_verify' => true,
                    'message' => "寄送失敗，你已驗證過郵件"
                ], 400);
            }

            $this->validate($request, [
                'email' => ['required', 'string', 'email', 'max:255']
            ]);

            $email = $request->post('email');
            $EmailUnverified = User::query()->EmailUnverified($email);

            if (!$EmailUnverified) {
                return response()->json([
                    'status' => 0,
                    'email' => $curr_email,
                    'email_verify' => false,
                    'message' => "寄送失敗，已有玩家使用驗證該郵件"
                ], 400);
            }

            $request->user()->forceFill([
                'email' => $email
            ])->save();

            $request->user()->sendEmailVerificationNotification();

            return response()->json([
                'status' => 1,
                'email' => $email,
                'email_verify' => false,
                'message' => "寄送成功"
            ], 202);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'email' => $curr_email,
                'email_verify' => false,
                'message' => "寄送失敗，具有無效的格式"
            ], 400);
        }
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     *
     */
    public function verify(Request $request)
    {
        try {
            if (!$request->user()) {
                return response()->json([
                    'status' => 1,
                    'message' => '郵件驗證失敗，請先登入在驗證',
                ], 400);
            }

            if (!hash_equals((string)$request->route('id'), (string)$request->user()->getKey())) {
                throw new AuthorizationException;
            }

            if (!hash_equals((string)$request->route('hash'), sha1($request->user()->getEmailForVerification()))) {
                throw new AuthorizationException;
            }

        } catch (AuthorizationException $e) {
            return response()->json([
                'status' => 0,
                'message' => '郵件驗證失敗，驗證碼無效',
            ], 400);
        }

        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'status' => 1,
                'message' => '你的郵件已驗證',
            ], 400);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json([
            'status' => 1,
            'message' => '郵件驗證成功',
        ]);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
        $this->middleware('throttle:6,1')->only('verify', 'send');
    }
}
