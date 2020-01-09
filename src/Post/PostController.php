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
        $data = [
            "posts" => $post->findAllJoin("user", "post.userId = user.userId"),
            "userId" => $this->di->get("session")->get("userId")
        ];

        $page->add("post/crud/view-all", $data);

        return $page->render([
            "title" => "A collection of items",
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

        $page->add("post/crud/create", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Create a item",
        ]);
    }



    /**
     * Handler with form to delete an item.
     *
     * @return object as a response object
     */
    public function deleteAction() : object
    {
        $page = $this->di->get("page");
        $form = new DeleteForm($this->di);
        $form->check();

        $page->add("post/crud/delete", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Delete an item",
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
        if (!$userId || ($post->userId != $usierId)) {
            $this->di->get("response")->redirect("user/login");
        }

        $page = $this->di->get("page");
        $form = new UpdateForm($this->di, $id);
        $form->check();

        $page->add("post/crud/update", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Update an item",
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
        $post = new Post();
        $post->setDb($this->di->get("dbqb"));

        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comments = $comment->findAllWhere("comment.postId", $id);
        $res = $comment->sort($comments);

        $page = $this->di->get("page");

        $data = [
            "post" => $post->findAllWhere("post.postId", $id)[0],
            "comments" => $res
        ];

        $page->add("post/crud/view-post", $data);

        return $page->render([
            "title" => "View post",
        ]);
    }
}
