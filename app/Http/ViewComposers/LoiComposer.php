<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

use App\Droit\Domain\Repo\DomainInterface;
use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Rjn\Repo\RjnInterface;
use App\Droit\Loi\Repo\LoiInterface;

class LoiComposer
{
    protected $domain;
    protected $categorie;
    protected $rjn;
    protected $loi;
    protected $helper;
    protected $alpha;

    public function __construct(DomainInterface $domain, CategorieInterface $categorie, RjnInterface $rjn, LoiInterface $loi)
    {
        $this->categorie = $categorie;
        $this->domain    = $domain;
        $this->rjn       = $rjn;
        $this->loi       = $loi;
        $this->helper  = new \App\Droit\Helper\Helper;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $domains    = $this->domain->getAll(2);
        $categories = $this->categorie->getAll(1);

        $lois = $this->loi->getAll();

        $alllois = !$lois->isEmpty() ? $lois->map(function ($loi, $key) {
            return [
                'id'   => $loi->id,
                'text' => $loi->sigle,
            ];
        })->reject(function ($item) {
            return empty($item['text']);
        }) : [];

        $view->with('alllois',$alllois);
    }
}
