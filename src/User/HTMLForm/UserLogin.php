<?php

namespace linder\USer\HTMLForm;

use Anax\HTMLForm\FormModel;
use linder\User\User;
use Psr\Container\ContainerInterface;

/**
 * Example of FormModel implementation.
 */
class UserLogin extends FormModel
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
            ],
            [
                "user" => [
                    "label"       => "Användarnamn:",
                    "type"        => "text"
                ],
                        
                "password" => [
                    "label"       => "Lösenord:",
                    "type"        => "password"
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Logga in",
                    "callback" => [$this, "callbackSubmit"]
                ],

                "create" => [
                    "type" => "submit",
                    "value" => "Registrera",
                    "callback" => [$this, "callbackRegister"]
                ],
            ]
        );
    }


    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        // Get values from the submitted form
        $username       = $this->form->value("user");
        $password       = $this->form->value("password");

        // Try to login
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $res = $user->verifyPassword($username, $password);

        if (!$res) {
            $this->form->rememberValues();
            $this->form->addOutput("User or password did not match.");
            $this->di->get("session")->delete("username");
            return false;
        }

        $this->form->addOutput("User " . $user->username . " logged in.");
        $this->di->get("session")->set("userId", $user->userId);
        return true;
    }

    /**
     * Callback for the register button
     */
    public function callbackRegister()
    {
        $this->di->get("response")->redirect("user/create");
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
