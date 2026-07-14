<?php
namespace App\Libraries\HelperClass;

use App\Models\Admin\SiteConfig;
use App\Models\Admin\News;
use App\Models\Admin\Staffs;
use App\Models\Admin\NewsCategory;
use Carbon\Carbon;


class ViewHelper
{

    public static function getAssetPath($path, $asset_type){

        $asset_path = config('myPath.assets.theme.panel.'.$asset_type);

        return asset($asset_path.$path);
    }

    public static function getFrontAssetPath($path, $asset_type){

        $asset_path = config('myPath.assets.theme.frontEnd.'.$asset_type);

        return asset($asset_path.$path);
    }

    public static function getImagePath($folder, $image_name)
    {
        if(file_exists(public_path().DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$image_name) && !is_null($image_name))
        {
            return asset('images/'.$folder.'/'.$image_name);
        }
        return asset('images/no_image.png');
    }

    public static function convertNumberEnToNp($english_number)
    {
        $english_number_array = str_split($english_number);
        $nepali_number = '';
        foreach ($english_number_array as  $num) {
            switch ($num) {
                case "0": $nepali_number .= "०"; break;
                case "1": $nepali_number .= "१"; break;
                case "2": $nepali_number .= "२"; break;
                case "3": $nepali_number .= "३"; break;
                case "4": $nepali_number .= "४"; break;
                case "5": $nepali_number .= "५"; break;
                case "6": $nepali_number .= "६"; break;
                case "7": $nepali_number .= "७"; break;
                case "8": $nepali_number .= "८"; break;
                case "9": $nepali_number .= "९"; break;
                default : $nepali_number .= $num; break;
            }
        }
        return $nepali_number;
    }

    public static function convertNumberNpToEn($nepali_number)
    {
        $nepali_number_array =[];
        preg_match_all('/./u', $nepali_number, $nepali_number_array);
        $english_number = '';
        foreach ($nepali_number_array[0] as  $num) {
            switch ($num) {
                case "०": $english_number .= "0"; break;
                case "१": $english_number .= "1"; break;
                case "२": $english_number .= "2"; break;
                case "३": $english_number .= "3"; break;
                case "४": $english_number .= "4"; break;
                case "५": $english_number .= "5"; break;
                case "६": $english_number .= "6"; break;
                case "७": $english_number .= "7"; break;
                case "८": $english_number .= "8"; break;
                case "९": $english_number .= "9"; break;
                default : $english_number .= $num; break;
            }
        }
        return $english_number;
    }

    public static function convertDurationToNepali($duration) {

        $viewHelper = New ViewHelper;
        $duration = $viewHelper->convertNumberEnToNp($duration);
        $translations = [
            'hours' => 'घण्टा',
            'hour' => 'घण्टा',
            'minutes' => 'मिनेट',
            'minute' => 'मिनेट',
        ];

        foreach ($translations as $en => $np) {
            $duration = str_replace($en, $np, $duration);
        }
        return $duration;
    }


    public static function printLimitString($x, $length)
    {
      if(strlen($x)<=$length)
      {
        return $x;
      }
      else
      {
        $y=substr($x,0,$length) . '...';
        return $y;
      }
    }


    public static function getSiteInfo()
    {

        $site_info = SiteConfig::pluck('config_values', 'config_keys');

        return [
                'general_info' => $site_info,
            ];
    }

    public static function getLatestDatas()
    {

        $latesthighlights = News::where('status', 1)->where('is_highlight', '1');
        
        $latesthighlights = $latesthighlights->where(function ($query){
            $query->whereDate('archive_at', '>', Carbon::now())->orWhereNull('archive_at');
        });
        $latesthighlights = $latesthighlights->orderBy('published_at', 'desc')->orderBy('created_at', 'desc')->take(5)->get();


        $highlight_string ='';
        foreach ($latesthighlights as $highlight) {
            $highlight_string .= "<a href=".route('news-show', ['category'=>$highlight->newsCategory()->first()->slug, 'slug'=>$highlight->slug]).">";
            $highlight_string .= \App::isLocale('en') ? $highlight->title_en : $highlight->title_np ;
            $highlight_string .= "</a> || ";

        }

        $latestnews = News::latestNews(5)->get();
        $latestuploads = News::latestFiles(5)->get();
        return['news' => $latestnews, 'files' => $latestuploads, 'highlight_string'=> $highlight_string];
    }

    public static function extractVideoId($url)
    {   
        $parsedurl = parse_url($url, PHP_URL_QUERY);
        parse_str($parsedurl, $params);
        if (isset($params['v'])) {
            return $params['v'];
        }
        return null;
    }

    public static function sendSMS($to, $text)
    {
        if(config('custom.sms_notice'))
        {
            if((config('custom.sms_provider') && config('custom.sms_provider') == 'aakash_sms'))
            {
                $args = http_build_query(array(
                    'auth_token' => config('custom.sms_token'),
                    'to'    => $to,
                    'text'  => $text.' -'.config('custom.sms_from')));
        
                    $url = "https://sms.aakashsms.com/sms/v3/send/";
        
                    # Make the call using API.
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1); ///
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                    // Response
                    $response = curl_exec($ch);
                    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);    

                    return ['response'=> $response, 'status_code'=>$status_code];

            }
            elseif(config('custom.sms_provider') && config('custom.sms_provider') == 'sparrow_sms')
            {
                $args = http_build_query(array(
                'token' => config('custom.sms_token'),
                'from'  => config('custom.sms_from'),
                'to'    => $to,
                'text'  => $text.' -'.config('custom.sms_from')));

                $url = "http://api.sparrowsms.com/v2/sms/";

                # Make the call using API.
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                // Response
                $response = curl_exec($ch);
                $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                return ['response'=> $response, 'status_code'=>$status_code]; 
            }
            else
            {
                return ['response'=> [], 'status_code'=>'200'];
            }
        }
        return true ;

    }

    public static function parse_size($size)
    {
        $unit = preg_replace('/[^kmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
        // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
        return round($size * pow(1024, stripos('kmgtpezy', $unit[0])));
        }
        else {
        return round($size);
        }
    }
}
