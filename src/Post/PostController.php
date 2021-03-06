<?php

namespace linder\Post;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use linder\Post\HTMLForm\CreateForm;
use linder\Post\HTMLForm\UpdateForm;
use linder\User\User;
use linder\Comment\Comment;
use linder\Tag\Tag2Post;
use \Michelf\MarkdownExtra;


// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class PostController implements ContainerInjectableInterface
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
     * Show all items.
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        $page = $this->di->get("page");
        $post = new Post();
        $post->setDb($this->di->get("dbqb"));        
        $tag = new Tag2Post();
        $tag->setDb($this->di->get("dbqb"));
        $data = [
            "posts" => $post->findAllJoin("user", "post.userId = user.userId", "post.postId DESC"),
            "userId" => $this->di->get("session")->get("userId"),
            "tags" => $tag->findAllJoin("tag", "tag.tagId = tag2post.tagId"),
            "filter" => new MarkdownExtra()
        ];

        $page->add("anax/v2/image/default", [
            "src" => $this->flash,
        ], "flash");

        $page->add("post/crud/view-all", $data);

        return $page->render([
            "title" => "Forum",
        ]);
    }



    /**
     * Handler with form to create a new item.
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        if (!$this->di->get("session")->has("userId")) {
            $this->di->get("response")->redirect("user/login");
        }
        $page = $this->di->get("page");
        $form = new CreateForm($this->di);
        $form->check();

        $page->add("anax/v2/image/default", [
            "src" => $this->flash,
        ], "flash");

        $page->add("post/crud/create", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Skapa inlägg",
        ]);
    }

    /**
     * Handler with form to update an item.
     *
     * @param int $id the id to update.
     *
     * @return object as a response object
     */
    public function updateAction(int $id) : object
    {
        $userId = $this->di->get("session")->get("userId");
        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $post->find("postId", $id);
        if (!$userId || ($post->userId != $userId)) {
            $this->di->get("response")->redirect("user/login");
        }

        $page = $this->di->get("page");
        $form = new UpdateForm($this->di, $id);
        $form->check();

        $page->add("anax/v2/image/default", [
            "src" => $this->flash,
        ], "flash");

        $page->add("post/crud/update", [
            "form" => $form->getHTML(),
            "type" => "inlägg"
        ]);

        return $page->render([
            "title" => "Redigera inlägg",
        ]);
    }

    /**
     * Handler to show an post.
     *
     * @param int $id the id to view.
     *
     * @return object as a response object
     */
    public function viewAction(int $id) : object
    {
        // posts
        $post = new Post();
        $post->setDb($this->di->get("dbqb"));

        // comments
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comments = $comment->findAllWhere("comment.postId", $id);
        $res = $comment->sort($comments);

        // tags
        $tags = new Tag2Post();
        $tags->setDb($this->di->get("dbqb"));
        
        $page = $this->di->get("page");
        
        $data = [
            "post" => $post->findAllWhere("post.postId", $id)[0],
            "comments" => $res,
            "userId" => $this->di->get("session")->get("userId"),
            "tags" => $tags->findTagsWhere("post.postId", $id),
            "filter" => new MarkdownExtra()
        ];

        $page->add("anax/v2/image/default", [
            "src" => $this->flash,
        ], "flash");

        $page->add("post/crud/view-post", $data);

        return $page->render([
            "title" => "Visar inlägg",
        ]);
    }
}
