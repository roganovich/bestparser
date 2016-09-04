<?php 
class Base{
	public $attributes = array();
	public $articles = array();
	
	
	public function getDoom($url){
		$file_curl = new mycurl($url);
                $file_curl->createCurl();
                $html = new simple_html_dom();
		return $html->load($file_curl->__tostring());
                //unset($html);
                //unset($file_curl);
		
	}
	/*функция записывает переменную какието свойства обьекта */
	public function setParam($param,$value){
		$this->attributes[$param]=$value;
	}
	
	/*функция записывает в обьект товар*/
	public function setArticle($art_id, $params){
            foreach ($params as $name=>$value){
                $srt_parm = array();
                /*превращаем массив характеристик в строку*/
                if(is_array($value)){
                        foreach($value as $key=>$val){
                                $srt_parm[] = $key.':'.$val;
                        }
                        $value = implode(', ',$srt_parm);
                }
               
                $this->articles[$art_id][$name] = $value; 
            }
	}
	
	/*функция выводит на экран определенный товар*/
	public function getArticle($art_id){
		echo '<pre>';
		print_R($this->articles[$art_id]);
		echo '</pre>';
	}
	
	/*функция выводит на экран массив товаров*/
	public function getArticles(){
		echo '<pre>';
		print_R($this->articles);
		echo '</pre>';
	}
	
	/*функция выводит в файл массив товаров*/
	public function putArticlesToCSV(){
		$file = $this->attributes['exportFile'];
		$fp = fopen($file, 'w');
		$flipped = array_flip($this->articles[0]);
               // fputcsv($fp, array('Каталог','URL каталога','ПодКаталог','URL Подкаталога','ID','URL товара','Картинка','Название','Характеристики'), ';');
                fputcsv($fp,$flipped, ';');
                foreach ($this->articles as $article) {
			fputcsv($fp, $article, ';');
		}
		fclose($fp);
	}
        
        /*функция добавляет запись в лог*/
	public function putToLog($str){
            $file = 'log_'.$this->attributes['exportFile'];
            $fp=fopen($file,"a");  
            fwrite($fp, "\r\n" . $str);  
            fclose($fp);
	}
	
}