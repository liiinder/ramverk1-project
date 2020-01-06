<?php

namespace linder\Comment\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use linder\Post\Post;
use linder\User\User;
use linder\Comment\Comment;

/**
 * Form to create an item.
 */
class CreateForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     * @param $postId int
     * @param $commentId int
     */
    public function __construct(ContainerInterface $di, $postId, $replyId = null)
    {
        parent::__construct($di);
        $this->postId = $postId;
        $this->replyId = $replyId;
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Details of the item",
                "escape-values" => false
            ],
            [

                "text" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Create item",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $active = $user->find("username", $this->di->get("session")->get("username"));
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comment->text = $this->form->value("text");
        $comment->userId = $active->userId;
        $comment->postId = $this->postId;
        $comment->replyId = $this->replyId;
        $comment->save();
        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("post/view/" . $this->postId )->send();
    }


    // /**
    //  * Callback what to do if the form was unsuccessfully submitted, this
    //  * happen when the submit callback method returns false or if validation
    //  * fails. This method can/should be implemented by the subclass for a
    //  * different behaviour.
    //  */
    // public function callbackFail()
    // {
    //     $this->di->get("response")->redirectSelf()->send();
    // }
}
