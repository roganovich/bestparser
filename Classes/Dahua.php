<?php 
class Dahua extends Base{
    public $articles = array();	
    public $attributes = array();
    
    public function parseDoom(){
        $b_url = $this->attributes['base_url'];
        $f_url = $this->attributes['first_url'];
       
        $this->putToLog('Парсер '.get_class($this).' начал работу');
       
        $html = $this->getDoom($f_url);
        $base_doom = $html->find('ul[class=category-tree]',0);
        foreach ($base_doom->find('li[class=category category-level-1]') as $parent){
            $ret = array();
            $ret['cat_name'] = trim($parent->find('a',0)->plaintext);
            $ret['cat_url'] = $b_url.trim($parent->find('a',0)->href);
            
            $this->putToLog('Работаю с каталогом '.$ret['cat_name']);
            
            $sub = $parent->find('ul',0);
            foreach ($sub->find('li[class=category category-level-2]') as $item) {
                $ret['syb_cat_name'] = trim($item->find('a',0)->plaintext);
                $ret['syb_cat_url'] = $b_url.trim($item->find('a',0)->href);
                
                 $this->putToLog('Работаю на подкаталогом '.$ret['syb_cat_name']);
                
                /*запускаем поиск артикулов*/
                $this->parseArticle($ret['syb_cat_url'],$ret);
            }
        }
        $html->clear();

    }
    /*функция ищет все товары на указанной страницу и заносит их в массив товаров обьекта*/
    public function parseArticle($article_url,$ret){
        $b_url = $this->attributes['base_url'];
       //$article_url='http://dahua-russia.ru/catalog/accessories';
        $html_f = $this->getDoom($article_url);
        $pager =$html_f->find('div[class=paginator]',0)->last_child()->href;
        $last_page=1;
        if($pager){
            $pager_ar=explode('&page=', $pager); 
            $last_page = $pager_ar[1]; 
        }
       
        for($p=1;$p<=$last_page;$p++){
            if($p>1){
                $html = $this->getDoom($article_url.'?page='.$p);
            }else{
                $html = $this->getDoom($article_url);
            }
             $this->putToLog('Работаю на странице '.$p);
          
                foreach ($html->find('article[class=tbl-row]') as $arts) {
                    $art_id = count($this->articles);
                    $art = $arts->find('div[class=tbl-cell]',1);
                   
                    $ret['art_id'] =  $art_id;
                    $ret['art_title'] = trim($art->find('h2',0)->find('a',0)->plaintext);
                     
                    $this->putToLog('Работаю с  артикулом '.$ret['art_id'].' '.$ret['art_title']);
                    
                    $ret['art_url'] = $b_url.trim($art->find('h2',0)->find('a',0)->href);
             
                    $art_obf = $this->getDoom($ret['art_url']);
                    if(is_object($art_obf)){
                        $ret['desc'] = trim($art_obf->find('div[class=product-description product-info-section]',0)->plaintext);
                        $ret['art_img'] = $b_url.trim($art_obf->find('div[class=product-image-wrapper]',0)->find('img',0)->src);

                        $params = $art_obf->find('table[class=product-property-table]',0);
                        if(is_object($params)){
                            foreach($params->find('tr[class=property]') as $tr){
                                $t1 = $tr->find('td',0)->plaintext;
                                $t2 = $tr->find('td',1)->plaintext;
                                $ret['param'][trim(str_replace('  ', '', $t1))]=trim($t2);
                            }
                        }
                        $this->setArticle($art_id,$ret);  
                    }
                    
                }
        }
       
       
        
    }
}
