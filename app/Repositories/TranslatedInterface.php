<?php 
namespace App\Repositories;

interface TranslatedInterface{
	public function storeT($target, $translatedText,$textStore);
}