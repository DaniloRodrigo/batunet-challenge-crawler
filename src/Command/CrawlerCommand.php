<?php
// src/AppBundle/Command/GreetCommand.php
namespace App\Command;

use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlInternalUrls;
use App\Observer\AllPagesCrawlerObserver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CrawlerCommand extends Command
{

    protected static $defaultName = 'app:crawler';
    protected $publicDirectory, $crawledDirectory;

    function __construct($publicDirectory){
        $this->publicDirectory = $publicDirectory;
        $this->crawledDirectory = $publicDirectory . 'crawled_pages/';

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Crawl all links on a page')
            ->addArgument(
                'url',
                InputArgument::REQUIRED,
                'Website url to crawl'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            Crawler::create() // Initializing Spatie Crawler
                ->setCrawlObserver(new AllPagesCrawlerObserver($output, parse_url($url)['host'], $this->crawledDirectory)) // Set the Observer to the class created by me to do the crawler
                ->setCrawlProfile(new CrawlInternalUrls($url)) // Using the provided profile to ignore external urls
                ->startCrawling($url); // starting the crawler with the passed parameter
        } else {
            $output->writeln("The provided $url is not valid !");
        }
    

        
    }
}