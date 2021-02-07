<?php


namespace App\Repository;


use App\Entity\Post;

class PostRepository
{
    /**
     * @param  array  $row
     * @return Post
     */
    public function fillThePostObject(array $row): Post
    {
        $post = new Post();
        $post->id = $row['id'] ?? null;
        $post->text = $row['text'];
        $post->created_at = $row['created_at'];
        $post->user_id = $row['user_id'];
        $post->user_name = $row['user_name'];

        return $post;
    }
}
