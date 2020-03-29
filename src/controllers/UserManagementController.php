<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 4/25/2019
 * Time: 9:28 PM
 */

namespace ersaazis\usermanagement\controllers;

use ersaazis\cb\exceptions\CBValidationException;
use ersaazis\cb\helpers\ModuleGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends \ersaazis\cb\controllers\Controller
{

    private $view = "usermanagement::users";

    public function __construct()
    {
        view()->share(['page_title'=>'User Manajement']);
    }

    private function myPrivileges(){
        if(cb()->session()->id()){
            $menu = cb()->find("cb_menus",[
                "type"=>'path',
                "name"=>'User Manajement'
            ]);
            $privilege = cb()->find("cb_role_privileges",[
                "cb_menus_id"=>$menu->id,
                "cb_roles_id"=>cb()->session()->roleId()
            ]);
            return $privilege;    
        }
        $privilege=[];
        $privilege->can_browse=false;
        $privilege->can_create=false;
        $privilege->can_read=false;
        $privilege->can_update=false;
        $privilege->can_delete=false;
        return false;
    }

    public function getIndex() {
        if(!$this->myPrivileges()->can_browse) return cb()->redirect(cb()->getAdminUrl(),cbLang("you_dont_have_privilege_to_this_area"));
        $data = [];
        $data['result'] = DB::table("users")
            ->join("cb_roles","cb_roles.id","=","cb_roles_id")
            ->select("users.*","cb_roles.name as cb_roles_name")
            ->get();
        return view($this->view.'.index',$data);
    }

    public function getAdd() {
        if(!$this->myPrivileges()->can_create) return cb()->redirect(cb()->getAdminUrl(),cbLang("you_dont_have_privilege_to_this_area"));
        $data = [];
        $data['roles'] = DB::table("cb_roles")->get();
        return view($this->view.'.add', $data);
    }

    public function postAddSave() {
        if(!$this->myPrivileges()->can_create) return cb()->redirect(cb()->getAdminUrl(),cbLang("you_dont_have_privilege_to_this_area"));
        try {
            cb()->validation(['name', 'email','password','cb_roles_id']);

            $user = [];
            $user['name'] = request('name');
            $user['email'] = request('email');
            $user['password'] = Hash::make(request('password'));
            $user['cb_roles_id'] = request('cb_roles_id');
            DB::table('users')->insert($user);

            return cb()->redirect(route("UserManagementControllerGetIndex"),"New user has been created!","success");

        } catch (CBValidationException $e) {
            return cb()->redirectBack($e->getMessage());
        }
    }

    public function getEdit($id) {
        if(!$this->myPrivileges()->can_update) return cb()->redirect(cb()->getAdminUrl(),cbLang("you_dont_have_privilege_to_this_area"));
        $data = [];
        $data['row'] = cb()->find("users", $id);
        $data['roles'] = DB::table("cb_roles")->get();
        return view($this->view.".edit", $data);
    }

    public function postEditSave($id) {
        if(!$this->myPrivileges()->can_update) return cb()->redirect(cb()->getAdminUrl(),cbLang("you_dont_have_privilege_to_this_area"));
        try {
            cb()->validation(['name', 'email','cb_roles_id']);

            $user = [];
            $user['name'] = request('name');
            $user['email'] = request('email');
            if(request('password')) $user['password'] = Hash::make(request('password'));
            $user['cb_roles_id'] = request('cb_roles_id');
            DB::table('users')->where('id',$id)->update($user);

            return cb()->redirect(route("UserManagementControllerGetIndex"),"The user has been updated!","success");

        } catch (CBValidationException $e) {
            return cb()->redirectBack($e->getMessage());
        }
    }

    public function getDelete($id) {
        if(!$this->myPrivileges()->can_delete) return cb()->redirect(cb()->getAdminUrl(),cbLang("you_dont_have_privilege_to_this_area"));
        DB::table("users")->where("id",$id)->delete();
        return cb()->redirectBack("The user has been deleted!","success");
    }

}