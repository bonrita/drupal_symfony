<?php

namespace App\Infrastructure\Helper;


use App\Domain\Entity\User;

class EmailMessage implements EmailMessageInterface
{

    private $from;
    private $body;
    private $contentType = 'text/html';
    private $subject;

    /**
     * @return mixed
     */
    public function getContentType():string
    {
        return $this->contentType;
    }

    /**
     * @param mixed $contentType
     * @return EmailMessage
     */
    public function setContentType($contentType): EmailMessageInterface
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * @var User
     */
    private $user;

    /**
     * @return mixed
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return EmailMessage
     */
    public function setUser(User $user): EmailMessageInterface
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     * @return EmailMessage
     */
    public function setFrom($from): EmailMessageInterface
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     * @return EmailMessage
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     * @return EmailMessage
     */
    public function setSubject($subject): EmailMessageInterface
    {
        $this->subject = $subject;

        return $this;
    }

}