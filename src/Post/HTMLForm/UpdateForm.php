<?php

namespace linder\Post\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use linder\Post\Post;
use linder\Comment\Comment;

/**
 * Form to update an item.
 */
class UpdateForm extends FormModel
{
    /**
     * Constructor injects with DI container and the id to update.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     * @param integer             $id to update
     */
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);
        $this->id = $id;
        $post = $this->getItemDetails($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                "escape-values" => false
            ],
            [
                "title" => [
                    "label" => "Rubrik:",
                    "type" => "text",
                    "value" => $post->title,
                    "validation" => ["not_empty"],
                ],

                "text" => [
                    "label" => "Inlägg:",
                    "type" => "textarea",
                    "value" => $post->text,
                    "validation" => ["not_empty"],
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Spara ändring",
                    "class" => "green",
                    "callback" => [$this, "callbackSubmit"]
                ],

                "reset" => [
                    "type"      => "reset",
                    "value"     => "Ångra"
                ],

                "delete" => [
                    "type" => "submit",
                    "value" => "Radera inlägg",
                    "class" => "right red",
                    "callback" => [$this, "callbackDelete"]
                ],
            ]
        );
    }



    /**
     * Get details on item to load form with.
     *
     * @param integer $id get details on item with id.
     * 
     * @return Post
     */
    public function getItemDetails($id) : object
    {
        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $post->find("postId", $id);
        return $post;
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $post = $this->getItemDetails($this->id);
        $post->title = $this->form->value("title");
        $post->text = $this->form->value("text");
        $post->save();
        return true;
    }


    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("post")->send();
        //$this->di->get("response")->redirect("post/update/{$post->id}");
    }

    /**
     * Callback for delete-button 
     */
    public function callbackDelete()
    {
        $post = $this->getItemDetails($this->id);
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comments = $comment->findAllWhere("postId", $this->id);
        foreach ($comments as $comment) {
            $comment->setDb($this->di->get("dbqb"));
            $comment->delete();
        }
        $post->delete();
        $this->di->get("response")->redirect("post")->send();
    }
}
