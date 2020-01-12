<?php

namespace linder\Post;

use linder\Model\ActiveRecordExtension;

/**
 * A database driven model using the Active Record design pattern.
 */
class Post extends ActiveRecordExtension
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Post";
    protected $tableIdColumn = "postId";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $postId;
    public $userId;
    public $text;
    public $title;

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
            "user.userId = post.userId"
        );
    }

    public function findLatest()
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from($this->tableName)
                        ->join("user", "user.userId = post.userId")
                        ->orderBy("post.postId DESC")
                        ->limit("3")
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }
}
