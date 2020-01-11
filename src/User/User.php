<?php

namespace linder\User;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model.
 */
class User extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "User";
    protected $tableIdColumn = "userId";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $userId;
    public $username;
    public $password;
    public $email;
    public $bio;

    /**
     * Set the password.
     *
     * @param string $password the password to use.
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify the username and the password, if successful the object contains
     * all details from the database row.
     *
     * @param string $username  username to check.
     * @param string $password the password to use.
     *
     * @return boolean true if username and password matches, else false.
     */
    public function verifyPassword($username, $password)
    {
        $this->find("username", $username);
        return password_verify($password, $this->password);
    }

    public function findMost($table)
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select("*, count({$table}.{$table}Id) as amount")
                        ->from($this->tableName)
                        ->join("{$table}", "{$table}.userId = user.userId")
                        ->groupBy("user.userId")
                        ->orderBy("amount DESC")
                        ->limit("10")
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }
}