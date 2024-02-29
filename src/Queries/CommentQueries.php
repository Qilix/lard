<?php

namespace Lard\Queries;

use Lard\Model\Comment;

class CommentQueries
{
    public static function createCommentQuery($data)
    {
        $comment = $data['comment'];
        $name = $data['name'];
        $parent_id = $data['parent_id'];
        Comment::setComment($comment, $name, $parent_id);
    }
    public static function updateCommentQuery($data)
    {
        print_r($data);
        $id = $data['id'];
        $comment = $data['comment'];
        $name = $data['name'];
        Comment::updateComment($id, $name, $comment);
    }
}
