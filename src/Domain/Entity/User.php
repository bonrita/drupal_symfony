<?php

namespace App\Domain\Entity;


use App\Infrastructure\Session\AccountInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Infrastructure\Security\User\UserInterface as AppUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Dtc\GridBundle\Annotation as Grid;

/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="validators.user.username.unique")
 * @UniqueEntity(fields={"email"}, message="validators.user.email.unique")
 */
class User implements UserInterface, \Serializable, AccountInterface, AppUserInterface {

  /**
   * The link to reset a password is only valid for 24 hours.
   */
  public const PASSWORD_RESET_TIMEOUT = 86400;

  public const USERNAME_MAX_LENGTH = 60;

  public const EMAIL_MAX_LENGTH = 254;

  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   * @Grid\Column
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=User::USERNAME_MAX_LENGTH, unique=true)
   * @Grid\Column
   */
  private $username;

  /**
   * @ORM\Column(type="string", length=64)
   */
  private $password;

  /**
   * @ORM\Column(type="string", length=User::EMAIL_MAX_LENGTH, unique=true)
   */
  private $email;

  /**
   * @Assert\NotBlank()
   * @Assert\Length(max=4096)
   */
  private $plainPassword;

  /**
   * @ORM\Column(name="is_active", type="boolean")
   * @Grid\Column(label="Status")
   */
  private $isActive;

  /**
   * @ORM\Column(name="langcode", type="string", length=5)
   */
  private $langcode = 'en';

  /**
   * @ORM\Column(name="login", type="integer", options={"default":0})
   */
  private $login = 0;

  /**
   * @ORM\ManyToMany(targetEntity="App\Domain\Entity\Role", mappedBy="users")
   */
  private $roles;

  /**
   * @ORM\OneToMany(targetEntity="App\Domain\Entity\Task", mappedBy="user")
   */
  private $tasks;

  public function __construct() {
    $this->isActive = TRUE;
    //        $this->roles = new ArrayCollection();
    // may not be needed, see section on salt below
    // $this->salt = md5(uniqid('', true));
    $this->tasks = new ArrayCollection();
  }

  /**
   * @inheritDoc
   */
  public function getRoles() {
    return $this->roles->toArray();
  }

  /**
   * Add role.
   *
   * @param Role $role
   *
   * @return void
   */
  public function addRole(Role $role): void {
    $role->addUser($this);
    $this->roles[] = $role;
  }


  /**
   * @inheritDoc
   */
  public function getPassword() {
    return $this->password;
  }

  public function setPassword($password) {
    $this->password = $password;
  }

  public function getPlainPassword() {
    return $this->plainPassword;
  }

  public function setPlainPassword($password) {
    $this->plainPassword = $password;
  }

  /**
   * @inheritDoc
   */
  public function getSalt() {
    // you *may* need a real salt depending on your encoder
    // see section on salt below
    return NULL;
  }


  public function getEmail() {
    return $this->email;
  }

  public function setEmail($email) {
    $this->email = $email;
  }


  /**
   * @inheritDoc
   */
  public function getUsername() {
    return $this->username;
  }

  public function setUsername($username) {
    $this->username = $username;
  }

  /**
   * @inheritDoc
   */
  public function eraseCredentials() {
    // TODO: Implement eraseCredentials() method.
  }

  /**
   * @return mixed
   */
  public function isActive(): bool {
    return $this->isActive;
  }

  /**
   * @param mixed $login
   */
  public function setLogin($login): void {
    $this->login = $login;
  }

  /**
   * {@inheritdoc}
   */
  public function getLastLoginTime(): int {
    return $this->login;
  }

  /**
   * @return mixed
   */
  public function getLangcode() {
    return $this->langcode;
  }

  /**
   * @param mixed $langcode
   *
   * @return User
   */
  public function setLangcode($langcode) {
    $this->langcode = $langcode;

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function serialize() {
    return serialize(
      array(
        $this->id,
        $this->username,
        $this->password,
        // see section on salt below
        // $this->salt,
      )
    );
  }

  /**
   * @inheritDoc
   */
  public function unserialize($serialized) {
    list (
      $this->id,
      $this->username,
      $this->password,
      // see section on salt below
      // $this->salt
      ) = unserialize($serialized, ['allowed_classes' => FALSE]);
  }

  /**
   * @return mixed
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @param mixed $id
   *
   * @return UserInterface
   */
  public function setId($id): UserInterface {
    $this->id = $id;

    return $this;
  }

  /**
   * @param bool $isActive
   *
   * @return User
   */
  public function setIsActive(bool $isActive) {
    $this->isActive = $isActive;

    return $this;
  }

}