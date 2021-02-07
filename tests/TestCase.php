<?php

namespace Tests;

use App\Entity\Post;
use App\Repository\SqlPostRepository;
use PHPUnit\Framework\TestCase as PhpUnit;

class TestCase extends PhpUnit
{
    public function getDummyPosts(int $count)
    {
        $posts = [];

        for ($i = 1; $i <= $count; $i++) {
            $posts[] = $this->buildPost((string)$i, 'text', (string)$i, 'user ' . $i, '2020-01-01');
        }

        return $posts;
    }

    public function buildPost(string $id, string $text, string $userId, string $userName, string $created_at): Post
    {
        $post = new Post();
        $post->id = $id;
        $post->user_id = $userId;
        $post->user_name = $userName;
        $post->text = $text;
        $date = new \DateTime($created_at);
        $post->created_at = $date->format(SqlPostRepository::DATE_FORMAT);

        return $post;
    }

}
