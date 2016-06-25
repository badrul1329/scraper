<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Sunra\PhpSimple\HtmlDomParser;

ini_set('max_execution_time', 300);
define('MAX_FILE_SIZE', 900000);

class homeController extends Controller {

    public function index() {
//Placed hotel names
//        $url = 'https://www.tripadvisor.com';
//        $html = HtmlDomParser::file_get_html($url);
//        $ret = $html->find('div[class=featured wrap]');
//        foreach ($ret as $div) {
//            $categoryinfo = $div->find('li[class=sprite-middot]');
//            foreach ($categoryinfo as $data) {
//                $categoryName = $data->plaintext;
//                $categoryLink = $url . $data->first_child()->href;
//                $categories[] = array('categoryName' => $categoryName, 'categoryLink' => $categoryLink);
//            }
//        }
//        $result['categories'] = $categories;

//get hotelinfo from category
        $url = 'https://www.tripadvisor.com/Hotels-g29092-Anaheim_California-Hotels.html';

        $domain = parse_url($url, PHP_URL_HOST);

        do {
            $html = HtmlDomParser::file_get_html($url);
            $ret = $html->find('div[class=listing_info]');
            foreach ($ret as $div) {

                $hotelName = $div->find('div[class=listing_title]', 0)->first_child()->plaintext;
                $hotelLink = 'https://' . $domain . $div->find('div[class=listing_title]', 0)->first_child()->href;
                if (!empty($div->find('[class=more]', 0)->plaintext)) {
                    $hotelReviews = $div->find('[class=more]', 0)->plaintext;
                } else {
                    $hotelReviews = '0 Reviews';
                }
                if (!empty($div->find('img[class=sprite-ratings]', 0)->alt)) {
                    $hotelRate = $div->find('img[class=sprite-ratings]', 0)->alt;
                } else {
                    $hotelRate = '0 of 5 stars';
                }
                $Tags = array();
                foreach ($div->find('a[class=tag]') as $data) {
                    $Tags[] = $data->plaintext;
                }
                if(count($Tags)>0){
                    $hotelTags = $Tags;
                }else{
                    $hotelTags = 'No Tags';
                }
                
                unset($Tags);
                $hotels[] = array('hotelName' => $hotelName, 'hotelLink' => $hotelLink, 'hotelReviews' => $hotelReviews, 'hotelRate' => $hotelRate, 'hotelTags' => $hotelTags);
            }

            if (!empty($html->find('div[class=pagination]', 0)->childNodes(1)->href)) {
                $url = 'https://' . $domain . $html->find('div[class=pagination]', 0)->childNodes(1)->href;
            }
        } while (!empty($html->find('div[class=pagination]', 0)->childNodes(1)->href));

        $result['hotels'] = $hotels;

        return view('scraper', compact('result'));
    }

}
