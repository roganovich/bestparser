<?php 
class Rvi extends Base{
    public $articles = array();	
    public $attributes = array();
    
    public function parseDoom(){
        $b_url = $this->attributes['base_url'];
        $f_url = $this->attributes['first_url'];
      
        $html = $this->getDoom($f_url);
        $base_doom = $html->find('div[class=content-main]',0);
        $this->putToLog('Парсер '.get_class($this).' начал работу');
        foreach ($base_doom->find('div[class=rc-inner]') as $parent){
            $ret = array();
            $ret['cat_name'] = trim($parent->find('a[class="cr-name"]',0)->find('i',0)->plaintext);
            $ret['cat_url'] = $b_url.trim($parent->find('a[class="cr-name"]',0)->href);
            $ret['cat_img'] = $b_url.trim($parent->find('a[class="rc-img"]',0)->find('img',0)->src);
            
            $this->putToLog('Работаю с каталогом '.$ret['cat_name']);
            
            $child2 = $this->getDoom($ret['cat_url']);
                $pager =$child2->find('div[class=paginator]',0);
                $pager_c =count($pager->find('li'));
              
                $last_page=1;
                if($pager_c){
                  $last_page = $pager_c-1; 
                }
            
                for($p=1;$p<=$last_page;$p++){
                    
                    $this->putToLog('Работаю на странице '.$p);
                    
                    if($p>1){
                        $child_new = $this->getDoom($ret['cat_url'].'/?PAGEN_1='.$p);
                    }else{
                        $child_new = $this->getDoom($ret['cat_url']);
                    }
                    //$child_array = $child2->find('div[class=cat-inner]');
                    foreach ($child_new->find('div[class=cat-block]') as $item_td) {
                        if(is_object($item_td)){
                            $ret['art_url'] = $b_url.trim($item_td->find('a[class="cb-name"]',0)->href);
                            $this->putToLog('Работаю на странице артикула '.$ret['art_url']);
                            $this->parseArticle($ret['art_url'],$ret); 
                        }
                    }
                    $child_new->clear();
                }
                
                $child2->clear();
          
        }
        $html->clear();

    }
    /*функция ищет все товары на указанной страницу и заносит их в массив товаров обьекта*/
    public function parseArticle($article_url,$ret){
        $b_url = $this->attributes['base_url'];

        $html = $this->getDoom($article_url);
        if(is_object($html)){
            $art_id = count($this->articles);
            $ret['art_id'] =  $art_id;
            $ret['art_title'] = trim($html->find('div[class=cat-inner]',0)->find('h1',0)->plaintext);
            
            $this->putToLog('Работаю с  артикулом '.$ret['art_id'].' '.$ret['art_title']);
            
            $ret['desc'] = trim($html->find('div[class=osobennost]',0)->plaintext);
            
            $imgs = $html->find('div[id=products_example]',0);
            if(is_object($imgs)){
                foreach ($imgs->find('a[class=fancybox]') as $a) {
                   $ret['images'][] =  $b_url.trim($a->find('img',0)->src);
                }
            }
            $params = $html->find('table[class=table-char my_class]',0);
            if(is_object($params)){
                foreach($params->find('tr') as $tr){
                    $t1 = $tr->find('td',0)->plaintext;
                    $t2 = $tr->find('td',1)->plaintext;
                    $ret['param'][trim($t1)]=trim($t2);
                }
            }

            $this->setArticle($art_id,$ret);
        }else{
            echo '<pre>';
            print_r($article_url);
            echo '</pre>';
            die();
        }
        
        
    }
}
