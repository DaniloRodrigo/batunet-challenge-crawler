# Batunet Software Developer Teste (Crawler)

This crawler was developed with Symfony Framework and Spatie Crawler for the Batunet developer test. The app works in a command line format receiving one parameter that is the url for crawling. The requirements and usage will be detailed ahead.


## Requirements
In order to run this application is necessary meet the following requirements:
1. PHP 7.X
2. PHP XML 7.X
3. Symfony 4.2
4. Spatie Crawler
5. Composer

## Installation

**Cloning project:**\
$ git clone https://github.com/DaniloRodrigo/batunet-challenge-crawler

**Installing requirements:**\
$ composer install \



> Note: All the requirement must to be meet before run the composer install command.

## Run project

In order to test the app run the following command:

$ php bin/console app:crawler url

Where the "url" is the address of the site the you aim to crawling.

### Output

The output of the app will be displayed on the terminal and saved as a json file under the public/crawled_pages folder.
