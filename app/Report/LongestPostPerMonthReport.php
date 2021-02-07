<?php

namespace App\Report;

use App\Policy\CalendarPolicy;
use App\Repository\IPostRepository;
use Framework\Cache\ICache;

class LongestPostPerMonthReport implements ReportInterface
{
    private ICache $cache;
    private IPostRepository $postRepository;

    /**
     * LongestPostPerMonthReport constructor.
     * @param  IPostRepository  $postRepository
     * @param  ICache  $cache
     */
    public function __construct(IPostRepository $postRepository, ICache $cache)
    {
        $this->postRepository = $postRepository;
        $this->cache = $cache;
    }

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
            $report[$month] = $this->getTheLongestPost($posts);
        }

        $this->cache->set(__CLASS__, $report);

        return $report;
    }

    /**
     * @param  array  $posts
     * @return int
     */
    private function getTheLongestPost(array $posts): int
    {
        $max = 0;

        foreach ($posts as $post) {
            $postLength = strlen($post->text);

            if ($postLength > $max) {
                $max = $postLength;
            }
        }

        return $max;
    }
}
