<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Validator;
use Response;
use Illuminate\Support\Facades\Hash;
use Form;

class UserController extends Controller
{
    protected $user;

    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next)  {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataUsers(Request $request)
    {
        $columns = array(
                            0  => 'name',
                            1  => 'username',
                            2  => 'role',
                            3  => 'status',
                            4  => 'btnOther',
                        );

        $totalData = User::Where('co_id', $this->user->co_id)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $users = User::Where('co_id', $this->user->co_id)
                                 ->offset($start)
                                 ->limit($limit)
                                 ->orderBy($order,$dir)
                                 ->get();
        }
        else {
            $search = $request->input('search.value');

            $users = User::Where('co_id', $this->user->co_id)
                                 ->where(function ($query) use ($search) {
                                            $query->where('name', 'LIKE', "%{$search}%")
                                                  ->orWhere('username', 'LIKE', "%{$search}%");
                                        })
                                 ->offset($start)
                                 ->limit($limit)
                                 ->orderBy($order,$dir)
                                 ->get();

            $totalFiltered = User::Where('co_id', $this->user->co_id)
                                 ->where(function ($query) use ($search) {
                                             $query->where('name', 'LIKE', "%{$search}%")
                                                   ->orWhere('username', 'LIKE', "%{$search}%");
                                         })
                                 ->count();
        }

        $data = array();
        if(!empty($users))
        {
            foreach ($users as $user)
            {
                // $show =  route('audits.show',$user->id);
                // $edit =  route('audits.edit',$user->id);
                $show =  "";
                $edit =  "";

                $nestedData['name']        = $user->name;
                $nestedData['username']    = $user->username;
                $nestedData['role']        = ((count($user->getRoleNames())>0)?$user->getRoleNames():'-');
                // $nestedData['role']        = '';

                if ($user->active == "Y"){
                    $klsBaris = "";
                    $stat = "<span class='btn btn-success btn-sm'>Active</span>";
                } elseif ($user->active == "N") {
                    $klsBaris = "alert-warning";
                    $stat = "<span class='btn btn-warning btn-sm'>Not Active</span>";
                }
                $nestedData['status']       = $stat;
                $nestedData['klsBaris']     = $klsBaris;


                $btnDel = Form::open(['route' => ['user.destroy', $user->id],
                                    'method' => 'DELETE', 'target' => '',
                                    'name' => 'formDelete',
                                    'id' => 'formDelete']).
                                    '<button type="button" name="edit" data-val='.$user->id.' class="btn btn-outline-primary btn-xs btnEdit" title="Ubah Data"><i class="fas fa-pencil-alt"></i></button>&nbsp;|&nbsp;'.
                          Form::button('<i class="fas fa-trash-alt"></i>', ['class'=>'btn btn-outline-danger btn-xs', 'type'=>'submit', 'title' => 'Hapus Data', 'data-toggle' => 'tooltip', 'data-placement' => 'top']).
                          Form::close();

                $btnOther = '<td align="center">
                                '.$btnDel.'
                            </td>';

                $nestedData['btnOther']  = $btnOther;

                // $nestedData['options'] = "&emsp;<a href='{$show}' title='SHOW' ><span class='glyphicon glyphicon-list'></span></a>
                //                           &emsp;<a href='{$edit}' title='EDIT' ><span class='glyphicon glyphicon-edit'></span></a>";
                $data[] = $nestedData;

            }
        }

        $json_data = array(
                    "draw"            => intval($request->input('draw')),
                    "recordsTotal"    => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data"            => $data
                    );

        echo json_encode($json_data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $role         = Role::all();
        // $permission   = Permission::all();
        //
        // $this->user->assignRole('admin');
        // // $permission->assignRole($role);
        //
        // dd($role, $permission);

        return view('pages.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();

        $arrRole = array();
        foreach ($roles as $role) {
            $arrRole[$role->name] = $role->name;
        }

        return view('pages.user.create', ['roles' => $arrRole]);
    }

    /**
     * Get a validatorStore for an incoming Store request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatorStore(array $data)
    {
        $roles = Role::all();

        $inRole = '';
        foreach ($roles as $role) {
            if (!empty($inRole)) {
                $inRole .= ',';
            }
            $inRole .= $role->name;
        }
        // dd($inRole);

        return Validator::make($data, [
            'co_id'       => 'required|integer',
            'nama'        => "required|min:3|string|regex:/(^([a-zA-Z0-9 ]+)(\d+\s\w+)?$)/u",
            'username'    => "required|min:3|string|regex:/(^([a-zA-Z0-9 ]+)(\d+\s\w+)?$)/u",
            'password'    => "required|min:3|string|regex:/(^([a-zA-Z0-9]+)(\d+\s\w+)?$)/u",
            'role'        => "required|in:$inRole",
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validatorStore($request->all());

        // $msg = array();
        $msg = "";
        if ($validator->fails()) {

            $MessagesBag = $validator->getMessageBag()->toArray();

            $msg .="<ol>";
            foreach ($MessagesBag as $keyMessage => $valueMessage) {
                foreach ($valueMessage as $key => $value) {
                    $msg .= "<li>".$value."</li>";
                }
            }
            $msg .="</ol>";

        } else {
            try {
                DB::beginTransaction();

                $user = new User;

                $user->co_id         = $request->input('co_id');
                $user->name         = $request->input('nama');
                $user->username     = $request->input('username');
                $user->password     = Hash::make($request->input('password'));
                // $user->role         = $request->input('role');
                $user->active       = 'Y';
                // dd($client);

                if ($user->save()) {
                    $user->assignRole($request->input('role'));
                }

                DB::commit();

                toastr('Berhasil Menambah Data', 'success', 'Sukses Info :');
                $msg = "success";

            } catch (\Illuminate\Database\QueryException $ex) {
                DB::rollback();
                // dd($ex->getMessage());
                $data = array('msg'=>$ex->getMessage());
                return json_encode($data);
            } catch (ValidationException $ex) {
                DB::rollback();
                // dd($ex);
                $data = array('msg'=>$ex->getMessage());
                return json_encode($data);
            } catch (\Exception $ex) {
                DB::rollback();
                // dd($ex);
                $data = array('msg'=>$ex->getMessage());
                return json_encode($data);
            }

        }

        $data = array('msg'=>$msg);
        return json_encode($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $userRole = $user->getRoleNames();

        $inRoles = Role::all();

        $roles = array();
        foreach ($inRoles as $inRole) {
            $roles[$inRole->name] = $inRole->name;
        }

        return view('pages.user.edit', compact('user', 'roles','userRole'));
    }

    /**
     * Get a validatorActivity for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatorUpdate(array $data)
    {
        $roles = Role::all();

        $inRole = '';
        foreach ($roles as $role) {
            if (!empty($inRole)) {
                $inRole .= ',';
            }
            $inRole .= $role->name;
        }
        // dd($inRole);

        return Validator::make($data, [
            'co_id'       => 'required|integer',
            'nama'        => "required|min:3|string|regex:/(^([a-zA-Z0-9 ]+)(\d+\s\w+)?$)/u",
            'username'    => "required|min:3|string|regex:/(^([a-zA-Z0-9 ]+)(\d+\s\w+)?$)/u",
            'password'    => "nullable|min:3|string|regex:/(^([a-zA-Z0-9]+)(\d+\s\w+)?$)/u",
            'role'        => "required|in:$inRole",
            'status'      => 'required|in:Y,N',
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = $this->validatorUpdate($request->all());

        // $msg = array();
        $msg = "";
        if ($validator->fails()) {

            $MessagesBag = $validator->getMessageBag()->toArray();

            $msg .="<ol>";
            foreach ($MessagesBag as $keyMessage => $valueMessage) {
                // dd($MessagesBag);
                foreach ($valueMessage as $key => $value) {
                    $msg .= "<li>".$value."</li>";
                    // $msg[$keyMessage] = $value;
                    // toastr($value, 'error', 'Update Failed : ');
                }
            }
            $msg .="</ol>";

        } else {

            try {
                DB::beginTransaction();

                $user->co_id        = $request->input('co_id');
                $user->name         = $request->input('nama');
                $user->username     = $request->input('username');
                if (!is_null($request->input('password'))) {
                    $user->password = Hash::make($request->input('password'));
                }
                // $user->role         = $request->input('role');
                $user->active       = $request->input('status');

                if($user->update()){
                    $user->syncRoles($request->input('role'));

                    DB::commit();

                    toastr('Berhasil Merubah Data', 'success', 'Sukses Info :');

                    $msg = "success";
                }

            } catch (\Illuminate\Database\QueryException $ex) {
                DB::rollback();
                // dd($ex->getMessage());
                $data = array('msg'=>$ex->getMessage());
        	    return json_encode($data);
                // toastr($ex->getMessage(), 'error', 'Terjadi Kesalahan Sistem');
                // return redirect()->intended('/form')->withInput();
            } catch (ValidationException $ex) {
                DB::rollback();
                // dd($ex);
                $data = array('msg'=>$ex->getMessage());
        	    return json_encode($data);
                // toastr($ex->getMessage(), 'error', 'Terjadi Kesalahan Sistem');
                // return redirect()->intended('/form')->withInput();
            } catch (\Exception $ex) {
                DB::rollback();
                // dd($ex);
                $data = array('msg'=>$ex->getMessage());
        	    return json_encode($data);
                // toastr($ex->getMessage(), 'error', 'Terjadi Kesalahan Sistem');
                // return redirect()->intended('/form')->withInput();
            }

        }

        $data = array('msg'=>$msg);
	    return json_encode($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();

            $msg = "";
            if ($user->delete()) {
                DB::commit();
                toastr('Berhasil Menghapus Data', 'success', 'Delete Success :');
                $msg = "success";
            } else {
                $msg .= "Data Member gagal dihapus !!!";
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            // dd($ex->getMessage());
            $err = "";
            if ($ex->errorInfo[1] == 1451) {
                $err = "Data tidak dapat dihapus, karena sedang digunakan pada menu lain.";
            }else {
                $err = $ex->getMessage();
            }
            $data = array('msg'=>$err);
            return json_encode($data);
        } catch (ValidationException $ex) {
            DB::rollback();
            // dd($ex);
            $data = array('msg'=>$ex->getMessage());
            return json_encode($data);
        } catch (\Exception $ex) {
            DB::rollback();
            // dd($ex);
            $data = array('msg'=>$ex->getMessage());
            return json_encode($data);
        }

        $data = array('msg'=>$msg);
	    return json_encode($data);
    }
}
