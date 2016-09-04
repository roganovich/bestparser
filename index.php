<!DOCTYPE html>
<html> 
<head>  
<meta charset="utf-8">
<title>BParser</title>
</head> 
<body> 
    <h1>BParser</h1>
		<p>R.R.M</p>
		<?php 
		include 'function.php';
                
                
		 /*Создаем обработчик сайта http://partizan-cctv.ru*/
		$parser1 = new Builder('Partizan');
		$bp_pz = $parser1->getParser('Partizan');
		$bp_pz->setParam('base_url','http://partizan-cctv.ru'); /*добавляем параметр базовой страницы*/
		$bp_pz->setParam('exportFile','partizan.csv'); /*добавляем параметр файла экспорта*/
                $bp_pz->parseDoom();/*запускаем стартовую страницу парсера*/
		$bp_pz->getArticles();/*выводим на экран все артикулы*/
		$bp_pz->putArticlesToCSV();/*выводим в файл все артикулы*/
              
                  die(); 
                
                $parser2 = new Builder('Rvi');
                $bp_rvi = $parser2->getParser('Rvi');
                $bp_rvi->setParam('base_url','http://rvi-cctv.ru'); /*добавляем параметр базовой страницы*/
                $bp_rvi->setParam('first_url','http://rvi-cctv.ru/catalog');
		$bp_rvi->setParam('exportFile','rvi.csv'); /*добавляем параметр файла экспорта*/
                $bp_rvi->parseDoom();/*запускаем стартовую страницу парсера*/
                $bp_rvi->getArticles();/*выводим на экран все артикулы*/
		$bp_rvi->putArticlesToCSV();/*выводим в файл все артикулы*/
              
                $parser3 = new Builder('Dahua');
                $bp_da = $parser3->getParser('Dahua');
		$bp_da->setParam('base_url','http://dahua-russia.ru'); /*добавляем параметр базовой страницы*/
                $bp_da->setParam('first_url','http://dahua-russia.ru/catalog');
		$bp_da->setParam('exportFile','dahua.csv'); /*добавляем параметр файла экспорта*/
                $bp_da->parseDoom();/*запускаем стартовую страницу парсера*/
                $bp_da->getArticles();/*выводим на экран все артикулы*/
		$bp_da->putArticlesToCSV();/*выводим в файл все артикулы*/
                
         
             
                
		?>
		
		
  </body>
</html>