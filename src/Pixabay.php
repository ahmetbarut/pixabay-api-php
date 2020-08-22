<?php

namespace Pixabay;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\TransferStats;
class Client 
{
    private $api_url = "https://pixabay.com";

    /**
    * @var TransferStats\getEffectiveUri() $url 
    */
    private $url;

    /**
    * @var \GuzzleHttp\Client $client
    */
    private $client;

    /** 
    * @var array $config
    */
    private $config;

    /**
    * @param array $config
    * @return void
    */
    public function __construct(array $config = [])
    {
        $this->config = array_merge([

            "videos" => false,
            /**
             * Eğer bu seçenek true olursa videoları getirir
             * Kabul edilen değerler : true, false
             * Varsayılan : false
             */

            "key" => null,
            /**
             * api key
             */

            "q" => null,
            /**
             * URL kodlu arama terimi. 
             * Atlanırsa, tüm görüntüler döndürülür. 
             * Bu değer 100 karakteri geçemez.
             * örnek : sarı+çiçek 
             * !! Türkçe için $this->lang("tr") yapmanız gereklidir.
             */

            "lang" => "en",
            /**
             * Aranacak dilin dil kodu. 
             * Kabul edilen değerler: cs, da, de, en, es, fr, 
             *                      id, it, hu, nl, no, pl, pt, 
             *                      ro, sk, fi, sv, tr, vi, th, 
             *                      bg, ru, el, ja , ko, zh
             * Varsayılan : en
             */

            "id" => null,
            /**
             * Tek tek görüntüleri kimliğe göre alın.
             */

            "image_type" => "all",
            /**
             * Sonuçları görüntü türüne göre filtreleyin.
             * Kabul edilen değerler: "all", "photo", "illustration", "vector"
             * Varsayılan: "all"
             */

            "orientation" => "all",
            /**
             * Bir görüntünün, uzun olduğundan daha geniş 
             * veya daha uzun olup olmadığı.
             * Kabul edilen değerler: "all", "horizontal", "vertical"
             * Varsayılan: "all"
             */

            "category" => null,
            /**
             * Sonuçları kategoriye göre filtreleyin.
             * Kabul edilen değerler: backgrounds, fashion, nature, 
             *                      science, education, feelings, health, 
             *                      people, religion, places, animals, 
             *                      industry, computer, food, sports,transportation, 
             *                      travel, buildings, business, music
             */

            "min_width" => 0,
            /**
             *  Minimum görüntü genişliği. 
             *  Varsayılan: "0" 
             */

            "min_height" => 0,
            /**
             * Minimum görüntü yüksekliği.
             * varsayılan: "0"
             */

            "colors" => null,
            /**
             * Resimleri renk özelliklerine göre filtreleyin. 
             * Birden fazla özelliği seçmek için virgülle ayrılmış 
             * bir değerler listesi kullanılabilir.
             * Kabul edilen değerler: "grayscale", "transparent", "red", "orange", 
             *                          "yellow", "green", "turquoise", "blue", 
             *                          "lilac", "pink", "white", "gray", "black", "brown" 
             */

            "editors_choice" => false,
            /**
             * Editörün Seçimi ödülü almış resimleri seçin.
             * Kabul edilen değerler : true, false
             * Varsayılan : false
             */

            "safesearch" => false,
            /**
             * Yalnızca her yaşa uygun görsellerin 
             * iade edilmesi gerektiğini belirten bir işaret.
             * Kabul edilen değerler : true, false
             * Varsayılan : false
             */

            "order" => "popular",
            /**
             * Sonuçlar nasıl sıralanmalı.
             * Kabul edilen değerler: "popular", "latest"
             * Varsayılan: "popular"
             */

            "page" => 1,
            /**
             * Döndürülen arama sonuçları sayfalandırılır. 
             * Sayfa numarasını seçmek için bu parametreyi kullanın.
             * Varsayılan : 1
             */

            "per_page" => 20,
            /**
             * Sayfa başına sonuç sayısını belirleyin.
             * Kabul edilen değerler: 3 - 200
             * Varsayılan: 20
             */


        ], $config);


        $this->client = new GuzzleClient([
            "base_uri" => $this->api_url
        ]);
    }

    /**
     * @return \GuzzleHttp\Client\get($response)  
     */
    public function get()
    {
        $this->config['key'] !== null or die("Api Key Boş : " . (new Exception())->getFile() . ":" . (new Exception())->getLine());
        $response = $this->client->get((!$this->config['videos'])
            ? "/api"
            : "/api/videos", [
            "query" => [
                "key" => $this->config['key'],
                "q" => $this->config["q"],
                "id" => $this->config['id'],
                "image_type" => $this->config['image_type'],
                "orientation" => $this->config['orientation'],
                "category" => $this->config['category'],
                "min_width" => $this->config['min_width'],
                "min_height" => $this->config['min_height'],
                "colors" => $this->config['colors'],
                "editors_choice" => $this->config['editors_choice'],
                "safesearch" => $this->config['safesearch'],
                "order" => $this->config['order'],
                "per_page" => $this->config['per_page'],
            ],
            "on_stats" => function (TransferStats $stats) use (&$url) {
                $this->url = $stats->getEffectiveUri();
            },

        ]);
        return $response;
    }

    /**
     * Gövde sonuçlarını getirir
     * @return object
     */
    public function body()
    {
        return (object)json_decode($this->get()->getBody());
    }

    /** api key
     * @param string $apiKey
     */
    public function api_key(string $apiKey)
    {
        $this->config['key'] = $apiKey;
        return $this;
    }

    /** Eğer bu seçenek true olursa videoları getirir
     * @param bool $query
     * @return $this
     */
    public function videos(bool $videos)
    {
        $this->config['videos'] = $videos;
        return $this;
    }

    /** URL kodlu arama terimi.
     * @param string $query
     * @return $this
     */
    public function query(string $query)
    {
        $this->config['q'] = $query;
        return $this;
    }

    /** Tek tek görüntüleri kimliğe göre alın.
     * @param int $id
     * @return $this
     */
    public function id(int $id)
    {
        $this->config['id'] = $id;
        return $this;
    }

    /** Aranacak dilin dil kodu.
     * @param string $lang
     * @return $this
     */
    public function lang(string $lang)
    {
        $this->config['lang'] = $lang;
        return $this;
    }
 
    /** Sonuçları görüntü türüne göre filtreleyin.
     * @param string $image_type
     * @return $this
     */
    public function image_type(string $image_type)
    {
        $this->config['image_type'] = $image_type;
        return $this;
    }

    /** Bir görüntünün, uzun olduğundan daha geniş veya daha uzun olup olmadığı.
     * @param string $orientation
     * @return $this
     */
    public function orientation(string $orientation)
    {
        $this->config['orientation'] = $orientation;
        return $this;
    }

    /** Sonuçları kategoriye göre filtreleyin.
     * @param string $category
     * @return $this
     */
    public function category(string $category)
    {
        $this->config['category'] = $category;
        return $this;
    }

    /** Minimum görüntü genişliği.
     * @param int $min_width
     * @return $this
     */
    public function min_width(int $min_width)
    {
        $this->config['min_width'] = $min_width;
        return $this;
    }

    /** Minimum görüntü yüksekliği.
     * @param int $min_height
     * @return $this
     */
    public function min_height(int $min_height)
    {
        $this->config['min_height'] = $min_height;
        return $this;
    }

    /** Resimleri renk özelliklerine göre filtreleyin.
     * @param string $colors
     * @return $this
     */
    public function colors(string $colors)
    {
        $this->config['colors'] = $colors;
        return $this;
    }

    /** Editörün Seçimi ödülü almış resimleri seçin.
     * @param bool $editors_choice
     * @return $this
     */
    public function editors_choice(bool $editors_choice)
    {
        $this->config['editors_choice'] = $editors_choice;
        return $this;
    }

    /** Yalnızca her yaşa uygun görsellerin iade edilmesi gerektiğini belirten bir işaret.
     * @param bool $safesearch
     * @return $this
     */
    public function safesearch(bool $safesearch)
    {
        $this->config['safesearch'] = $safesearch;
        return $this;
    }

    /** Sonuçlar nasıl sıralanmalı.
     * @param string $order
     * @return $this
     */
    public function order(string $order)
    {
        $this->config['order'] = $order;
        return $this;
    }

    /** Döndürülen arama sonuçları sayfalandırılır
     * @param int $page
     * @return $this
     */
    public function page($page)
    {
        $this->config['page'] = $page;
        return $this;
    }

    /** Sayfa başına sonuç sayısını belirleyin
     * @param int $per_page
     * @return $this
     */
    public function per_page(int $per_page)
    {
        $this->config['per_page'] = $per_page;
        return $this;
    }
}