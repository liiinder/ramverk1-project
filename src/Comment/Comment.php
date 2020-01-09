<?php

namespace linder\Comment;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Comment extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Comment";
    protected $tableIdColumn = "commentId";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $commentId;
    public $postId;
    public $userId;
    public $replyId;
    public $text;

    /**
     * formats comments and gives html respond
     *
     * @param array $comments array of comments
     *
     * @return array
     */
    public function sort($comments)
    {
        $res = [];
        foreach ($comments as $comment)
        {
            if ($comment->replyId == null) {
                $comment->depth = 1;
                array_push($res, $comment);
            } else {
                for ($i = 0; $i < sizeof($res); $i++){
                    if ($res[$i]->commentId == $comment->replyId) {
                        $comment->depth = $res[$i]->depth + 1;
                        array_splice($res, $i+1, 0, [$comment]);
                    }
                }
            }
        }
        return $res;
    }

    /**
     * Overwrites the ActiveRecord findAllWhere
     * so it includes the join on each search.
     * 
     * @param string table
     * @param string value to search for
     *
     * @return array of object of this class
     */
    public function findAllWhere($where, $value)
    {
        return $this->findAllWhereJoin(
            $where . " = ?",
            $value,
            "user",
            "user.userId = comment.userId"
        );
    }
}
