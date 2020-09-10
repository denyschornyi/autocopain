<?php

use App\PromocodeUsage;
use Illuminate\Support\Facades\Storage;

function currency($value = '') {
	if($value == ""){
		return number_format(0, 2, '.', ''). Setting::get('currency');
	} else {
		return number_format($value, 2, '.', ''). Setting::get('currency');
	}
}

function distance($value = '') {
    if ($value == "") {
        return "0" . Setting::get('distance', 'Km');
    } else {
        return $value . Setting::get('distance', 'Km');
    }
}

function appDate($value) {
	return date('d-m-Y', strtotime($value));  
}

function appDateTime($value) {
	return date('d-m-Y H:i:s', strtotime($value));  
}

function img($img) {
    if ($img == "") {
        return asset('main/avatar.jpg');
    } else if (strpos($img, 'http') !== false) {
        return $img;
    } else {
        return asset('storage/' . $img);
    }
}

function img_new($img) {
    if ($img == "") {
        return asset('main/avatar.jpg');
    } else if (strpos($img, 'http') !== false) {
        return $img;
    } else {
        return Storage::url($img);
    }
}

function promo_used_count($promo_id) {
    return PromocodeUsage::where('status', 'USED')->where('promocode_id', $promo_id)->count();
}

function curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $return = curl_exec($ch);
    curl_close($ch);
    return $return;
}
