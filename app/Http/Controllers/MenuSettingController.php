<?php

namespace App\Http\Controllers;

use App\Menus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class MenuSettingController extends AdminMainController
{

    public function index()
    {
        $firstlevelmenus = Menus::getFirstLevelList();
        $routeCollection = Route::getRoutes()->get();
        $editMenu = Menus::all();
        $rname = [];
        foreach ($routeCollection as $key => $r) {
            if (isset($r->action['as'])) {
                if ($r->action['prefix'] !== '_debugbar') {
                    $rname[$r->action['as']] = $r->uri;
                }
            }
        }
        return view('superadmin.menu-setting.index', ['firstlevelmenus' => $firstlevelmenus, 'rname' => $rname, 'editMenu' => $editMenu]);
    }

    public function add()
    {
        $menuEloq = new Menus();
        if (isset($this->inputs['isParent']) && $this->inputs['isParent'] === 'on') {

            $menuEloq->label = $this->inputs['menulabel'];
        } else {
            $menuEloq->label = $this->inputs['menulabel'];
            $menuEloq->route = $this->inputs['route'];
            $menuEloq->pid = $this->inputs['parentid'];
        }
        if ($menuEloq->save()) {
            $menuEloq->refreshStar();
            return response()->json(['success' => true, 'menucreated' => 1]);
        } else {
            return response()->json(['success' => false, 'menucreated' => 0]);
        }
    }

    public function delete()
    {
        $menuEloq = new Menus();
        $toDelete = json_decode($this->inputs['toDelete'], true);
        if (!empty($toDelete)) {

            try {
                $menuEloq->find($toDelete)->each(function ($product, $key) {
                    $product->delete();
                });
                $menuEloq->refreshStar();
                return response()->json(['success' => true, 'menudeleted' => 1]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'menudeleted' => 0]);
            }
        }


    }
}