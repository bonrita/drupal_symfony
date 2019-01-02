<?php

namespace App\Infrastructure\Form\Helper;


class Permission
{

    public $values = [];

    private $id;
    private $name;
    private $description;
    private $domain;
    private $domainDescription;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Permission
     */
    public function setName($name): Permission
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Permission
     */
    public function setDescription($description): Permission
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     * @return Permission
     */
    public function setDomain($domain): Permission
    {
        $this->domain = $domain;

        return $this;
    }


    public function __get($name)
    {
        if (!array_key_exists($name, $this->values)) {
            $this->values[$name] = null;
        }

        return $this->values[$name];
    }

    public function __set($name, $value)
    {
        return $this->values[$name] = $value;
    }

    public function __isset($name)
    {
        if (!isset($this->values[$name])) {
            throw new \InvalidArgumentException(sprintf('The name: %s does not exist in the values list', $name));
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $domainDescription
     * @return Permission
     */
    public function setDomainDescription($domainDescription): Permission
    {
        $this->domainDescription = $domainDescription;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDomainDescription()
    {
        return $this->domainDescription;
    }

}