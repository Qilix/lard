<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Lard\Helpers\CommentHelper;
use Lard\Model\Comment;
use Lard\Queries\CommentQueries;


function timeElapsedString($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $w = floor($diff->d / 7);
    $diff->d -= $w * 7;
    $string = ['y' => 'year', 'm' => 'month', 'w' => 'week', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second'];
    foreach ($string as $k => &$v) {
        if ($k == 'w' && $w) {
            $v = $w . ' week' . ($w > 1 ? 's' : '');
        } else if (isset($diff->$k) && $diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function showComments($comments, $parent_id = -1)
{
    $html = '';
    $helper = new CommentHelper;
    if ($parent_id != -1) {
        array_multisort(array_column($comments, 'created_at'), SORT_ASC, $comments);
    }
    foreach ($comments as $comment) {
        $limit = $helper->checkChildsCommentsLimit($comments, $comment, 1);
        if ($comment['parent_id'] == $parent_id) {
            $html .= '
            <div class="comment">
                <div>
                    <h3 class="name">' . $comment['name'] . '</h3>
                    <span class="date">' . timeElapsedString($comment['created_at']) . '</span>
                </div>
                <p class="content">' . nl2br($comment['comment']) . '</p>
                <a class="reply_comment_btn" href="#" data-comment-id="' . $comment['id'] . '">Ответить</a>
                ' . showWriteCommentForm($comment['id']) . '
                <a class="edit_comment_btn" href="#" data-edit-comment-id="' . $comment['id'] . '">Изменить</a>
                ' . showEditCommentForm($comment) .
                ($limit ? '' : '<span class="limit_comments">Макс.</span>') . '
                <div class="replies">
                ' . showComments($comments, $comment['id']) . '
                </div>
            </div>
            ';
        }
    }
    return $html;
}

function showWriteCommentForm($parent_id = -1)
{
    $html = '
    <div class="write_comment" data-comment-id="' . $parent_id . '">
        <form>
            <input name="parent_id" type="hidden" value="' . $parent_id . '">
            <input name="name" type="text" placeholder="Имя" required>
            <textarea name="comment" placeholder="Оставьте комментарий..." required></textarea>
            <button type="submit">Отправить</button>
        </form>
    </div>
    ';
    return $html;
}

function showEditCommentForm($comment)
{
    $html = '
    <div class="edit_comment" data-edit-comment-id="' . $comment['id'] . '">
        <form>
            <input name="id" type="hidden" value="' . $comment['id'] . '">
            <input name="parent_id" type="hidden" value="' . $comment['parent_id'] . '">
            <input name="name" type="text" value="' . $comment['name'] . '"placeholder="Имя" required>
            <textarea name="comment" value placeholder="Оставьте комментарий..." required>' . $comment['comment'] . '</textarea>
            <button type="reset">Отменить</button>
            <button type="submit">Сохранить</button>
        </form>
    </div>
    ';
    return $html;
}

?>

<div class="comment_header">
    <span class="total">Комментарии</span>
    <a href="#" class="write_comment_btn" data-comment-id="-1">Оставить комментарий</a>
</div>


<?php
$comments = Comment::getComments();
if (isset($_GET)) {
    echo showWriteCommentForm();
    echo showComments($comments);
}
if (isset($_POST['createComment'])) {
    $helper = new CommentHelper;
    $limit = $helper->checkChildsCommentsLimit($comments, $_POST);
    if ($limit) {
        CommentQueries::createCommentQuery($_POST);
    }
}
if (isset($_POST['updateComment'])) {
    CommentQueries::updateCommentQuery($_POST);
}
?>