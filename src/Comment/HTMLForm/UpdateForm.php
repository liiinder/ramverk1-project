<?php

namespace linder\Comment\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use linder\Comment\Comment;

/**
 * Form to update an item.
 */
class UpdateForm extends FormModel
{

    public $id;
    public $comment;
    
    /**
     * Constructor injects with DI container and the id to update.
     *
     * @param \Psr\Container\ContainerInterface $di a service container
     * @param integer             $id to update
     */
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);
        $this->id = $id;
        $this->comment = $this->getItemDetails($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                "escape-values" => false
            ],
            [
                "text" => [
                    "type" => "textarea",
                    "label" => "Kommentar:",
                    "value" => $this->comment->text,
                    "validation" => ["not_empty"],
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Save",
                    "class" => "green",
                    "callback" => [$this, "callbackSubmit"]
                ],

                "reset" => [
                    "type"      => "reset",
                    "value"     => "Ångra"
                ],
            ]
        );
    }



    /**
     * Get details on item to load form with.
     *
     * @param integer $id get details on item with id.
     * 
     * @return Comment
     */
    public function getItemDetails($id) : object
    {
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comment->find("commentId", $id);
        return $comment;
    }


    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $this->comment->text = $this->form->value("text");
        $this->comment->save();
        return true;
    }

    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("post/view/" . $this->comment->postId)->send();
    }

}
