<?php

namespace linder\Post;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use linder\Post\HTMLForm\CreateForm;
use linder\Post\HTMLForm\EditForm;
use linder\Post\HTMLForm\DeleteForm;
use linder\Post\HTMLForm\UpdateForm;
use linder\User\User;
use linder\Comment\Comment;
use linder\Tag\Tag2Post;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class PostController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * @var $data description
     */

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
            "posts" => $post->findAllJoin("user", "post.userId = user.userId"),
            "userId" => $this->di->get("session")->get("userId"),
            "tags" => $tag->findAllJoin("tag", "tag.tagId = tag2post.tagId")
        ];

        $page->add("anax/v2/image/default", [
            "src" => "image/theme/tree.jpg?width=1100&height=150&crop-to-fit&area=0,0,30,0",
        ], "flash");

        $page->add("post/crud/view-all", $data);

        return $page->render([
            "title" => "Visa inlägg",
        ]);
    }



    /**
     * Handler with form to create a new item.
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        if ($this->di->get("session")->has("username") == false) {
            $this->di->get("response")->redirect("user/login");
        }
        $page = $this->di->get("page");
        $form = new CreateForm($this->di);
        $form->check();

        $page->add("anax/v2/image/default", [
            "src" => "image/theme/tree.jpg?width=1100&height=150&crop-to-fit&area=0,0,30,0",
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
            "src" => "image/theme/tree.jpg?width=1100&height=150&crop-to-fit&area=0,0,30,0",
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
            "tags" => $tags->findTagsWhere("post.postId", $id)
        ];

        $page->add("anax/v2/image/default", [
            "src" => "image/theme/tree.jpg?width=1100&height=150&crop-to-fit&area=0,0,30,0",
        ], "flash");

        $page->add("post/crud/view-post", $data);

        return $page->render([
            "title" => "Visa inlägg",
        ]);
    }
}
