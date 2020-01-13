<?php

namespace linder\User;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use linder\User\HTMLForm\UserLogin;
use linder\User\HTMLForm\CreateUser;
use linder\User\HTMLForm\EditUser;
use linder\Comment\Comment;
use linder\Post\Post;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class UserController implements ContainerInjectableInterface
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
        // Use to set flash picture on all user subpages
        $this->flash = "image/theme/sunset.jpg?width=1100&height=200&cf&area=65,0,0,0";
    }

    /**
     * Description.
     *
     * @return object as a response object
     */
    public function loginAction() : object
    {
        $page = $this->di->get("page");
        $form = new UserLogin($this->di);
        $form->check();

        $page->add("anax/v2/image/default", [
            "src" => $this->flash,
        ], "flash");

        $page->add("anax/v2/article/default", [
            "content" => "<h1>Logga in</h1>" . $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Logga in",
        ]);
    }

    /**
     * Description.
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        $page = $this->di->get("page");
        $form = new CreateUser($this->di);
        $form->check();

        $page->add("anax/v2/image/default", [
            "src" => $this->flash,
        ], "flash");

        $page->add("anax/v2/article/default", [
            "content" => "<h1>Registrera nytt konto</h1>" . $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Registrera användare",
        ]);
    }

    /**
     * edit route.
     *
     * @param int $id for user you want to change
     *
     * @return object as a response object
     */
    public function editAction(int $id) : object
    {
        $page = $this->di->get("page");
        $userId = $this->di->get("session")->get("userId");
        if ($userId == $id) {
            $form = new EditUser($this->di, $id);
        } else {
            $form = new UserLogin($this->di);
        }
        $form->check();

        $page->add("anax/v2/image/default", [
            "src" => $this->flash,
        ], "flash");

        $page->add("anax/v2/article/default", [
            "content" => "<h1>Redigera konto</h1>" . $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Redigera användare",
        ]);
    }

    /**
     * @param int $userId
     * 
     * @return object as a response object
     */
    public function profileAction(int $id) : object
    {
        $page = $this->di->get("page");
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comments = $comment->findAllWhereJoin("comment.userId = ?", $id, "post", "post.postId = comment.postId");
        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $posts = $post->findAllWhere("post.userId", $id);
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find("userId", $id);
        if ($user->userId == null) {
            $this->di->get("response")->redirect("post");
        }
        $active = ($this->di->get("session")->get("userId") == $user->userId);

        $data = [
            "posts" => $posts,
            "comments" => $comments,
            "user" => $user,
            "active" => $active
        ];

        $page->add("anax/v2/image/default", [
            "src" => $this->flash,
        ], "flash");

        $page->add("user/profile", $data);
        return $page->render([
            "title" => "Användarprofil"
        ]);
    }

    /**
     * Logout route
     */
    public function logoutAction()
    {
        $this->di->get("session")->delete("userId");
        $this->di->get("response")->redirect("user/login");
    }
}
