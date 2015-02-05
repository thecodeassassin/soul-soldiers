<?php
namespace Soul\Controller\Website;

use Soul\Controller\Base;
use Twitter;

/**
 * Class IndexController
 *
 * @package Soul\Controller
 *
 */
class IndexController extends Base
{

    protected $hasNews = true;

    /**
     * Index action
     */
    public function indexAction()
    {
        $cache = $this->getCache();


        if ($cache->exists('twitter_feed')) {
            $filteredPosts = $cache->get('twitter_feed');
        } else {

            $twitter = new Twitter($this->config->twitter->consumerKey,
                $this->config->twitter->consumerSecret,
                $this->config->twitter->token,
                $this->config->twitter->tokenSecret
            );
            $filteredPosts = [];
            $twitterPosts = $twitter->load();

            foreach ($twitterPosts as $post) {

                $tmpPost = new \stdClass();
                $tmpPost->text = Twitter::clickable($post);
                $tmpPost->date = date('Y-m-d H:i', strtotime($post->created_at));

                $filteredPosts[] = $tmpPost;
            }

            // save the twitter feed for 30 minutes
            $cache->save('twitter_feed', $twitterPosts, 1800);
        }


        $this->view->twitterPosts = $filteredPosts;

    }

    /**
     * News action
     */
    public function newsAction()
    {

    }

}