<?php 

if(!function_exists('mangaList')){
	function mangaList(){
		$list = array(
			/*From http://mangakakalot.com*/
			'mangakakalot' => array(
				/*'qed' => 'http://mangakakalot.com/manga/qed_shoumei_shuuryou/',
				'cmb' => 'http://mangakakalot.com/manga/cmb',*/
			),
			/*From http://www.mangakita.net/*/
			'mangakita' => array(
				'the-promised-neverland' => 'http://www.mangakita.net/manga/the-promised-neverland/',
			),
		);
		return $list;
	}
}

if (!function_exists('dd')) {
    function dd(){
        echo '<pre>';
        $args = func_get_args();
        foreach ($args as $arg) {
            var_dump($arg);
            echo '<br/>===========================================================<br/><br/>';
        }
        echo '</pre>';
        die('==END==');
    }
}
?>