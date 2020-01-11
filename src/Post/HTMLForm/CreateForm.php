<?php

namespace linder\Post\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use linder\Post\Post;
use linder\User\User;
use linder\Tag\Tag;
use linder\Tag\Tag2Post;

/**
 * Form to create an item.
 */
class CreateForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
                "escape-values" => false
            ],
            [
                "title" => [
                    "label" => "Rubrik:",
                    "type" => "text",
                    "validation" => ["not_empty"],
                ],
                        
                "text" => [
                    "label" => "Inlägg:",
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                ],

                "tags" => [
                    "label" => "Taggar:",
                    "type" => "text",
                    "description" => "Skriv in dina taggar med mellanslag mellan varje tag"
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Posta",
                    "class" => "green",
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
        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $post->title  = $this->form->value("title");
        $post->text = $this->form->value("text");
        $post->userId = $this->di->get("session")->get("userId");
        $post->save();

        // handle tags
        $tags = explode(' ', $this->form->value("tags"));
        foreach ($tags as $tag)
        {
            $tagTable = new Tag();
            $tag2post = new Tag2Post();
            $tagTable->setDb($this->di->get("dbqb"));
            $tagTable->find("tag", $tag);
            if (!$tagTable->tagId && $tag != "") {
                $tagTable->tag = $tag;
                $tagTable->save();
                $tagTable->find("tag", $tag);
            }
            $tag2post->setDb($this->di->get("dbqb"));
            $tag2post->tagId = $tagTable->tagId;
            $tag2post->postId = $post->postId;
            $tag2post->save();
        }
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

}
