<?php

namespace App\Report;

use App\Policy\CalendarPolicy;
use App\Repository\IPostRepository;
use Framework\Cache\ICache;

class AvgLengthOfPostsPerMonthReport implements ReportInterface
{
    private ICache $cache;
    private IPostRepository $postRepository;

    /**
     * AverageLengthOfPostsPerMonthReport constructor.
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
            return (array)$cacheData;
        }

        $months = CalendarPolicy::getMonths();

        $report = [];

        foreach ($months as $number => $month) {
            $posts = $this->postRepository->getByMonth($number);
            $report[$month] = $this->calcTheAverageLengthOfPosts($posts);
        }

        $this->cache->set(__CLASS__, $report);

        return $report;
    }

    /**
     * @param  array  $posts
     * @return int
     */
    private function calcTheAverageLengthOfPosts(array $posts): int
    {
        if (count($posts) === 0) {
            return 0;
        }

        $totalLength = 0;

        foreach ($posts as $post) {
            $totalLength += strlen($post->text);
        }

        return round($totalLength / count($posts), 2);
    }
}
