<?php

namespace App\Http\Dto;

use App\Models\Task;

class TaskDto
{
  public int $id;
  public string $title;
  public string $required_skill;
  public string $urgency;
  public int $duration;
  public array $technicians;
  public array $assigned;
  public int $remain;

  public function __construct(Task $task)
  {
      $this->id = $task->id;
      $this->title = $task->title;
      $this->required_skill = $task->required_skill;
      $this->urgency = $task->urgency;
      $this->duration = $task->duration;
      $this->technicians = $task->technicians;
      $this->assigned = [];
      $this->remain = $task->duration;
  }
}
