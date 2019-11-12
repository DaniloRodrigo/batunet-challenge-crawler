<?php

namespace App\Observer;

use DateTime;
use DOMDocument;
use Spatie\Crawler\CrawlObserver;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class AllPagesCrawlerObserver extends CrawlObserver{

    private $output;
    private $pages = [];
    private $path;
    private $crawledDirectory;

    function __construct($output, $path, $crawledDirectory) {
        $this->output = $output;
        $this->path = $path;
        $this->crawledDirectory = $crawledDirectory;
    }

    public function willCrawl(UriInterface $url){
        $this->output->writeln("Starting crawling $url ...");
    }

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null){
        /* The Spatie Crawler will visit all pages on the providaded URL by default */

        $path = $url->getPath(); // get path
        $doc = new DOMDocument(); // initialize DOMDocument
        @$doc->loadHTML($response->getBody()); // load the HTML from the responder provided by Spatie
        $hrefs = $doc->getElementsByTagName('a'); // find all link
        $this->pages[$path] = []; // itializate an empty array for each new link
        // add all hrefs to the created array
        foreach($hrefs as $link) {
            array_push($this->pages[$path], [$link->nodeValue => $link->getAttribute('href')]);
        }

    }


    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null){
        $this->output->writeln("Failed to crawling  $url ");
    }

    public function finishedCrawling() {
        // save all the pages into a file with the page name and current time on the public/crawled_pages folder
        $date = new DateTime('NOW');
        $fp = fopen($this->crawledDirectory . $this->path . '-' .$date->format('c') . '.json', 'w');
        fwrite($fp, json_encode($this->pages));
        fclose($fp);
        // print formated array of pages
        dd($this->pages);
    }
}