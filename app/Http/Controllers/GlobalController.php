<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GlobalController extends Controller
{
    public static function slugify($text, string $divider = '-'){
        
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
        return 'n-a';
        }

        return $text;
    }

    // Upload image to amazon s3 bucket
    public function uploadBucket($image,$path){

        $imagedata = $image;
        $extension = $imagedata->getClientOriginalExtension();
        $fileName = rand(1,999999).date('dMYHis').'.'.$extension;
        $destinationPath = $path."/".$fileName;
        $s3 = \Storage::disk('s3');
        $s3->put($destinationPath, file_get_contents($imagedata));
        return \Storage::disk('s3')->url($destinationPath);
    }

    // Convert Date Format
    public function convertDate($date){
        $date = explode('/',$date);
        return $date[2]."-".$date[1]."-".$date[0];
    }

    public function convertDateTime($date){
        $date = explode('-',$date);
        return $date[2]."/".$date[1]."/".$date[0];
    }

    public function convertDt($date){
        $date = explode('-',$date);
        return $date[2]."-".$date[1]."-".$date[0];
    }

    // Upload image
    public function uploadImage($image,$path){

        $imagedata = $image;
        $destinationPath = 'uploads/'.$path;
        $extension = $imagedata->getClientOriginalExtension(); 
        $fileName = rand(1,999999).date('dMYHis').'.'.$extension;
        $imagedata->move($destinationPath, $fileName);
        
        return $fileName;
    }

}
