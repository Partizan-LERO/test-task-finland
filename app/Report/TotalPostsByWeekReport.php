<?php

namespace App\Report;


use App\Repository\IPostRepository;
use DateTime;
use Exception;
use Framework\Cache\ICache;

class TotalPostsByWeekReport implements ReportInterface
{

    private ICache $cache;
    private IPostRepository $postRepository;

    /**
     * TotalPostsByWeekReport constructor.
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
     * @throws Exception
     */
    public function generate(): array
    {
        $cacheData = $this->cache->get(__CLASS__);
        if ($cacheData) {
            return $cacheData;
        }

        $posts = $this->postRepository->all();

        $report = $this->calcNumberByWeek($posts);

        $this->cache->set(__CLASS__, $report);

        return $report;
    }

    /**
     * @param $posts
     * @return array
     * @throws Exception
     */
    private function calcNumberByWeek($posts): array
    {
        $report = [];

        for($i = 1; $i <= 53; $i++) {
            $report[$i] = 0;
        }

        foreach ($posts as $post) {
            $date = new DateTime($post->created_at);
            $week = (int)$date->format('W');

            $report[$week]++;
        }

        return $report;
    }
}
