<?php

namespace Lard\Model;

use Lard\Database\DB;

class Comment extends DB
{

    public static function setComment($comment = '', $name, $parent_id = -1)
    {
        $conn = DB::getConnect();
        $stmt = $conn->prepare('INSERT INTO comments (comment, name, created_at, updated_at, parent_id) VALUES (:comment, :name, :created_at, :updated_at, :parent_id)');
        $stmt->bindValue(':comment', $comment);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':created_at', date('Y-m-d H:i:s'));
        $stmt->bindValue(':updated_at', date('Y-m-d H:i:s'));
        $stmt->bindValue(':parent_id', $parent_id);
        return $stmt->execute();
    }

    public static function getComments()
    {
        $conn = DB::getConnect();
        $sth = $conn->query('SELECT * FROM comments');

        return $sth->fetchAll();
    }

    public static function getChildComments($parent_id)
    {
        $conn = DB::getConnect();
        $sth = $conn->query('SELECT * FROM comments WHERE parent_id = ' . (int)$parent_id);

        return $sth->fetchAll();
    }

    public static function updateComment($id, $name, $comment)
    {
        $conn = DB::getConnect();
        $stmt = $conn->prepare("UPDATE comments SET name=:name, comment=:comment, updated_at=:updated_at WHERE id=:id");
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':comment', $comment);
        $stmt->bindValue(':updated_at', date('Y-m-d H:i:s'));
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }
}