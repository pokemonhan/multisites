<?php

namespace App\Http\Controllers\FrontendApi;

use App\Models\Admin\BackendAdminAccessGroup;
use App\Models\Admin\Fund\BackendAdminRechargePocessAmount;
use App\Models\Admin\SystemConfiguration;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class FrontendAuthController extends FrontendApiMainController
{
    use AuthenticatesUsers;

    public $successStatus = 200;

    protected $eloqM = 'User\FrontendUser';

    public function username()
    {
        return 'username';
    }

    /**
     * Login user and create token
     *
     * @param  Request  $request
     * @return JsonResponse [string] access_token
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|string|alpha_dash',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);
        $credentials = request(['username', 'password']);
        $this->maxAttempts = 1; //1 times
        $this->decayMinutes = 1; //1 minutes
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $seconds = $this->limiter()->availableIn(
                $this->throttleKey($request)
            );
            return $this->msgOut(false, [], '100005');
        }
        if (!$token = $this->currentAuth->attempt($credentials)) {
            return $this->msgOut(false, [], '100002');
        }
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        $expireInMinute = $this->currentAuth->factory()->getTTL();
        $expireAt = Carbon::now()->addMinutes($expireInMinute)->format('Y-m-d H:i:s');
        $user = $this->currentAuth->user();
        if ($user->remember_token !== null) {
            try {
                JWTAuth::setToken($user->remember_token);
                JWTAuth::invalidate();
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }
        }
        $user->remember_token = $token;
        $user->last_login_ip = request()->ip();
        $user->last_login_time = Carbon::now()->timestamp;
        $user->save();
        $data = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => $expireAt,
        ];
        return $this->msgOut(true, $data);
    }

    public function userDetail()
    {
        $user = $this->currentAuth->user();
        $account = $user->account;
        $balance = $account->balance;
        $frozen = $account->frozen;
        $data = [
            'user_id' => $user->id,
            'username' => $user->username,
            'prize_group' => $user->prize_group,
            'user_type' => $user->type,
            'is_tester' => $user->is_tester,
            'last_login_time' => $user->last_login_time->toDateTimeString(),
            'levels' => $user->levels,
            'can_withdraw' => $user->frozen_type <= 0, //$user->frozen_type > 0 ? false : true
            'today_withdraw' => 0, //
            'daysalary_percentage' => 0,
            'bonus_percentage' => 0,
            'allowed_transfer' => true,
            'balance' => sprintf('%1.4f', substr($balance, 0, strrpos($balance, '.', 0) + 1 + 4)),
            'frozen_balance' => sprintf('%1.4f', substr($frozen, 0, strrpos($frozen, '.', 0) + 1 + 4)),
            'has_funds_password' => $user->fund_password ? true : false,
            'download_url' => SystemConfiguration::getConfigValue('app_download_url') . '/' . $user->invite_code,
            'version' => SystemConfiguration::getConfigValue('app_version'),
        ];
        return $this->msgOut(true, $data);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        $token = $this->currentAuth->refresh();
        return $token;
    }

    /**
     * change partner user Password
     * @return JsonResponse
     */
    public function selfResetPassword()
    {
        $validator = Validator::make($this->inputs, [
            'old_password' => 'required|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        if (!Hash::check($this->inputs['old_password'], $this->partnerAdmin->password)) {
            return $this->msgOut(false, [], '100003');
        } else {
            $token = $this->refresh();
            $this->partnerAdmin->password = Hash::make($this->inputs['password']);
            $this->partnerAdmin->remember_token = $token;
            try {
                $this->partnerAdmin->save();
                $expireInMinute = $this->currentAuth->factory()->getTTL();
                $expireAt = Carbon::now()->addMinutes($expireInMinute)->format('Y-m-d H:i:s');
                $data = [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_at' => $expireAt,
                ];
                return $this->msgOut(true, $data);
            } catch (\Exception $e) {
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        }

    }

    /**
     * Register api
     * @return JsonResponse
     */
    public function register(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'name' => 'required|unique:backend_admin_users',
            'email' => 'required|email|unique:backend_admin_users',
            'password' => 'required',
            'is_test' => 'required|numeric',
            'group_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $group = BackendAdminAccessGroup::find($this->inputs['group_id']);
        $role = $group->role == '*' ? Arr::wrap($group->role) : Arr::wrap(json_decode($group->role, true));
        $isManualRecharge = false;
        $FundOperation = PartnerMenus::select('id')->where('route', '/manage/recharge')->first()->toArray();
        $isManualRecharge = in_array($FundOperation['id'], $role, true);
        $input = $this->inputs;
        $input['password'] = bcrypt($input['password']);
        $input['platform_id'] = $this->currentPlatformEloq->platform_id;
        $user = BackendAdminUser::create($input);
        if ($isManualRecharge === true) {
            $insertData = ['admin_id' => $user->id];
            $FundOperationEloq = new BackendAdminRechargePocessAmount();
            $FundOperationEloq->fill($insertData);
            $FundOperationEloq->save();
        }
        $credentials = request(['email', 'password']);
        $token = $this->currentAuth->attempt($credentials);
        $success['token'] = $token;
        $success['name'] = $user->name;
        return $this->msgOut(true, $success);
    }

    /**
     * 获取所有当前平台的商户管理员用户
     * @return JsonResponse
     */
    public function allUser():  ? JsonResponse
    {
        try {
            $data = $this->currentPlatformEloq->partnerAdminUsers;
            if (is_null($data)) {
                $result = Arr::wrap($data);
            } else {
                $result = $data->toArray();
            }
            return $this->msgOut(true, $result);
        } catch (\Exception $e) {
            return $this->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }

    /**
     * @return JsonResponse|null
     */
    public function updateUserGroup() :  ? JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'group_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $targetUserEloq = $this->eloqM::find($this->inputs['id']);
        if ($targetUserEloq !== null) {
            $targetUserEloq->group_id = $this->inputs['group_id'];
            if ($targetUserEloq->save()) {
                $result = $targetUserEloq->toArray();
                return $this->msgOut(true, $result);
            }
        }
    }

    /**
     * Logout user (Revoke the token)
     * @param  Request  $request
     * @return JsonResponse [string] message
     */
    public function logout(Request $request) : JsonResponse
    {
        $throtleKey = Str::lower($this->username() . '|' . $request->ip());
        $request->session()->invalidate();
        $this->limiter()->clear($throtleKey);
        $this->currentAuth->logout();
        $this->currentAuth->invalidate();
        return $this->msgOut(true, []); //'Successfully logged out'
    }

    /**
     * Get the authenticated User
     *
     * @param  Request  $request
     * @return JsonResponse [json] user object
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    public function deletePartnerAdmin():  ? JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }

        $targetUserEloq = $this->eloqM::where([
            ['id', '=', $this->inputs['id']],
            ['name', '=', $this->inputs['name']],
        ])->first();
        if ($targetUserEloq !== null) {
            $token = $targetUserEloq->token();
            if ($token) {
                $token->revoke(); //取消目前登录中的状态
            }
//            OauthAccessTokens::clearOldToken($targetUserEloq->id); //删除相关登录的token
            if ($targetUserEloq->delete()) {
//删除用户
                return $this->msgOut(true);
            }
        } else {
            return $this->msgOut(false, [], '100004');
        }
    }

    public function updatePAdmPassword() :  ? JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $targetUserEloq = $this->eloqM::where([
            ['id', '=', $this->inputs['id']],
            ['name', '=', $this->inputs['name']],
        ])->first();
        if ($targetUserEloq !== null) {
            $targetUserEloq->password = Hash::make($this->inputs['password']);
            if ($targetUserEloq->save()) {
//用户更新密码
                return $this->msgOut(true);
            }
        } else {
            return $this->msgOut(false, [], '100004');
        }
    }
}
