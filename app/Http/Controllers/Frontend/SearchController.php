<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Requests;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\TermRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Droit\Loi\Repo\LoiInterface;
use App\Droit\Domain\Repo\DomainInterface;
use App\Droit\Disposition\Repo\DispositionInterface;
use App\Droit\Matiere\Repo\MatiereInterface;
use App\Droit\Rjn\Repo\RjnInterface;
use App\Droit\Service\Worker\SearchWorker;

class SearchController extends Controller {

    protected $loi;
    protected $disposition;
    protected $helper;
    protected $rjn;
    protected $worker;
    protected $domain;
    protected $matiere;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RjnInterface $rjn, DomainInterface $domain,LoiInterface $loi,DispositionInterface $disposition, SearchWorker $worker,MatiereInterface $matiere){

        $this->middleware('search');

        $this->loi         = $loi;
        $this->disposition = $disposition;
        $this->rjn         = $rjn;
        $this->worker      = $worker;
        $this->domain      = $domain;
        $this->matiere     = $matiere;

        $this->helper      = new \App\Droit\Helper\Helper;
    }

    public function index(SearchRequest $request)
    {
        $results = $this->disposition->newsearch($request->except('_token'));

        $arrets = (!$results->isEmpty() ? $this->worker->search($results) : collect([]));
        $terms = array_filter($request->except('_token'));

        return view('frontend.search')->with(['arrets' => $arrets , 'terms' => $terms, 'content' => 'Jurisprudence', 'type' => 'arret']);
    }

    public function searching(TermRequest $request){

        $type = [
            'arret'     => 'Jurisprudence',
            'chronique' => 'Chronique de jurisprudence',
            'doctrine'  => 'Doctrine et Chronique de jurisprudence',
            'matiere'   => 'Matières',
            'loi'       => 'Lois',
        ];

        $content = $request->input('content');
        $search_content = ($content == 'doctrine' ? ['doctrine','chronique'] : $content);

        if(strpos(strtolower($request->input('terms')), "rjn") == 0) {
            $aTerms = explode(" ", $request->input('terms'));
            if(sizeof($aTerms) > 2) {
                $volume = $aTerms[1];
                $page = $aTerms[2];
                $volumes = $this->rjn->getAll();
                $results = $this->worker->searchByVolumePage($volume, $page, $request->input('terms'),$volumes);
            } else {
                $results = $this->worker->searchAll($request->input('terms'),$search_content);
            }
        } else {
            $results = $this->worker->searchAll($request->input('terms'),$search_content);
        }

        return view('frontend.global')->with(['results' => $results, 'content' => $type[$content], 'type' => $content]);
    }

    /**
     * Display a listing for ajax autocomplete
     *
     * @return Response
     */
    public function matieres(Request $request)
    {
        $matieres = $this->matiere->search($request->input('term'));

        return $this->convertAutocomplete($matieres);
    }

    public function convertAutocomplete($results){

        if(!$results->isEmpty())
        {
            foreach($results as $result)
            {
                $data[] = ['label' => $result->title , 'id' => $result->id , 'value' =>  '"'.$result->title.'"'];
            }
        }

        return ($data ? $data : []);
    }

    /**
     * Display a listing for ajax autocomplete
     *
     * @return Response
     */
    public function lois(Request $request)
    {
        $lois = $this->loi->search($request->input('term'));

        return $this->convertAutocompleteLoi($lois);
    }

    public function articles(Request $request)
    {
        $loi = $this->loi->find($request->input('loi_id'));

        $articles = $loi->dispositions->unique('cote')->map(function ($article) {
            return [
                'id'   => $article->cote,
                'text' => 'Art. '.$article->cote,
            ];
        })->toArray();

        return response()->json($articles);
    }

    public function notes(Request $request)
    {
        // loi_id, cote
        $results = $this->disposition->newsearch(['loi_id' => $request->input('loi_id'), 'article' => $request->input('article')]);

        $results = $results->pluck('disposition_pages')
            ->flatten(1)
            ->unique(function ($item) {
                return $item->resume;
            })
            ->map(function ($disposition) {
                return [
                    'id'    => $disposition->id,
                    'text'  => $disposition->resume,
            /* */   'other' => [
                    'alinea'    => $disposition->alinea,
                    'chiffre'   => $disposition->chiffre,
                    'lettre'    => $disposition->lettre,
                    'page'      => $disposition->page,
                    'volume_id' => $disposition->volume_id,
                ]
            ];
        })->reject(function ($item) {
            return empty($item['text']);
        })->values();

        return response()->json($results);
    }

    public function convertAutocompleteLoi($results){

        if(!$results->isEmpty()) {
            foreach($results as $result) {
                $data[] = ['label' => $result->name , 'idloi' => $result->id, 'sigle' => $result->sigle , 'value' =>  '"'.$result->name.'"'];
            }
        }

        return ($data ? $data : []);
    }
}
