<?php

namespace linder\Model;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * An implementation of the Active Record pattern to be used as
 * base class for database driven models.
 */
class ActiveRecordExtension extends ActiveRecordModel
{
    /**
     * Join + find and return all.
     *
     * @param string $table what table to join.
     * @param string $condition what to join on.
     * 
     * @return array of object of this class
     */
    public function findAllJoin($table, $condition, $order = null)
    {
        $order = ($order) ? $order : $this->tableIdColumn;
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from($this->tableName)
                        ->join($table, $condition)
                        ->orderBy($order)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }

    public function findAllWhereJoin($where, $value, $table, $condition)
    {
        $this->checkDb();
        $params = is_array($value) ? $value : [$value];
        return $this->db->connect()
                        ->select()
                        ->from($this->tableName)
                        ->where($where)
                        ->join($table, $condition)
                        ->execute($params)
                        ->fetchAllClass(get_class($this));
    }
}