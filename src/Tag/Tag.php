<?php

namespace linder\Tag;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Tag extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Tag";
    protected $tableIdColumn = "tagId";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $tagId;
    public $tag;

    public function cleanTags()
    {
        $this->checkDb();
        $this->db->connect()
                //  ->deleteFrom($this->tableName)
                //  ->where("tagId NOT IN")
                //  ->select($this->tableIdColumn)
                //  ->from("tag2post")
                 ->execute("DELETE FROM tag WHERE tagId NOT IN (SELECT tagId FROM tag2post)");

        return true;
    }
}
