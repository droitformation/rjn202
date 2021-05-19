<?php namespace App\Droit\Arret\Worker;


class ArretWorker{

    protected $custom;

    public function __construct()
    {
        $this->custom  = new \App\Droit\Helper\Helper;
    }

    public function dispachCategories($arrets)
    {
        if(!$arrets->isEmpty()){
            foreach($arrets as $arret){
                if(isset($arret->arrets_categories))
                {
                    foreach($arret->arrets_categories as $categorie){
                        $sort[$categorie->id][] = $arret;
                    }
                }
            }
        }

        return (!empty($sort) ? $sort : []);
    }
    
    public function getArretsData($arrets, $rjn) {
        $return = array();
        
        $years = array();
        $pages = array();
        $manageYears = array();
        foreach($arrets as $arret) {
            $currentYear = $rjn->find($arret->volume_id)->publication_at->year;
            if(strlen($currentYear) == 4 && !isset($manageYears[$currentYear])) {
                $manageYears[$currentYear] = $currentYear;
                $years[] = ['name' => 'rjn '.$currentYear];
            }
            $pages[] = ['name' => 'rjn '.$currentYear." ".$arret->page." "];
        }
        
        usort($pages, function($a, $b) {
            return $a['name'] > $b['name'];
        });
            
        usort($years, function($a, $b) {
            return $a['name'] < $b['name'];
        });
            
        $return['volumes'] = $years;
        $return['pages'] = $pages;
        $return['all'] = array_merge($years,$pages);
        
        return $return;
    }
}