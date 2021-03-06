<?php

namespace linder\Tag;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use linder\Tag\Tag2Post;
use linder\User\User;
use linder\Post\Post;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class TagController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public $flash;
    /**
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @return void
     */
    public function initialize() : void
    {
        // Use to set the flash picture on all tag subpages
        $this->flash = "image/theme/sunset.jpg?width=1100&height=200&cf&area=65,0,0,0";
    }


    /**
     * Show all tags
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        $page = $this->di->get("page");

        $tag2post = new Tag2Post();
        $tag2post->setDb($this->di->get("dbqb"));

        $user = new User();
        $user->setDb($this->di->get("dbqb"));

        $post = new Post();
        $post->setDb($this->di->get("dbqb"));

        $page->add("anax/v2/image/default", [
            "src" => $this->flash,
        ], "flash");

        $page->add("tag/index", [
            "tags" => $tag2post->findAll("10"),
            "posts" => $user->findMost("post"),
            "comments" => $user->findMost("comment"),
            "latest" => $post->findLatest()
        ]);

        return $page->render([
            "title" => "Topplista",
        ]);
    }

    public function viewAction(int $id) : object
    {
        $page = $this->di->get("page");

        $tag2post = new Tag2Post();
        $tag2post->setDb($this->di->get("dbqb"));

        $data = [
            "tags" => $tag2post->findTagsWhere("tag.tagId", $id),
            "test" => $tag2post->getTagString("5"),
        ];

        $page->add("anax/v2/image/default", [
            "src" => $this->flash,
        ], "flash");

        $page->add("tag/view-tag", $data);

        return $page->render([
            "title" => "Visar tag"
        ]);
    }

    public function allAction() : object
    {
        $page = $this->di->get("page");
        $tag2post = new Tag2Post();
        $tag2post->setDb($this->di->get("dbqb"));

        $data = [
            "tags" => $tag2post->findAll("-1")
        ];

        $page->add("anax/v2/image/default", [
            "src" => $this->flash,
        ], "flash");

        $page->add("tag/view-all", $data);

        return $page->render([
            "title" => "Visar alla taggar"
        ]);
    }

}
