<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Droit\Domain\Repo\DomainInterface;
use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Rjn\Repo\RjnInterface;
use App\Droit\Doctrine\Repo\DoctrineInterface;
use App\Droit\Chronique\Repo\ChroniqueInterface;
use App\Droit\Arret\Repo\ArretInterface;
use App\Droit\Arret\Worker\ArretWorker;
use App\Droit\Matiere\Repo\MatiereInterface;
use App\Droit\Loi\Repo\LoiInterface;
use App\Droit\Service\Worker\ColloqueWorker;
use App\Http\Requests\SendMessageRequest;
use App\Droit\Service\Worker\PageInterface;
use App\Droit\Disposition\Repo\DispositionInterface;
use App\Droit\Critique\Repo\CritiqueInterface;
use App\Jobs\SendSlide;

class HomeController extends Controller {

    protected $domain;
    protected $categorie;
    protected $rjn;
    protected $doctrine;
    protected $arret;
    protected $critique;
    protected $worker;
    protected $page;
    protected $matiere;
    protected $loi;
    protected $disposition;
    protected $chronique;
    protected $colloque;
    protected $domains_doc;
    protected $helper;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        PageInterface $page,
        DomainInterface $domain,
        CategorieInterface $categorie,
        RjnInterface $rjn,
        DoctrineInterface $doctrine,
        ArretInterface $arret,
        ArretWorker $worker,
        MatiereInterface $matiere,
        LoiInterface $loi,
        DispositionInterface $disposition,
        ChroniqueInterface $chronique,
        ColloqueWorker $colloque,
        CritiqueInterface $critique
    )
    {
        $this->page        = $page;
        $this->critique    = $critique;
        $this->loi         = $loi;
        $this->disposition = $disposition;
        $this->matiere     = $matiere;
        $this->arret       = $arret;
        $this->worker      = $worker;
        $this->doctrine    = $doctrine;
        $this->chronique   = $chronique;
        $this->colloque    = $colloque;
        $this->categorie   = $categorie;
        $this->domain      = $domain;
        $this->rjn         = $rjn;

        $this->helper  = new \App\Droit\Helper\Helper;

    }

    /**
     *
     * @return Response
     */
    public function index()
    {
        $lois     = $this->loi->getAllSigle();
        $articles = $lois->mapWithKeys(function ($loi) {
            return [
                $loi->id => $loi->dispositions->mapToGroups(function ($disposition, $key) {
                    return [$disposition->cote => $disposition->toArray()];
                })
            ];
        });

        $droits = [1 => 'Droit fédéral', 2 => 'Droit cantonal', 3 => 'Droit international'];

        $lois = $lois->sortBy('droit')->groupBy(function ($item, $key) use ($droits) {
            return isset($droits[$item->droit]) ? $droits[$item->droit] : '';
        })->map(function ($lois,$key) use ($droits) {
            $droits = array_flip($droits);
            return [
                 'id'   => $droits[$key] ?? '',
                 'text' => $key,
                 'children' => $lois->map(function ($article) {
                     return [
                         'id' => $article->id,
                         'text' => $article->sigle,
                     ];
                 })->toArray()
            ];

        })->values();

/*        echo '<pre>';
        print_r($lois);
        echo '</pre>';
        exit;*/
        
        $arrets = $this->arret->getAll(1);
        $arretsData = $this->worker->getArretsData($arrets, $this->rjn);

        return view('frontend.index')->with(['lois' => $lois, 'articles' => $articles, 'years' => $arretsData['volumes'], 'pages' => $arretsData['pages'], 'years_page' => $arretsData['all']]);
    }

    /**
     *
     * @return Response
     */
    public function historique()
    {
        return view('frontend.historique');
    }

    public function jurisprudence()
    {
        $section = [ 'url' => 'jurisprudence', 'page' => 'Jurisprudence' ];

        return view('frontend.jurisprudence')->with(array('section' => $section, 'content' => 'Jurisprudence', 'type' => 'arret'));
    }

    /**
     * Domain or jurisprudence
     * @return Response
     */
    public function domain($domain, $volume = null)
    {
        $volume_id = $this->getVolume($volume);
        $arrets    = $this->arret->getByVolume($volume_id,$domain);
        $section   = [ 'url' => 'domain', 'page' => 'Jurisprudence' ];

        return view('frontend.categorie')->with(
            [
                'current_id'     => $domain,
                'content'        => 'Jurisprudence',
                'type'           => 'arret',
                'current_volume' => $volume_id,
                'arrets'         => $arrets,
                'section'        => $section
            ]);
    }

    public function categorie($categorie, $volume = null)
    {
        $volume_id = $this->getVolume($volume);

        $arrets    = $this->arret->getByVolumeCategorie($volume_id,$categorie);

        $section   = [ 'url' => 'categorie', 'page' => 'Jurisprudence' ];

        return view('frontend.categorie')->with(
            [
                'current_id'     => $categorie ,
                'content'        => 'Jurisprudence',
                'type'           => 'arret',
                'current_volume' => $volume_id,
                'arrets'         => $arrets,
                'section'        => $section
            ]
        );
    }

    /**
     *
     * @return Response
     */
    public function doctrine($current = NULL)
    {
        $volumes     = $this->rjn->getAll();
        $domains_doc = $this->domain->getAll(1)->pluck('title','id')->all();

        $current    = (!$current ? $volumes->first()->id : $current);
        $doctrines  = $this->doctrine->getAll(1);
        $chroniques = $this->chronique->getAll(1);
        $chroniques = $this->helper->dispatchDomaine($chroniques,array_keys($domains_doc));

        $section = [ 'url' => 'doctrine' , 'page' => 'Doctrine' ];

        return view('frontend.doctrine')->with(['content' => 'Doctrine', 'type' => 'doctrine' ,'doctrines' => $doctrines, 'chroniques' => $chroniques , 'current' => $current, 'section' => $section]);
    }

    /**
     *
     * @return Response
     */
    public function matiere($alpha = NULL)
    {
        $alpha    = ($alpha ? $alpha : 'A');

        $matieres = $this->matiere->getAll($alpha);

        $section  = [ 'url' => 'matiere' , 'page' => 'Matières' ];

        return view('frontend.matiere')->with(['content' => 'Matières', 'type' => 'matiere','matieres' => $matieres, 'section' => $section, 'current' => $alpha]);
    }

    /**
     *
     * @return Response
     */
    public function lois()
    {
        $lois = $this->loi->getAll();
        $lois = $this->helper->dispatchLoi($lois);

        $section = [ 'url' => 'lois' , 'page' => 'Lois' ];

        return view('frontend.loi')->with(array('content' => 'Lois', 'type' => 'loi', 'lois' => $lois, 'section' => $section));
    }

    /**
     *
     * @return Response
     */
    public function disposition($id)
    {
        $dispositions = $this->disposition->findByLoi($id);

        $section = [ 'url' => 'lois ' , 'page' => 'Lois' ];

        return view('frontend.disposition')->with(array('dispositions' => $dispositions, 'section' => $section));
    }

    /**
     *
     * @return Response
     */
    public function arret($id)
    {
        $arret    = $this->arret->find($id);
        $critique = $this->critique->getByType('arret',$id);
        
        $section = [ 'url' => 'jurisprudence/' , 'page' => 'Jurisprudence' ];
        $page    = [ 'page' => 'arrêt' ];

        return view('frontend.arret')->with(array('arret' => $arret, 'critique' => $critique, 'section' => $section, 'page' => $page));
    }

    /**
     *
     * @return Response
     */
    public function article($id)
    {
        $article  = $this->doctrine->find($id);
        $critique = $this->critique->getByType('article',$id);

        $section = [ 'url' => 'doctrine' , 'page' => 'Doctrine' ];
        $page    = [ 'page' => 'Article' ];

        return view('frontend.article')->with(array('article' => $article, 'critique' => $critique, 'section' => $section, 'page' => $page));
    }

    /**
     *
     * @return Response
     */
    public function chronique($id)
    {

        $chronique = $this->chronique->find($id);
        $critique = $this->critique->getByType('chronique',$id);

        $section = [ 'url' => 'doctrine/'.$chronique->volume_id , 'page' => 'Chronique' ];
        $page    = [ 'page' => 'Article' ];

        return view('frontend.chronique')->with(array('chronique' => $chronique, 'critique' => $critique, 'section' => $section, 'page' => $page));
    }

    /**
     *
     * @return Response
     */
    public function colloque()
    {

        $colloques = $this->colloque->getColloques();
        $archives  = $this->colloque->getArchives();

        $section = [ 'url' => 'colloque' , 'page' => 'Colloques' ];

        return view('frontend.colloque')->with(['colloques' => $colloques, 'archives' => $archives, 'section' => $section]);
    }

    public function contact()
    {
        $section = [ 'url' => 'contact' , 'page' => 'Contact' ];

        return view('frontend.contact')->with(['section' => $section]);
    }

    /**
     * Send contact message
     *
     * @return Response
     */
    public function sendMessage(SendMessageRequest $request)
    {
        \Mail::to('info@rjne.ch')->send(new \App\Mail\SendMessage($request->input('email'),$request->input('nom'),$request->input('remarque')));

        return redirect('/')->with(array('status' => 'success', 'message' => '<strong>Merci pour votre message</strong><br/>Nous vous contacterons dès que possible.'));
    }

    /**
     * Redirect to content from page number
     *
     * @return Response
     */
    public function page($page,$volume,$path){

        $url = $this->page->calcul($page,$volume);

        if(!empty($url))
        {
            return redirect($url['content'].'/'.$url['id'])->with(['path' => $path]);
        }
        else
        {
            abort(404);
        }
    }

    /**
     * Filter content
     *
     * @return Response
     */
    public function filter(){

        $domain_id = \Request::get('domain_id');
        $volume_id = \Request::get('volume_id');

        return redirect('jurisprudence/'.$domain_id.'/'.$volume_id);

    }

    /**
     * Filter content
     *
     * @return Response
     */
    public function getVolume($volume){

        $request_volume = \Request::get('volume_id',null);

        if($request_volume)
        {
            return $request_volume;
        }
        elseif($volume)
        {
            return $volume;
        }
        else
        {
            return null;
        }
    }

}
