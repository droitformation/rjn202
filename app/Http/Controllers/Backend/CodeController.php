<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCode;
use App\Droit\Code\Repo\CodeInterface;
use Illuminate\Http\Request;

class CodeController extends Controller {

    protected $code;

    public function __construct(CodeInterface $code)
    {
        $this->code = $code;
    }

	/**
	 *
	 * @return Response
	 */
	public function index()
	{
        $codes = $this->code->getAll();

        return view('admin.codes.index')->with(['codes' => $codes]);
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.codes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CreateCode $request)
    {
        $code = $this->code->create($request->all());

        return redirect('admin/code/'.$code->id);
    }

    /**
     *
     * @return Response
     */
    public function show($id)
    {
        $code = $this->code->find($id);

        return view('admin.codes.show')->with(['code' => $code]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id,CreateCode $request)
    {
        $code = $this->code->update($request->all());

        return redirect('admin/code/'.$code->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->code->delete($id);

        return redirect('admin/code')->with(array('status' => 'success', 'message' => 'Codee supprimé' ));
    }

    public function addCode(Request $request)
    {
        $code = $this->code->valid($request->input('code'));

        if($code){
            $code = $this->code->update(['id' => $code->id, 'user_id' => $request->input('user_id'), 'used' => 1]);

            return redirect()->back()->with(['status' => 'success', 'message' => 'Code ajouté']);
        }

        return redirect()->back()->with(['status' => 'danger', 'message' => 'Code non valide']);
    }

    public function removeCode(Request $request)
    {
        $code = $this->code->find($request->input('code_id'));

        if($code){
            $code = $this->code->update(['id' => $code->id, 'user_id' => null, 'used' => null]);

            return redirect()->back()->with(['status' => 'success', 'message' => 'Code retiré']);
        }

        return redirect()->back()->with(['status' => 'danger', 'message' => 'Code non valide']);
    }
}
