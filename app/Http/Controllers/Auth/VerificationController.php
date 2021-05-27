<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            if ($request->user()->hasVerifiedEmail()) {
                return $request->wantsJson()
                    ? new JsonResponse([], 204)
                    : redirect($this->redirectPath());
            }

            $this->validate($request, [
                'email' => ['required', 'string', 'email', 'max:255']
            ]);

            $email = $request->post('email');
            $EmailVerified =  User::query()->EmailVerified($email);

            if ($EmailVerified) {
                return response()->json([
                    'status' => 0,
                    'message' => "寄送失敗，已有玩家使用驗證該郵件"
                ], 400);
            }

            $request->user()->forceFill([
                'email' => $email
            ])->save();

            $request->user()->sendEmailVerificationNotification();

            return $request->wantsJson()
                ? new JsonResponse([], 202)
                : back()->with('resent', true);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => "寄送失敗，具有無效的格式"
            ], 400);
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}
