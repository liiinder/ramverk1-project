<?php

namespace linder\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use linder\User\User;
use Anax\Commons\ContainerInjectableInterface;

/**
 * Example of FormModel implementation.
 */
class CreateUser extends FormModel
{
    private $userId;
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\Commons\ContainerInjectableInterface $di a service container
     */
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
            ],
            [
                "username" => [
                    "label"       => "Användarnamn:",
                    "type"        => "text",
                ],
                        
                "password" => [
                    "label"       => "Lösenord:",
                    "type"        => "password",
                ],

                "password-again" => [
                    "label"       => "Repetera lösenord:",
                    "type" => "password",
                    "validation" => [
                        "match" => "password"
                    ],
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Registrera",
                    "callback" => [$this, "callbackSubmit"]
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
        $username       = $this->form->value("username");
        $password      = $this->form->value("password");
        $passwordAgain = $this->form->value("password-again");

        // Check password matches
        if ($password !== $passwordAgain ) {
            $this->form->rememberValues();
            $this->form->addOutput("Lösenorden matchade inte varandra.");
            return false;
        }

        // Save to database
        $user = New User();
        $user->setDb($this->di->get("dbqb"));
        $user->username = $username;
        $user->setPassword($password);
        $user->save();
        $this->userId = $user->userId;

        $this->form->addOutput("Användare skapad.");
        return true;
    }

    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("session")->set("userId", $this->userId);
        $this->di->get("response")->redirect("post")->send();
    }
}
