<?php


namespace App\Repository;


use App\Entity\Post;

class InMemoryPostRepository extends PostRepository implements IPostRepository
{
    public array $posts;

    public function __construct(array  $posts)
    {
        $this->posts = $posts;
    }

    public function all()
    {
        return $this->posts;
    }

    /**
     * @param  string  $monthNumber
     * @return array
     * @throws \Exception
     */
    public function getByMonth(string $monthNumber): array
    {
        $posts = [];

        /** @var Post $post */
        foreach ($this->posts as $post) {
            $date = new \DateTime($post->created_at);
            if ($date->format('m') === $monthNumber) {
                $posts[] = $post;
            }
        }

        return $posts;
    }

    public function getByUserId(string $userId)
    {
        $posts = [];

        /** @var Post $post */
        foreach ($this->posts as $post) {
            if ($post->user_id === $userId) {
                $posts[] = $post;
            }
        }

        return $posts;
    }

    public function save(Post $post): Post
    {
       $this->posts[] = $post;

       return $post;
    }

    public function update(Post $updatedPost): Post
    {
        foreach ($this->posts as $key => $post) {
            if ($post->id === $updatedPost->id) {
                $this->posts[$key] = $updatedPost;
            }
        }

        return $updatedPost;
    }
}
