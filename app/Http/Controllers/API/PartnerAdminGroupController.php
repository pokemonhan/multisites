<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use App\models\PartnerMenus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PartnerAdminGroupController extends ApiMainController
{
    protected $postUnaccess = ['id', 'updated_at', 'created_at'];//不需要接收的字段

    protected $eloqM = 'PartnerAdminGroupAccess';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = $this->eloqM::all()->toArray();
        return $this->msgout(true, $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $validator = Validator::make($this->inputs, [
            'group_name' => 'required|unique:partner_access_group',
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors()->first(), 200);
        }
//        unique:books,label
        $data['platform_id'] = $this->currentPlatformEloq->platform_id;
        $role = json_decode($data['role']); //[1,2,3,4,5]
        $objPartnerAdminGroup = new $this->eloqM;
        $objPartnerAdminGroup->fill($data);
        try {
            $objPartnerAdminGroup->save();
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgout(false, [], $msg, $sqlState);
        }
        $partnerAccessGroupEloq = PartnerMenus::whereIn('id', $role)->get();
        $partnerMenuObj = new PartnerMenus();
        $partnerMenuObj->createMenuDatas($partnerAccessGroupEloq, $objPartnerAdminGroup->id);
        $objPartnerAdminGroup->save();
        return $this->msgout(true, $data);
    }

    protected function accessOnlyColumn()
    {
        $partnerAdminAccess = new $this->eloqM();
        $column = $partnerAdminAccess->getTableColumns();
        $column = array_values(array_diff($column, $this->postUnaccess));
        return $column;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'group_name' => 'required',
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors(), 401);
        }
        $id = $request->get('id');
        $datas = $this->eloqM::find($id);
        if (!is_null($datas)) {
            $datas->group_name = $request->get('group_name');
            $datas->role = $request->get('role');
            $datas->save();
            $data = $datas->toArray();
            return $this->msgout(true, $data);
        } else {
            return $this->msgout(false, [], '没有此组可编辑', '0001');
        }
    }

    /**
     * 删除组管理员角色
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        $validator = Validator::make($this->inputs,[
            'id' => 'required|numeric',
            'group_name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors(), 401);
        }
        $id = $this->inputs['id'];
        $datas = $this->eloqM::where([
            ['id', '=', $id],
            ['group_name', '=', $this->inputs['group_name']],
        ])->first();
        if (!is_null($datas)) {
            try {
                $datas->delete();
                return $this->msgout(true, []);
            } catch (\Exception $e) {
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
                return $this->msgout(false, [], $msg, $sqlState);
            }
        } else {
            return $this->msgout(false, [], '没有此组可删除', '0001');
        }
    }

    public function specificGroupUsers()
    {
        $validator = Validator::make($this->inputs,[
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors(), 401);
        }
        $accessGroupEloq = $this->eloqM::find($this->inputs['id']);
        if (!is_null($accessGroupEloq)) {
            $data = $accessGroupEloq->adminUsers->toArray();
            return $this->msgout(true, $data);
        } else {
            return $this->msgout(false, [], '没有此组', '0001');
        }
    }

}
