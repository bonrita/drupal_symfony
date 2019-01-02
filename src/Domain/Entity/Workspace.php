<?php

namespace App\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Workspace
 *
 * @ORM\Table(name="workspace")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\WorkspaceRepository")
 */
class Workspace {

  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var string|null
   *
   * @ORM\Column(name="name", type="string", length=45, nullable=false,
   *   options={"default"="NULL"})
   */
  private $name;

  /**
   * @var string|null
   *
   * @ORM\Column(name="description", type="text", length=255, nullable=true,
   *   options={"default"="NULL"})
   */
  private $description;

  /**
   * @ORM\OneToMany(targetEntity="App\Domain\Entity\Project", mappedBy="workspace")
   */
  private $projects;

  public function __construct() {
    $this->projects = new ArrayCollection();
  }

  /**
   * @param string $name
   *
   * @return Workspace
   */
  public function setName(string $name): Workspace {
    $this->name = $name;
    return $this;
  }

  /**
   * @param string $description
   *
   * @return Workspace
   */
  public function setDescription(string $description): Workspace {
    $this->description = $description;
    return $this;
  }

  /**
   * @param mixed $id
   *
   * @return Workspace
   */
  public function setId($id): Workspace {
    $this->id = $id;
    return $this;
  }

}
