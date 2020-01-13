<?php

namespace linder\Post\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use linder\Post\Post;
use linder\Comment\Comment;
use linder\Tag\Tag;
use linder\Tag\Tag2Post;

/**
 * Form to update an item.
 */
class UpdateForm extends FormModel
{
    public $id;

    /**
     * Constructor injects with DI container and the id to update.
     *
     * @param \Psr\Container\ContainerInterface $di a service container
     * @param integer             $id to update
     */
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);
        $tags = new Tag2Post();
        $tags->setDb($this->di->get("dbqb"));
        $res = $tags->getTagString($id);
        $oldTags = "";
        foreach ($res as $tag)
        {
            $oldTags .= $tag->tag . " ";
        }

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

                "tags" => [
                    "label" => "Taggar:",
                    "value" => $oldTags,
                    "type" => "text",
                    "description" => "Skriv in dina taggar med mellanslag mellan varje tag"
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

        // handle tags
        $tags = explode(' ', $this->form->value("tags"));
        // remove old tags
        $tag2post = new Tag2Post();
        $tag2post->setDb($this->di->get("dbqb"));
        $tag2post->deleteWhere("postId = ?", $this->id);
        // add new tags
        foreach ($tags as $tag)
        {
            // Setup and check if the tag exists
            $tagTable = new Tag();
            $tagTable->setDb($this->di->get("dbqb"));
            $tagTable->find("tag", $tag);
            if (!$tagTable->tagId && $tag != "") {
                $tagTable->tag = $tag;
                $tagTable->save();
                $tagTable->find("tag", $tag);
            }
            // Add into the coupling table
            $tag2post = new Tag2Post();
            $tag2post->setDb($this->di->get("dbqb"));
            $tag2post->tagId = $tagTable->tagId;
            $tag2post->postId = $post->postId;
            $tag2post->save();
        }
        // Clean unused tags
        $tag = new Tag();
        $tag->setDb($this->di->get("dbqb"));
        $tag->cleanTags();
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
