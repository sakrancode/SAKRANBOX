<?php

namespace App\Http\Controllers;

use App\Models\Document;

use Illuminate\Http\Request;
use Form;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
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
    public function getDataDocument(Request $request)
    {
        $columns = array(
                            0  => 'name',
                            1  => 'updated_at',
                            12  => 'btnOther',
                        );

        $totalData = Document::Where([['co_id', $this->user->co_id], ['level', 1]])
                              ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $documents = Document::Where([['co_id', $this->user->co_id], ['level', 1]])
                                 ->offset($start)
                                 ->limit($limit)
                                 ->orderBy('type', 'ASC')
                                 ->orderBy($order,$dir)
                                 ->get();
        }
        else {
            $search = $request->input('search.value');

            $documents = Document::Where([['co_id', $this->user->co_id], ['level', 1]])
                                 ->where(function ($query) use ($search) {
                                            $query->where('name', 'LIKE', "%{$search}%");
                                        })
                                 ->offset($start)
                                 ->limit($limit)
                                 ->orderBy('type', 'ASC')
                                 ->orderBy($order,$dir)
                                 ->get();

            $totalFiltered = Document::Where([['co_id', $this->user->co_id], ['level', 1]])
                                 ->where(function ($query) use ($search) {
                                             $query->where('name', 'LIKE', "%{$search}%");
                                         })
                                 ->count();
        }

        $data = array();
        if(!empty($documents))
        {
            $grand_trx = 0;
            foreach ($documents as $document)
            {
                // $show =  route('audits.show',$document->id);
                // $edit =  route('audits.edit',$document->id);
                $show =  "";
                $edit =  "";

                $icon = '';
                if ($document->type=='FOLDER') {
                    $icon = '<i class="far fa-fw fa-folder"></i>';
                } else {
                    $file = explode('.', $document->storage);

                    if ($file[1]=='ppt') {
                        $icon = '<i class="far fa-file-powerpoint"></i>';
                    } elseif ($file[1]=='doc' || $file[1]=='docx') {
                        $icon = '<i class="far fa-file-word"></i>';
                    } elseif ($file[1]=='xls' || $file[1]=='xlsx') {
                        $icon = '<i class="far fa-file-excel"></i>';
                    } elseif ($file[1]=='pdf') {
                        $icon = '<i class="far fa-file-pdf"></i>';
                    } elseif ($file[1]=='jpg' || $file[1]=='jpeg' || $file[1]=='png' || $file[1]=='gif') {
                        $icon = '<i class="far fa-file-image"></i>';
                    }
                    // dd($file,$icon);

                }
                if ($document->type=='FOLDER') {
                    $nestedData['name']        = '<a href="'.url('document/'.$document->id.'/subFolder').'">'.$icon.' '.$document->name.'</a>';
                } else {
                    $nestedData['name']        = $icon.' '.$document->name;
                }
                $nestedData['updated_at']  = date('d-M-Y', strtotime($document->updated_at));

                // if ($document->status == "Y"){
                //     $klsBaris = "";
                //     $stat = "<span class='btn btn-success btn-sm'>APPROVE</span>";
                // } elseif ($document->status == "R") {
                //         $klsBaris = "";
                //         $stat = "<span class='btn btn-success btn-sm'>RECIEVE</span>";
                // }
                $nestedData['status']       = '';
                $nestedData['klsBaris']     = '';

                    $btnDel = Form::open(['route' => ['document.destroy', $document->id],
                                        'method' => 'DELETE', 'target' => '',
                                        'name' => 'formDelete',
                                        'id' => 'formDelete']).
                              Form::button('<i class="fas fa-trash-alt"></i> Delete', ['class'=>'dropdown-item', 'type'=>'submit', 'title' => 'Hapus Data', 'data-toggle' => 'tooltip', 'data-placement' => 'top']).
                              Form::close();

                // if ($document->status != "N") {
                    // <a href="#" class="btn btn-outline-secondary btn-xs" target="_blank" title="Share">
                    //     <i class="fas fa-share-alt-square"></i>
                    // </a>
                    // <a href="#" class="btn btn-outline-secondary btn-xs" target="_blank" title="Copy Link">
                    //     <i class="fas fa-link"></i>
                    // </a>
                    $btnOther = '<td align="center">
                                    <div class="btn-group dropleft">
                                        <button type="button" class="btn btn-outline-secondary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="More">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                        <a class="dropdown-item" href="'.url('document/download/'.$document->id).'">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                        <button type="button" name="edit" data-val="'.$document->id.'" data-type="'.$document->type.'" data-co="'.$document->co_id.'" class="dropdown-item btnMove" title="Move">
                                            <i class="fas fa-arrows-alt"></i> Move
                                        </a>
                                        <button type="button" name="edit" data-val="'.$document->id.'" data-type="'.$document->type.'" data-co="'.$document->co_id.'" class="dropdown-item btnCopy" title="Copy">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <button type="button" name="edit" data-val="'.$document->id.'" data-type="'.$document->type.'" class="dropdown-item btnEdit" title="Edit">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </button>
                                        '.$btnDel.'
                                        </div>
                                    </div>
                                </td>';
                // } else {
                //     $btnOther = '<td align="center">
                //                     <button type="button" class="btn btn-default btn-sm" title="Disable">
                //                         <i class="fas fa-keyboard"></i>
                //                     </button>
                //                 </td>';
                // }

                // $nestedData['btnEdit']   = $btnEdit;
                // $nestedData['btnDetail'] = $btnDetail;
                // $nestedData['btnProses'] = $btnProses;
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
        $documents = Document::Where([['co_id', $this->user->co_id], ['level', 1]])->get();

        return view('pages.dashboard.index', compact('documents'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function document()
    {        
        return view('pages.document.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function show(Dashboard $dashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function edit(Dashboard $dashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dashboard $dashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dashboard $dashboard)
    {
        //
    }
}
