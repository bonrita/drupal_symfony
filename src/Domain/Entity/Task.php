<?php

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table(name="task", indexes={@ORM\Index(name="task_project_idx", columns={"project_id"}, columns={"project_id"}), @ORM\Index(name="task_app_users_idx", columns={"user_id"})})})
 * @ORM\Entity(repositoryClass="App\Domain\Repository\TaskRepository")
 */
class Task
{
    /**
     * @var int
     *
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
     * @ORM\Column(name="description", type="text", length=255, nullable=true, options={"default"="NULL"})
     */
    private $description;

    /**
    * @var \DateTime|null
    *
    * @ORM\Column(name="due_date", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
    */
    private $dueDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * Many tasks can be assigned to one user.
     * @ORM\ManyToOne(targetEntity="App\Domain\Entity\Project", inversedBy="tasks")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

  /**
   * @return int
   */
  public function getId(): int {
    return $this->id;
  }

  /**
   * @param int $id
   */
  public function setId(int $id): void {
    $this->id = $id;
  }

  /**
   * @return null|string
   */
  public function getTitle(): string {
    return $this->title;
  }

  /**
   * @param null|string $title
   */
  public function setTitle(string $title): void {
    $this->title = $title;
  }

  /**
   * @return null|string
   */
  public function getDescription(): ?string {
    return $this->description;
  }

  /**
   * @param null|string $description
   */
  public function setDescription(?string $description): void {
    $this->description = $description;
  }

  /**
   * @return \DateTime|null
   */
  public function getDueDate(): ?\DateTime {
    return $this->dueDate;
  }

  /**
   * @param \DateTime|null $dueDate
   */
  public function setDueDate(?\DateTime $dueDate): void {
    $this->dueDate = $dueDate;
  }

  /**
   * @return mixed
   */
  public function getUser() {
    return $this->user;
  }

  /**
   * @param mixed $user
   */
  public function setUser($user): void {
    $this->user = $user;
  }

  /**
   * @return mixed
   */
  public function getProject() {
    return $this->project;
  }

  /**
   * @param mixed $project
   */
  public function setProject($project): void {
    $this->project = $project;
  }

}
