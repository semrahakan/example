<?php

namespace App\Http\Controllers;
use View;
use Illuminate\Http\Request;
use App\Repositories\TextInterface as TextInterface;
use App\Repositories\TranslatedInterface as TranslatedInterface;
use App\Translated;

// https://cloud.google.com/translate/docs/reference/rest
class TranslateController extends Controller
{
	protected $api_key = '';

    public function __construct(TextInterface $textI,Translated $translated_text,TranslatedInterface $tr)
    {
    	$this->textI = $textI;
    	$this->tr = $tr;
    	$this->translated_text = $translated_text;
	}
//Post Request
	public function translate($api_key,$text,$target,$source=false)
	{
		    $url = 'https://www.googleapis.com/language/translate/v2?key=' . $api_key . '&q=' . rawurlencode($text);

		    $url .= '&target='.$target;
		    if($source)
		     $url .= '&source='.$source;
		    $ch = curl_init($url);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    $response = curl_exec($ch);                 
		    curl_close($ch);

		    $obj =json_decode($response,true); //true converts stdClass to associative array.

		    return $obj;
	}
	
//get request
	public function getAvailableLanguages($api_key){
		$commonLags =[];
		$url = 'https://translation.googleapis.com/language/translate/v2/languages?key=' . $api_key;
		$langs = file_get_contents($url);

		$langData =json_decode($langs,true);

		foreach ($langData as $key => $value) {
		 	foreach ($value['languages'] as $key => $value) {
		 		if ($value['language'] == 'en' || $value['language'] == 'fr' || $value['language'] == 'es' || $value['language'] == 'de' || $value['language'] == 'tr') {
		 			$commonLags[$value['language']] = $value['language'];
		 		}
		 	}
		 } 
		return $commonLags;
	}
	
	public function index(){
		
		
		//get the available languages from google
		$languages = $this->getAvailableLanguages($this->api_key);
		
		return View::make('welcome')->with('languages', $languages);

	}
	public function storeTranslate($target, $translatedText, $id){

		return $this->translated_text->create(
            ['langCode' => $target,
                'translated_text' => $translatedText,
                'text_to_be_translated_id' => $id
            ]
            );
	}

	public function post(Request $request){

		// $api_key = 'AIzaSyCtjzseee7bRzBnJnXuKaReLFAxH-nisx0';
		// $text = $request->input('text');
		// $source= $request->input('languageFrom'); 
		// $target= $request->input('languageTo');
		// $translatedText = [];

		// $obj = $this->translate($api_key,$text,$target,$source);
		// if($obj != null)
		// {
		//     if(isset($obj['error']))
		//     {
		//         $translatedText= $obj['error']['message'];
		//         return response()->json(['error'=>$translatedText], 401);
		//     }
		//     else
		//     {	
		//     	$translatedText = $obj['data']['translations'][0]['translatedText'];
		//     	$textStore = $this->textI->storeT($source, $text);
		    	
		//     	if (is_int($textStore)) {
		//     		$id = $textStore;
		//     	}else{
		//     		$id= $textStore['id'];
		//     	}
		//     	$translatedStore = $this->storeTranslate($target, $translatedText, $id);
		        
		//         return response()->json(['success'=>$translatedText], $this->successStatus);
		//     }
		// }
		// else
		//     $translatedText= ['Unknown Error'];


		// return response($translatedText);

		$text = $request->input('text');
		$source= $request->input('languageFrom'); 
		$target= $request->input('languageTo');
		$translatedText = [];

		$successStatus = 200;
		$successStatusCreate = 201;
		$successStatusError = 400;

		//check the word in db if not found then translate
		$text_old= $this->textI->getTextWithName($text,$target);

		if ($text_old) {

			return response()->json(['success'=>$text_old, 'status'=>$successStatus]);
		}else{
			$obj = $this->translate($this->api_key,$text,$target,$source);
			if($obj != null)
			{
			    if(isset($obj['error']))
			    {
			        $translatedText= $obj['error']['message'];
			        return response()->json(['error'=>$translatedText, 'status' => $successStatusError]);
			    }
			    else
			    {	
			    	$translatedText = $obj['data']['translations'][0]['translatedText'];

			    	$textStore = $this->textI->storeT($source, $text);
			    	
			    	if (is_int($textStore)) {
		    		$id = $textStore;
			    	}else{
			    		$id= $textStore['id'];
			    	}

			    	$translatedStore = $this->storeTranslate($target, $translatedText, $id);
			    	// $translatedStore = $this->tr->storeTranslate($target, $translatedText, $id);
			        
			        return response()->json(['success'=>$translatedText,'status'=> $successStatusCreate]);
			    }
			}
			else
			    $translatedText= ['Unknown Error'];
				return response()->json(['error' => $translatedText, 'status' => $successStatusError]);
		}
	}

}
