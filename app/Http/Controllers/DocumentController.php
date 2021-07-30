<?php

namespace App\Http\Controllers;

use App\Models\Document;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Validator;
use Response;
use Form;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class DocumentController extends Controller
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

    public function getStorageFolderLink($id)
    {
        $subFolder = '';
        $document = Document::find($id);
        // dd($document,$id);

        if ($document->level > 1) {
            $parent = Document::find($document->parent_id);
            $subFolder .= $this->getStorageFolderLink($parent->id).'/'.$document->storage;
        } else {
            $subFolder .= $document->storage.$subFolder;
        }

        return $subFolder;
    }

    public function getBreadCrumb($id, $active = false, $count = 0)
    {
        $breadcrumb = '';
        $document = Document::find($id);
        // dd($document);

        if ($document->level > 1) {
            // $parent = Document::find($document->parent_id);
            $count++;
            if ($active) {
                $breadcrumb .= $this->getBreadCrumb($document->parent_id, false, $count).'<li class="breadcrumb-item active">'.$document->name.'</li>';
            }else {
                if ($count > 2) {
                    $breadcrumb .= $this->getBreadCrumb($document->parent_id, false, $count);
                } else {
                    $breadcrumb .= $this->getBreadCrumb($document->parent_id, false, $count).'<li class="breadcrumb-item"><a href="'.url('document/'.$document->id.'/subFolder').'">'.$document->name.'</a></li>';
                }
            }
        } else {
            if ($count > 2) {
                $breadcrumb .= '<li class="breadcrumb-item"><a href="'.url('document/'.$document->id.'/subFolder').'">'.$document->name.'<a/></li><li class="breadcrumb-item">...</li>'.$breadcrumb;
            } else {
                $breadcrumb .= '<li class="breadcrumb-item"><a href="'.url('document/'.$document->id.'/subFolder').'">'.$document->name.'<a/></li>'.$breadcrumb;
            }
        }

        return $breadcrumb;
    }

    public function getJtreeData($id){

        $curr_doc = Document::find($id);

        $documents = Document::where([['id', '!=', $curr_doc->id], ['co_id', $curr_doc->co_id], ['type', 'FOLDER']])->get();

        $folders_arr = array();

        foreach ($documents as $document) {

            $subFolder = $document->parent_id;

            if(is_null($subFolder)) $subFolder = "#";

            // $selected = false;$opened = false;

            // if($row['id'] == 2){
            //     $selected = true;$opened = true;
            // }

            $folders_arr[] = [
                                "id" => $document->id,
                                "parent" => $subFolder,
                                "text" => $document->name
                                // "state" => array("selected" => $selected,"opened"=>$opened)
                             ];
        }
        // dd($folders_arr);
        return $folders_arr;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function subFolder(Document $document)
    {
        // dd($this->getBreadCrumb($document->id, true));
        $breadcrumb = $this->getBreadCrumb($document->id, true);
        if ($document->type=='FOLDER') {
            return view('pages.document.sub', compact('document', 'breadcrumb'));
        } else {
            return redirect()->route('home');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('masuk');
        return redirect('/home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createFile($parent_id = NULL)
    {
        $parent = NULL;
        if ($parent_id) {
            $parent = Document::find($parent_id);
        }
        // dd($parent, $parent_id);

        return view('pages.document.createFile', compact('parent'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createFolder($parent_id = NULL)
    {
        $parent = NULL;
        if ($parent_id) {
            $parent = Document::find($parent_id);
        }

        return view('pages.document.createFolder', compact('parent'));
    }

    /**
     * Get a validatorFiles for an incoming upload file request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatorFiles(array $data)
    {
        Validator::extend('valid_file', function($attribute, $value, $parameters)
        {
            $allowed_mimes = [
                    'image/jpeg', // jpeg
                    'image/jpg', // jpg
                    'image/png', // png
                    'application/octet-stream', // txt etc
                    'application/msword', // doc
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document', //docx
                    'application/vnd.ms-excel', // xls
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // xlsx
                    'application/pdf', // pdf
                ];

            return in_array($value->getMimeType(), $allowed_mimes);
        });

        return Validator::make($data, [
            'co_id'         => 'nullable|integer',
            'parent_id'     => 'nullable|integer',
            'nama_file'     => "min:3|max:100|string|regex:/(^([a-zA-Z0-9 ]+)(\d+\s\w+)?$)/u",
            'keterangan'    => "min:1|max:250|string|regex:/(^([a-zA-Z0-9 ,.:'-=]+)(\d+\s\w+)?$)/m",
            'file_name'     => 'required|file|valid_file|max:5120',
            // 'file_name.*'  => ['nullable', 'file', $allowed_mimes, 'max:2048'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFile(Request $request)
    {
        // dd($request);
        $validator = $this->validatorFiles($request->all());

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

                if ($request->hasFile('file_name')) {
                    #jika ada file yang diupload
                    $fileName = strtotime("now").".".$request['file_name']->getClientOriginalExtension();

                    if ($request->parent_id) {
                        $parent = Document::find($request->parent_id);
                    }

                    $document = new Document;

                    $document->co_id       = $request->input('co_id');
                    $document->name        = $request->input('nama_file');
                    $document->description = $request->input('keterangan');
                    $document->storage     = $fileName;
                    $document->type        = 'FILE';

                    if ($request->parent_id) {
                        $document->parent_id = $parent->id;
                        $document->level     = $parent->level+1;
                    }

                    if ($document->save()) {
                        #kondisi untuk rekursive sub folder belum dibikin, sementara hanya 2 level
                        if ($request->parent_id) {
                            $storage = $this->getStorageFolderLink($parent->id);
                            // dd($storage);
                            $request['file_name']->storeAs('public/'.$this->user->co_id.'/'.$storage.'/',$fileName);
                        } else {
                            $request['file_name']->storeAs('public/'.$this->user->co_id.'/',$fileName);
                        }
                    }

                    DB::commit();
                    toastr('Berhasil Mengupload File', 'success', 'Sukses Info :');
                    $msg = "success";
                } else {
                    DB::rollback();
                    toastr('Berkas File tidak ditemukan !!!', 'Warning', 'Warning Info :');
                    $msg = "warning";
                }

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
     * Get a validatorFolders for an incoming upload file request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatorFolders(array $data)
    {
        return Validator::make($data, [
            'co_id'         => 'nullable|integer',
            'parent_id'     => 'nullable|integer',
            'nama_folder'   => "min:3|max:100|string|regex:/(^([a-zA-Z0-9 ]+)(\d+\s\w+)?$)/u",
            'keterangan'    => "min:1|max:250|string|regex:/(^([a-zA-Z0-9 ,.:'-=]+)(\d+\s\w+)?$)/m",
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFolder(Request $request)
    {
        // dd($request);
        $validator = $this->validatorFolders($request->all());

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

                #jika ada file yang diupload
                $folderName = strtotime("now");

                if ($request->parent_id) {
                    $parent = Document::find($request->parent_id);
                }

                $document = new Document;

                $document->co_id       = $request->input('co_id');
                $document->name        = $request->input('nama_folder');
                $document->description = $request->input('keterangan');
                $document->storage     = $folderName;
                $document->type        = 'FOLDER';

                if ($request->parent_id) {
                    $document->parent_id = $parent->id;
                    $document->level     = $parent->level+1;
                }

                if ($document->save()) {
                    if ($request->parent_id) {
                        $storage = $this->getStorageFolderLink($parent->id);
                        Storage::disk('local')->makeDirectory('public/'.$this->user->co_id.'/'.$storage.'/'.$folderName);
                    } else {
                        Storage::disk('local')->makeDirectory('public/'.$this->user->co_id.'/'.$folderName);
                    }
                }

                DB::commit();
                toastr('Berhasil Membuat Folder', 'success', 'Sukses Info :');
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
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function editFile(Document $document)
    {
        $subFolder = $this->getStorageFolderLink($document->id);
        // dd($subFolder);
        return view('pages.document.editFile', compact('document', 'subFolder'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function editFolder(Document $document)
    {
        return view('pages.document.editFolder', compact('document'));
    }

    /**
     * Get a validatorFiles for an incoming upload file request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatorUpdateFiles(array $data)
    {
        return Validator::make($data, [
            'nama_file'     => "min:3|max:100|string|regex:/(^([a-zA-Z0-9 ]+)(\d+\s\w+)?$)/u",
            'keterangan'    => "min:1|max:250|string|regex:/(^([a-zA-Z0-9 ,.:'-=]+)(\d+\s\w+)?$)/m",
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function updateFile(Request $request, Document $document)
    {
        // dd($request);
        $validator = $this->validatorUpdateFiles($request->all());

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


                // $document->co_id       = $request->input('co_id');
                $document->name        = $request->input('nama_file');
                $document->description = $request->input('keterangan');
                // $document->storage     = $fileName;
                // $document->type        = 'FILE';

                // if ($document->save()) {
                //     $request['file_name']->storeAs('public/'.$this->user->co_id.'/',$fileName);
                // }

                $document->update();

                DB::commit();
                toastr('Berhasil Mengedit Data File', 'success', 'Sukses Info :');
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function updateFolder(Request $request, Document $document)
    {
        // dd($request);
        $validator = $this->validatorFolders($request->all());

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

                // $document->co_id       = $request->input('co_id');
                $document->name        = $request->input('nama_folder');
                $document->description = $request->input('keterangan');

                // if ($document->save()) {
                //     Storage::disk('local')->makeDirectory('public/'.$this->user->co_id.'/'.$folderName);
                // }

                $document->update();

                DB::commit();
                toastr('Berhasil Mengedit Data Folder', 'success', 'Sukses Info :');
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

    public function destroyStorageFolder($id)
    {
        $path = '';

        $document = Document::find($id);

        $childs   = Document::where('parent_id', $id)->get();

        if (!is_null($childs)) {
            foreach ($childs as $child) {
                $this->destroyStorageFolder($child->id);
            }
        }

        $document->delete();

        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        try {
            DB::beginTransaction();

            $msg  = "";
            $path = 'public/'.$document->co_id.'/'.$this->getStorageFolderLink($document->id);
            $type = $document->type;
            $hapus = false;

            if ($this->destroyStorageFolder($document->id)) {

                if ($type == 'FOLDER') {
                    if (Storage::deleteDirectory($path)) {
                        $hapus = true;
                    }
                } elseif ($type == 'FILE') {
                    if (Storage::delete($path)) {
                        $hapus = true;
                    }
                }

                if ($hapus) {
                    DB::commit();
                    toastr('Berhasil Menghapus Data', 'success', 'Delete Success :');
                    $msg = "success";
                } else {
                    $msg .= "Data fisik gagal dihapus !!!";
                }
            } else {
                $msg .= "Data base folder gagal dihapus !!!";
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function download(Document $document)
    {
        $path     = $document->co_id.'/'.$this->getStorageFolderLink($document->id);
        // dd(storage_path('public'));

        if ($document->type == 'FILE') {
            // $path     = $document->co_id.'/'.$this->getStorageFolderLink($document->id);
            $fileName = $document->name.'.'.pathinfo('storage/'.$path, PATHINFO_EXTENSION);
            // dd($path, $document->name.'.'.pathinfo('storage/'.$path, PATHINFO_EXTENSION), mime_content_type( 'storage/'.$path ));
            $headers = array(
                'Content-Type: ' . mime_content_type( 'storage/'.$path ),
            );

            return Storage::download('public/'.$path, $fileName, $headers);

        } elseif ($document->type == 'FOLDER') {


            // dd(Storage::disk('public')->allFiles($path));
            if (Storage::disk('public')->allFiles($path)) {

                #Hapus seluruh file didalam folder zip_temp
                #biar tidak terjadi penumpukan file didalam zip_temp
                $files = Storage::allFiles('public/zip_temp');

                if ($files) {
                    Storage::delete($files);
                }

                #Bikin file zip didalam folder zip_temp
                $zip_file = 'storage/zip_temp/'.$document->name.'.zip';
                // dd($zip_file);
                $zip = new \ZipArchive();
                $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                $path = storage_path('../public/storage/'.$path);
                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
                foreach ($files as $name => $file)
                {
                    // We're skipping all subfolders
                    if (!$file->isDir()) {
                        $filePath     = $file->getRealPath();
                        // dd($name, $file, $filePath, substr($filePath, strlen($path) + 1));

                        // extracting filename with substr/strlen
                        $relativePath = $document->name.'/' . substr($filePath, strlen($path) + 1);

                        $zip->addFile($filePath, $relativePath);
                    }
                }
                $zip->close();
                return response()->download($zip_file);

            } else {
                toastr('Folder Kosong!!!', 'warning', 'Gagal Download :');

        	    return redirect('/home');
            }


        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function copyFile(Document $document)
    {
        return view('pages.document.copyFile', compact('document'));
    }

    /**
     * Get a validatorFiles for an incoming upload file request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatorCopyFiles(array $data)
    {
        return Validator::make($data, [
            'co_id'         => 'required|integer',
            'target_id'     => 'required|integer',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function updateCopyFile(Request $request, Document $document)
    {
        // dd($request->all(), $document);
        $validator = $this->validatorCopyFiles($request->all());

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

                // Storage::copy('old/file1.jpg', 'new/file1.jpg');

                $old_path = 'public/'.$document->co_id.'/'.$this->getStorageFolderLink($document->id);

                $namaFile     = explode('.', $document->storage);
                $newFileName  = strtotime("now").'.'.$namaFile[1];

                $new_path = 'public/'.$document->co_id.'/'.$this->getStorageFolderLink($request->input('target_id')).'/'.$newFileName;
                // dd($old_path, $new_path, Storage::copy($old_path, $new_path));

                $newParent   = Document::find($request->input('target_id'));

                $newDocument = new Document;

                $newDocument->co_id        = $document->co_id;
                $newDocument->name         = $document->name;
                $newDocument->description  = $document->description;
                $newDocument->storage      = $newFileName;
                $newDocument->type         = $document->type;
                $newDocument->parent_id    = $newParent->id;
                $newDocument->level        = $newParent->level + 1;

                if ($newDocument->save()) {
                    if (Storage::copy($old_path, $new_path)) {
                        DB::commit();
                        toastr('Berhasil Mengcopy Data File', 'success', 'Sukses Info :');
                        $msg = "success";
                    } else {
                        DB::rollback();
                        toastr('Copy Data File gagal !!!', 'Warning', 'Warning Info :');
                        $msg = "warning";
                    }
                }

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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function copyFolder(Document $document)
    {
        return view('pages.document.copyFolder', compact('document'));
    }

    /**
     * Get a validatorFiles for an incoming upload file request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatorCopyFolder(array $data)
    {
        return Validator::make($data, [
            'co_id'         => 'required|integer',
            'target_id'     => 'required|integer',
        ]);
    }

    protected function CopyData($oldParent, $newParent)
    {
        // dd($oldParent, $newParent);

        $lists = Document::Where('parent_id', $oldParent->id)->get();

        foreach ($lists as $list) {

            $newChild = new Document;

            // $newChildName  = strtotime("now");

            $newChild->co_id        = $list->co_id;
            $newChild->name         = $list->name;
            $newChild->description  = $list->description;
            $newChild->storage      = $list->storage;
            $newChild->type         = $list->type;
            $newChild->parent_id    = $newParent->id;
            $newChild->level        = $newParent->level + 1;

            if ($newChild->save()) {
                if ($list->type == 'FOLDER') {
                    $this->CopyData($list, $newChild);
                }
            } else {
                return false;
            }
        }

        return true;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function updateCopyFolder(Request $request, Document $document)
    {
        // dd($request->all(), $document);
        $validator = $this->validatorCopyFiles($request->all());

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

                // Storage::copy('old/file1.jpg', 'new/file1.jpg');

                $newFileName  = strtotime("now");

                $newParent   = Document::find($request->input('target_id'));

                $newDocument = new Document;

                $newDocument->co_id        = $document->co_id;
                $newDocument->name         = $document->name;
                $newDocument->description  = $document->description;
                $newDocument->storage      = $newFileName;
                $newDocument->type         = $document->type;
                $newDocument->parent_id    = $newParent->id;
                $newDocument->level        = $newParent->level + 1;

                if ($newDocument->save()) {
                    ##ada sedikit kendala logika, antara mengcopy data fisik dengan data pada database,
                    ##secara fisik ketika dicopy maka seluruh file dan subfolder yang ada didalam folder langsung ikut tercopy
                    ##namun permasalahannya adalah ketika ingin merubah nama file dari masing2 file dan folder yang dicopy


                    #1. ambil seluruh liat data document pada folder yang dicopy
                    #2. insert data folder yang dicopy dengan nama baru, serta copy seluruh data turunannya / rekursive
                    #3. jika proses database berhasil, maka baru copy folder fisiknya.

                    if ($this->CopyData($document, $newDocument)) {

                        $old_path = '../storage/app/public/'.$document->co_id.'/'.$this->getStorageFolderLink($document->id);
                        $new_path = '../storage/app/public/'.$document->co_id.'/'.$this->getStorageFolderLink($request->input('target_id')).'/'.$newFileName;

                        // $old_path = storage_path('app/public').'/'.$document->co_id.'/'.$this->getStorageFolderLink($document->id);
                        // $new_path = storage_path('app/public').'/'.$document->co_id.'/'.$this->getStorageFolderLink($request->input('target_id')).'/'.$newFileName;

                        // dd($old_path, $new_path, File::copyDirectory($old_path, $new_path));

                        if (File::copyDirectory($old_path, $new_path)) {
                            DB::commit();
                            toastr('Berhasil Mengcopy Data File', 'success', 'Sukses Info :');
                            $msg = "success";
                        } else {
                            DB::rollback();
                            toastr('Copy Data File gagal !!!', 'Warning', 'Warning Info :');
                            $msg = "warning";
                        }

                    } else {
                        DB::rollback();
                        toastr('Copy Data File gagal !!!', 'Warning', 'Warning Info :');
                        $msg = "warning";
                    }


                    // dd($old_path, $new_path, File::copyDirectory($old_path, $new_path));
                    // dd(File::files(public_path($old_path)), File::allFiles(public_path($old_path)));
                    // {
                        // $files = File::allFiles(public_path($old_path));
                        // $data = array();
                        // $folders = array();
                        // $folderNames = array();
                        // $no = 0;
                        // $x = 0;
                        // foreach ($files as $file) {
                        //     // dd($file, $file->getFileName(), $file->getRealPath(), $file->getRelativePath());
                        //
                        //     if(empty($file->getRelativePath())){
                        //         #1.1 insert new document type file
                        //         $data[$no]['filename'] = $file->getFileName();
                        //         $data[$no]['realpath'] = $file->getRealPath();
                        //         $data[$no]['relativepath'] = $file->getRelativePath();
                        //     } else {
                        //         #1.2 insert new document type folder
                        //         $folders[$no] = explode('/', $file->getRelativePath());
                        //         foreach ($folders[$no] as $key => $value) {
                        //             // $key = array_search($value, array_column($folderNames, 'oldName'));
                        //             // if ($key) {
                        //                 $folderNames[$x]['oldName'] = $value;
                        //                 $folderNames[$x]['newName'] = strtotime("now")+$x;
                        //                 $x++;
                        //                 // sleep(1);
                        //             // }
                        //         }
                        //
                        //         #1.3 insert new document type file
                        //         $data[$no]['filename'] = $file->getFileName();
                        //         $data[$no]['realpath'] = $file->getRealPath();
                        //         $data[$no]['relativepath'] = $file->getRelativePath();
                        //     }
                        //
                        //
                        //     $no++;
                        // }
                        // dd($data, $folders, $folderNames);
                    // }


                }

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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function moveDocument(Document $document)
    {
        return view('pages.document.moveDocument', compact('document'));
    }

    protected function MoveData($parent)
    {
        // dd($oldParent, $newParent);

        $lists = Document::Where('parent_id', $parent->id)->get();

        foreach ($lists as $list) {

            // $list->parent_id    = $newParent->id;
            $list->level        = $parent->level + 1;

            if ($list->update()) {
                if ($list->type == 'FOLDER') {
                    $this->MoveData($list);
                }
            } else {
                return false;
            }
        }

        return true;

    }

    /**
     * Get a validatorFiles for an incoming upload file request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatorMoveDocuments(array $data)
    {
        return Validator::make($data, [
            'co_id'         => 'required|integer',
            'target_id'     => 'required|integer',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function updateMoveDocument(Request $request, Document $document)
    {
        // dd($request->all(), $document);
        $validator = $this->validatorMoveDocuments($request->all());

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

                // Storage::copy('old/file1.jpg', 'new/file1.jpg');

                $old_path = 'public/'.$document->co_id.'/'.$this->getStorageFolderLink($document->id);

                // $namaFile     = explode('.', $document->storage);
                // $newFileName  = strtotime("now").'.'.$namaFile[1];

                $new_path = 'public/'.$document->co_id.'/'.$this->getStorageFolderLink($request->input('target_id')).'/'.$document->storage;
                // dd($old_path, $new_path, Storage::copy($old_path, $new_path));

                $newParent   = Document::find($request->input('target_id'));

                $document->parent_id    = $newParent->id;
                $document->level        = $newParent->level + 1;

                if ($document->update()) {
                    $move = false;
                    if ($document->type == 'FOLDER') {
                        if ($this->MoveData($document)) {
                            $move = true;
                        }
                    } else {
                        $move = true;
                    }

                    if ($move) {
                        if (Storage::move($old_path, $new_path)) {
                            DB::commit();
                            toastr('Berhasil Memindahkan Data File', 'success', 'Sukses Info :');
                            $msg = "success";
                        } else {
                            DB::rollback();
                            toastr('Memindahkan Data File gagal !!!', 'Warning', 'Warning Info :');
                            $msg = "warning";
                        }
                    } else {
                        DB::rollback();
                        toastr('Memindahkan Data File gagal !!!', 'Warning', 'Warning Info :');
                        $msg = "warning";
                    }

                }

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


    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function uploadFiles()
    // {
    //     return view('pages.document.uploadFiles');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataSubFolder(Request $request)
    {
        $columns = array(
                            0  => 'name',
                            1  => 'updated_at',
                            12  => 'btnOther',
                        );

        $totalData = Document::Where([['co_id', $this->user->co_id], ['parent_id', $request->parent_id]])
                              ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $documents = Document::Where([['co_id', $this->user->co_id], ['parent_id', $request->parent_id]])
                                 ->offset($start)
                                 ->limit($limit)
                                 ->orderBy('type', 'ASC')
                                 ->orderBy($order,$dir)
                                 ->get();
        }
        else {
            $search = $request->input('search.value');

            $documents = Document::Where([['co_id', $this->user->co_id], ['parent_id', $request->parent_id]])
                                 ->where(function ($query) use ($search) {
                                            $query->where('name', 'LIKE', "%{$search}%")
                                                  ->orWhere('description', 'LIKE', "%{$search}%");
                                        })
                                 ->offset($start)
                                 ->limit($limit)
                                 ->orderBy('type', 'ASC')
                                 ->orderBy($order,$dir)
                                 ->get();

            $totalFiltered = Document::Where([['co_id', $this->user->co_id], ['parent_id', $request->parent_id]])
                                 ->where(function ($query) use ($search) {
                                             $query->where('name', 'LIKE', "%{$search}%")
                                                   ->orWhere('description', 'LIKE', "%{$search}%");
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

                // if ($document->status != "N") {
                    $btnDel = Form::open(['route' => ['document.destroy', $document->id],
                                        'method' => 'DELETE', 'target' => '',
                                        'name' => 'formDelete',
                                        'id' => 'formDelete']).
                              Form::button('<i class="fas fa-trash-alt"></i> Delete', ['class'=>'dropdown-item', 'type'=>'submit', 'title' => 'Hapus Data', 'data-toggle' => 'tooltip', 'data-placement' => 'top']).
                              Form::close();

                    // $path = 'storage/'.$document->co_id.'/'.$this->getStorageFolderLink($document->id);

                    // <a href="#" class="btn btn-outline-secondary btn-xs" target="_blank" title="Share">
                    //     <i class="fas fa-share-alt-square"></i>
                    // </a>
                    // <a href="#" class="btn btn-outline-secondary btn-xs" target="_blank" title="Copy Link">
                    //     <i class="fas fa-link"></i>
                    // </a>

                    $btnOther = '<td align="center">
                                    <div class="btn-group dropleft">
                                        <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="More">
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

}
