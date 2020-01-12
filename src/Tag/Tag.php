<?php

namespace linder\Tag;

use linder\Model\ActiveRecordExtension;

/**
 * A database driven model using the Active Record design pattern.
 */
class Tag extends ActiveRecordExtension
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
                 ->execute("DELETE FROM tag WHERE tagId NOT IN (SELECT tagId FROM tag2post)");

        return true;
    }
}
