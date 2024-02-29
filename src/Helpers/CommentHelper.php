<?php

namespace Lard\Helpers;

class CommentHelper
{
    public function checkChildsCommentsLimit($comments, $data, $add = 0)
    {
        $level = 0 + $add;
        $parent_id = (int)$data['parent_id'];
        while ($parent_id !== -1) {
            if ($level >= 10) {
                return false;
            }
            $parentInd = array_search($parent_id, array_column($comments, 'id'));
            $parent_id = $comments[$parentInd]['parent_id'];
            $level++;
        }
        return true;
    }
}
