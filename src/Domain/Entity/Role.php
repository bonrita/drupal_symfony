<?php

namespace App\Domain\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role as CoreRole;
use Dtc\GridBundle\Annotation as Grid;
use App\Infrastructure\Annotation\GridBundle as CustomGrid;


/**
 * @ORM\Table(name="app_roles")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\RoleRepository")
 * @Grid\Grid(actions={@CustomGrid\EditAction(), @Grid\DeleteAction()})
 */
class Role extends CoreRole
{
    public const ROLE_AUTHENTICATED = 'ROLE_AUTHENTICATED';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Grid\Column
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Grid\Column(label="Name")
     */
    private $title;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Grid\Column(label="Machine name")
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="roles")
     */
    private $users;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $permissions;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    public function __construct(string $role)
    {
        parent::__construct($role);
        $this->users = new ArrayCollection();
        $this->name  = $role;
    }

    /**
     * @inheritDoc
     */
    public function getRole()
    {
        return $this->name;
    }



    /**
     * Add a User to the role
     *
     * @param User $user
     * @return void
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;
    }

    /**
     * @param mixed $name
     * @return Role
     */
    public function setName($name): Role
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param array $permissions
     * @return Role
     */
    public function setPermissions(array $permissions): Role
    {
        $this->permissions = $permissions;
        return $this;
    }

    public function addPermission(string $permission): void
    {
        if (!\in_array($permission, $this->permissions, true)) {
            $this->permissions[] = $permission;
        }
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Role
     */
    public function setTitle($title): Role
    {
        $this->title = $title;
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
     * @return Role
     */
    public function setDescription($description): Role
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

}
