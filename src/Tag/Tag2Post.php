<?php

namespace linder\Tag;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Tag2Post extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "tag2post";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $tagId;
    public $postId;

    /**
     * Join + find and return all.
     *
     * @param string $table what table to join.
     * @param string $condition what to join on.
     * 
     * @return array of object of this class
     */
    public function findTags()
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select("*, count(tag2post.tagId) as amount")
                        ->from($this->tableName)
                        ->join("tag", "tag2post.tagId = tag.tagId")
                        ->groupBy("tag.tagId")
                        ->orderBy("amount DESC")
                        ->limit("10")
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }

    public function findTagsWhere($where, $value)
    {
        $this->checkDb();
        $params = is_array($value) ? $value : [$value];
        return $this->db->connect()
                        ->select()
                        ->from($this->tableName)
                        ->where($where . " = ?")
                        ->join("post", "tag2post.postId = post.postId")
                        ->join("tag", "tag2post.tagId = tag.tagId")
                        ->execute($params)
                        ->fetchAllClass(get_class($this));
    }
}
