<?php 
class Partizan extends Base{
	public $articles = array();	
	public $attributes = array();
	
	public function parseDoom(){
		$b_url = $this->attributes['base_url'];
		$html = $this->getDoom($b_url);
		foreach ($html->find('div[class=cellsBlock]') as $parent){
			foreach ($parent->find('div[class=test2]') as $iKey=> $item){
				$ret = array();
				$ret['cat_name'] = trim($item->find('div',1)->plaintext);
				$ret['cat_url'] = $b_url.trim($item->find('a',0)->href);
				
				/*вторая вложенность*/
				$child2 = $this->getDoom($ret['cat_url']);
				/*Если есть вложенность каталогов*/
				$itemListSubCategory = $child2->find('div[class=itemListCategory]',0);
				$itemListArticles = $child2->find('div[class=catItemBody]',0);
				
				 if((!empty($itemListSubCategory))&&(count($itemListSubCategory)>0)){
					$tableChild = $itemListSubCategory->find('table',0);
					$tableTrChild1 = $tableChild->find('tr',0);
					$tableTrChild2 = $tableChild->find('tr',1);
					foreach ($tableTrChild1->find('td') as $item_td){
						foreach ($item_td->find('a') as $key=>$item_a){
							$ret['syb_cat_name'] = trim($tableTrChild2->find('td',$key)->plaintext);
							$ret['syb_cat_url'] = $b_url.trim($item_a->href);
							/*запускаем поиск артикулов*/
							$this->parseArticle($ret['syb_cat_url'],$ret);
						}
					}
				}
				if((!empty($itemListArticles))&&(count($itemListArticles)>0)){
					/*Если нет вложенности сразу ищем товары*/
					$ret['syb_cat_name'] = '-';
					$ret['syb_cat_url'] = '-';
					/*запускаем поиск артикулов*/
					$this->parseArticle($ret['cat_url'],$ret);
				}
				$child2->clear();
			}
		}
			
	}
	/*функция ищет все товары на указанной страницу и заносит их в массив товаров обьекта*/
	public function parseArticle($article_url,$ret){
		$b_url = $this->attributes['base_url'];
		$html = $this->getDoom($article_url);
		foreach($html->find('div[class=catItemBody]') as $art_block){
			$art_id = count($this->articles);
			$ret['art_id'] =  $art_id;
			$ret['art_url'] = $b_url.$art_block->find('a',0)->href;
			$ret['art_img'] = $b_url.$art_block->find('img',0)->src;
			$ret['art_title']=$art_block->find('div[class=modname]',0)->find('a',0)->plaintext;
			$params = $art_block->find('ul[class=u]',0);
			if(is_object($params)){
				foreach($params->find('li') as $li){
					$strLi = explode(':',$li->plaintext);
					$ret['params'][trim($strLi[0])]=trim($strLi[1]); 
				}
			}
			$this->setArticle($art_id,$ret);
		}
	}

	
	
	
	
}

