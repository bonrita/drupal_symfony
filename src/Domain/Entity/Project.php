<?php

namespace App\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table(name="project", indexes={@ORM\Index(name="fk_workspace_idx", columns={"workspace_id"})})
 * @ORM\Entity(repositoryClass="App\Domain\Repository\ProjectRepository")
 */
class Project {

  /**
   * @ORM\Column(name="id", type="integer", nullable=false)
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var string|null
   *
   * @ORM\Column(name="title", type="string", length=45, nullable=false)
   */
  private $title;

  /**
   * @var string|null
   *
   * @ORM\Column(name="description", type="text", length=255, nullable=true,
   *   options={"default"="NULL"})
   */
  private $description;

  /**
   * @var \DateTime|null
   *
   * @ORM\Column(name="due_date", type="datetime", nullable=true,
   *   options={"default"="CURRENT_TIMESTAMP"})
   */
  private $dueDate;

  /**
   * @ORM\ManyToOne(targetEntity="App\Domain\Entity\Workspace", inversedBy="projects")
   * @ORM\JoinColumn(name="workspace_id", referencedColumnName="id")
   */
  private $workspace;

  /**
   * @ORM\OneToMany(targetEntity="App\Domain\Entity\Task", mappedBy="project")
   */
  private $tasks;

  public function __construct() {
    $this->tasks = new ArrayCollection();
  }

  /**
   * @param string $title
   *
   * @return Project
   */
  public function setTitle(string $title): Project {
    $this->title = $title;
    return $this;
  }

  /**
   * @param null|string $description
   *
   * @return Project
   */
  public function setDescription(string $description): Project {
    $this->description = $description;
    return $this;
  }

  /**
   * @param \DateTime|null $dueDate
   *
   * @return Project
   */
  public function setDueDate(\DateTime $dueDate): Project {
    $this->dueDate = $dueDate;
    return $this;
  }

  /**
   * @param mixed $workspace
   *
   * @return Project
   */
  public function setWorkspace($workspace) {
    $this->workspace = $workspace;
    return $this;
  }


}
