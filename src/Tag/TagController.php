<?php

namespace linder\Tag;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use linder\tag\tag2post;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class TagController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * @var $data description
     */
    //private $data;


    /**
     * Show all tags
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        $page = $this->di->get("page");

        $tag2post = new Tag2Post();
        $tag2post->setDb($this->di->get("dbqb"));

        $page->add("tag/view-all", [
            "tags" => $tag2post->findTags(),
        ]);

        return $page->render([
            "title" => "A index page",
        ]);
    }

    public function viewAction(int $id) : object
    {
        $page = $this->di->get("page");

        $tag2post = new Tag2Post();
        $tag2post->setDb($this->di->get("dbqb"));

        $data = [
            "tags" => $tag2post->findTagsWhere("tag.tagId", $id)
        ];

        $page->add("tag/view-tag", $data);

        return $page->render([
            "title" => "Boring title"
        ]);
    }

}
