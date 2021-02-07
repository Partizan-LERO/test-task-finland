<?php

namespace App\Report;

use App\Repository\IPostRepository;
use Framework\Cache\ICache;

class AvgPostsCountPerUserPerMonthReport implements ReportInterface
{
    private ICache $cache;
    private IPostRepository $postRepository;

    /**
     * AvgPostsCountPerUserPerMonthReport constructor.
     * @param  IPostRepository  $postRepository
     * @param  ICache  $cache
     */
    public function __construct(IPostRepository $postRepository, ICache $cache)
    {
        $this->postRepository = $postRepository;
        $this->cache = $cache;
    }

    /**
     * @return array
     */
    public function generate(): array
    {
        $cacheData = $this->cache->get(__CLASS__);
        if ($cacheData) {
            return $cacheData;
        }

        $posts = $this->postRepository->all();

        $report = $this->calcAvgPostsCount($posts);

        $this->cache->set(__CLASS__, $report);

        return $report;
    }

    /**
     * @param $posts
     * @return array
     */
    private function calcAvgPostsCount($posts): array
    {
        if (count($posts) === 0) {
            return [];
        }

        $months = [];
        $usersPostsCount = [];

        foreach ($posts as $post) {
            $months[date('m', strtotime($post->created_at))] = $post->created_at;
            if (array_key_exists($post->user_id, $usersPostsCount)) {
                $usersPostsCount[$post->user_id]['user_name'] = $post->user_name;
                $usersPostsCount[$post->user_id]['count']++;
            } else {
                $usersPostsCount[$post->user_id]['user_name'] = $post->user_name;
                $usersPostsCount[$post->user_id]['count'] = 1;
            }
        }

        $avg = [];

        foreach ($usersPostsCount as $userId => $userPostsCount) {
            $reportTemplate = new \stdClass();
            $reportTemplate->user_id = $userId;
            $reportTemplate->user_name = $userPostsCount['user_name'];
            $reportTemplate->count = round($userPostsCount['count'] / count($months), 2);
            $avg[] = $reportTemplate;
        }

        return $avg;
    }
}
