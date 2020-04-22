<?php
namespace app\helpers;

use yii\httpclient\Client;
use DOMWrap\Document;
use app\models\Site;
use app\models\News;

class FeedHelper
{

    public static function checkUrl($url)
    {
        $client = new Client();
        $response = $client->createRequest()->setMethod('GET')->setUrl($url)->send();
        if ($response->isOk) {
            if (stripos($response->headers['content-type'], 'text/xml') !== false) {
                // This is Xml feed
                return $url;
            }
            
            if (stripos($response->headers['content-type'], 'text/html') !== false) {
                // This is HTML, parse for find RSS Feed
                return self::searchFeed($response);
            }
        }
        
        return false;
    }

    public static function searchFeed(\yii\httpclient\Response $response)
    {
        $doc = new Document();
        $doc->html($response->content);
        
        $nodes = $doc->find('link[rel="alternate"][type="application/rss+xml"]');
        if ($nodes->count() && $nodes->first()->getAttr('href'))
            return trim($nodes->first()->getAttr('href'), '/');
        
        return false;
    }

    public static function parseFeed(\app\models\Site $site)
    {
        $xml = simplexml_load_file($site->feed);
        
        if (! $xml)
            return false;
        
        if (! empty($xml->channel->item)) {
            foreach ($xml->channel->item as $item) {
                $news = News::findOne([
                    'link' => $item->link->__toString()
                ]) ?  : new News();
                
                $news->site_id = $site->id;
                $news->title = $item->title->__toString();
                $news->link = $item->link->__toString();
                $news->description = $item->description->__toString();
                
                $news->save();
            }
        }
    }
}