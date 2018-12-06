<?php 
namespace App\Repositories;

interface TextInterface {
	public function storeT($source, $text);
	public function getText();
	public function getTextID($id);
}