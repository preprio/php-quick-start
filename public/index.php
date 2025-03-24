<?php
require '../vendor/autoload.php';

use Preprio\Prepr;

$apiRequest = new Prepr('https://graphql.prepr.io/ac_8a73ce93e85c18ccec497b81cf8a6458a8cee50c50fbbda897bb9cee07e1eba0');

echo '<div>';

    if(!isset($_GET['slug'])) {

        echo '<h1>My blog site</h1>
    
        <ul>';

            $apiRequest
                ->query('../queries/get-posts.graphql')
                ->request();

            $apiResponse = $apiRequest->getResponse();

            $posts = $apiResponse['data']['Posts']['items'];
            if ($posts) {

                foreach ($posts as $post) {

                    echo '<li>
                        <a href="' . $_SERVER['REQUEST_URI'] . '?slug='.$post['_slug'].'">'.$post['title'].'</a>
                    </li>';
                }
            }

        echo '</ul>';

    } else {

        $apiRequest
            ->query('../queries/get-post-by-slug.graphql')
            ->variables([
                'slug' => $_GET['slug']
            ])
            ->request();

        $apiResponse = $apiRequest->getResponse();

        $post = $apiResponse['data']['Post'];
        if($post) {

            echo '<h1>' . $post['title'] . '</h1>';

            if($post['content']) {
                foreach($post['content'] as $content) {

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
