<?php
require '../vendor/autoload.php';

use Preprio\Prepr;

$apiRequest = new Prepr('https://graphql.prepr.io/7f05e9e2f17f1b3d5e08dab3a565acbea8b87745473917e159f70ae1cf0334b9');

echo '<div>';

    if(!isset($_GET['slug'])) {

        echo '<h1>My blog site</h1>
    
        <ul>';

            $apiRequest
                ->query('../queries/get-articles.graphql')
                ->request();

            $apiResponse = $apiRequest->getResponse();

            $articles = $apiResponse['data']['Articles']['items'];
            if ($articles) {

                foreach ($articles as $article) {

                    echo '<li>
                        <a href="' . $_SERVER['REQUEST_URI'] . '?slug='.$article['_slug'].'">'.$article['title'].'</a>
                    </li>';
                }
            }

        echo '</ul>';

    } else {

        $apiRequest
            ->query('../queries/get-article-by-slug.graphql')
            ->variables([
                'slug' => $_GET['slug']
            ])
            ->request();

        $apiResponse = $apiRequest->getResponse();

        $article = $apiResponse['data']['Article'];
        if($article) {

            echo '<h1>' . $article['title'] . '</h1>';

            if($article['content']) {
                foreach($article['content'] as $content) {

                    if($content['__typename'] === 'Assets') {

                        echo '<div class="my-10">
                            <img src="' . $content['items'][0]['url'] . '" width="300" height="250"/>
                        </div>';

                    } elseif($content['__typename'] === 'Text') {

                        echo '<div>
                            ' . $content['body'] . '
                        </div>';

                    }
                }
            }
        }
    }

echo '</div>';
