<?php

namespace App\Http\Controllers;
use View;
use Illuminate\Http\Request;
use App\Repositories\TextInterface as TextInterface;
//use App\Repositories\TranslatedInterface as TranslatedInterface;
use App\Translated;

// https://cloud.google.com/translate/docs/reference/rest
class TranslateApiController extends Controller
{
	protected $api_key = '';
	protected $request;

    public function __construct(TextInterface $textI,Translated $translated_text, Request $request)
    {
    	$this->textI = $textI;
    	$this->request = $request;
    	$this->translated_text = $translated_text;
	}

	public function getAllTranslated(){
        return $this->translated_text->with('text')->get();
    }

    public function getTranslated($text){

        return $this->translated_text->where('translated_text', '=', $text)->get();
    }

//returns list of all data from database tables in relation HTTP: GET
	public function index(){

		$allTranslated = $this->getAllTranslated();
		return response()->json(['data' => $allTranslated]);
	}
//returns the translated word to be used by internal apps HTTP: GET
	public function translatedWord(){
		//$text ='bag';
		$text = $this->request->get('text');
		$translated = $this->getTranslated($text);
		return response()->json(['data' => $translated]);
	}
//Consuming rest post request of Google Translate
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

	public function storeTranslate($target, $translatedText, $id){

		return $this->translated_text->create(
            ['langCode' => $target,
                'translated_text' => $translatedText,
                'text_to_be_translated_id' => $id
            ]
            );
	}
//HTTP POST Request FOR TRANSLATION
	public function post(){
		$text = $this->request->get('text');
		$source = $this->request->get('source');
		$target = $this->request->get('target');

		$translatedText = [];
		$successStatus = 200;
		$successStatusCreate = 201;
		$successStatusError = 400;
		
		//searching for text and target language in the db
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
			        
			        return response()->json(['success'=>$translatedText,'status'=> $successStatusCreate]);
			    }
			}
			else
			    $translatedText= ['Unknown Error'];
				return response()->json(['error' => $translatedText, 'status' => $successStatusError]);
		}
	}
	//sends lists to be translated to the agency from db HTTP GET/WAITING
	public function getWaitingList(){
		$list = $this->request->get('list');
		$source = $this->request->get('source');
		$target = $this->request->get('target');

		$waiting = $this->textI->getTextsWithArray($list, $target);
		//if list is null then send it to the agency
		if (count($waiting)==0) {
			return response()->json(['waiting'=>$list, 'status' => 'waiting']);
		}else{
			return response()->json(['data' => $waiting, 'status' => 'translated']);
		}
		 
	}
}
