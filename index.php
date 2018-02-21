<?php 

include 'simple_html_dom.php';
require_once 'list_manga.php';

$chapter = '31.2';
$dataHtml = file_get_html('http://mangakakalot.com/chapter/qed_shoumei_shuuryou/chapter_'.$chapter);
$mangaList = mangaList();
set_time_limit(0);
foreach ($mangaList as $web => $listManga) {
	foreach($listManga as $title => $urlManga){
		$dataHtml = file_get_html($urlManga);
		if($web == 'mangakakalot'){
			mangakakalot($dataHtml, $title);
		}
	}
}

function mangakakalot($dataHtml = false, $title = ''){
	if(!$dataHtml){
		return false;
	}
	$rowChapterList = $dataHtml->find('div.chapter-list > div.row > a')/*->find('div.row')*//*->find('span', 0)*/;
	foreach ($rowChapterList as $valueChapterList) {
		$urlDetail = $valueChapterList->href;
		$chapter = explode('/', $urlDetail);
		$chapter = end($chapter);

		$chapterTitle = str_replace(array(':','"'),'-',$valueChapterList->innertext);
		
		$dataDetailHtml = file_get_html($urlDetail);
		$dataDetail = $dataDetailHtml->find('div#vungdoc > img');
		echo 'Saving '.$chapterTitle.' : <br/>';
		foreach ($dataDetail as $detail) {
			$filename = preg_replace('/[^\da-z]/i', '_', $detail->title);
			$ext = explode('.', $detail->src);
			$ext = end($ext);

			$dir = 'data/'.$title.'/'.$chapterTitle.'/';
			if(!file_exists($dir)){
				mkdir($dir, 0777, true);
			}
			if(@copy($detail->src, $dir.$filename.'.'.$ext)){
				echo $filename.' saved to : '.$dir.$filename.'<br/>';
			}else{
				echo $filename.' failed to save<br/>';
			}
		}
		echo '==========================END SAVING=========================<br/>';
	}
}
/*$div = $dataHtml->find('div#vungdoc');

foreach ($div as $key => $value) {
	$img = $value->find('img');
	foreach($img as $val){
		$explode = explode('-', $val->src);
		$filename = end($explode);
		$dir = 'data/qed/'.$chapter.'/';
		if(!file_exists($dir)){
			mkdir($dir, 0777, true);
		}
		copy($val->src, $dir.$filename);
		echo 'saved to : '.$dir.$filename.'<br/>';
	}
}*/
?>