<?php
// file: model/ProjectMapper.php
require_once(__DIR__."/../core/PDOConnection.php");

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/Project.php");
require_once(__DIR__."/../model/Task.php");

/**
 * Class ProjectMapper
 *
 * Database interface for Project entities
 *
 */
class ProjectMapper
{

	/**
	 * Reference to the PDO connection
	 * @var PDO
	 */
	private $db;

	public function __construct()
	{
		$this->db = PDOConnection::getInstance();
	}

	/**
	 * Retrieves all Projects from the user with the given user_mail
	 *
	 * Note: Tasks & Users are not added to the Project instances
	 *
	 * @throws PDOException if a database error occurs
	 * @return mixed Array of Project instances (without Tasks & Users)
	 */
	public function findAll($user_mail)
	{
		$stmt = $this->db->prepare("SELECT * FROM projects WHERE project_id IN (SELECT project_id FROM users_on_projects WHERE user_mail=?)");
		$stmt->execute(array($user_mail));
		$projects_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$projects = array();

		foreach ($projects as $project) {
			array_push($projects, new Project($project["project_id"], $project["project_name"]));
		}

		return $projects;
	}

	/**
	 * Loads a Project from the database given its id
	 *
	 * Note: Tasks & Users are not added to the Project
	 *
	 * @throws PDOException if a database error occurs
	 * @return Project The Project instances (without Tasks & Users). NULL
	 * if the Project is not found
	 */
	public function findById($projectid)
	{
		$stmt = $this->db->prepare("SELECT * FROM projects WHERE project_id=?");
		$stmt->execute(array($projectid));
		$project = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($project != null) {
			return new Project(
				$project["project_id"],
				$project["project_name"]
			);
		} else {
			return NULL;
		}
	}

	/**
	 * Loads a Project from the database given its id
	 *
	 * It includes all the Tasks & Users
	 *
	 * @throws PDOException if a database error occurs
	 * @return Project The Project instances (with Tasks & Users). NULL
	 * if the Project is not found
	 */
	public function findByIdWithAll($projectid)
	{
		$stmt = $this->db->prepare("SELECT
			P.project_id as 'project.id',
			P.project_name as 'project.name',
			UP.user_mail as 'user.mail',
			T.task_id as 'task.id',			
			T.task_name as 'task.name',			
			T.task_status  as 'task.status '			

			FROM projects P, LEFT OUTER JOIN tasks T
			ON P.project_id = T.project_id
			WHERE
			P.project_id=?");

		$stmt->execute(array($projectid));
		$project_with_all = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (sizeof($project_with_all) > 0) {

			$project = new Project(
				$project_with_all[0]["project.id"],
				$project_with_all[0]["project.name"]
			);

			$tasks_array = array();
			if ($project_with_all[0]["task.id"] != null) {
				foreach ($project_with_all as $task) {
					$task = new Task(
						$task["task.id"],
						$task["task.name"],
						$task["project.id"],
						$task["task.status"]
					);
					array_push($tasks_array, $task);
				}
			}

			$project->setTasks($tasks_array);


			//recuperar usuarios
			$stmt2 = $this->db->prepare("SELECT * FROM users WHERE user_mail IN (
				SELECT user_mail FROM users_on_projects WHERE project_id=?)
				");
			$stmt2->execute(array($projectid));
			$users = $stmt2->fetchAll(PDO::FETCH_ASSOC);


			$users_array = array();
			foreach ($users as $user) {
				array_push($users_array, new User($user["user_mail"], $user["username"], $user["passwd"]));
			}

			$project->setUsers($users_array);


			return $project;
		} else {
			return NULL;
		}
	}

	/**
	 * Saves a Project into the database
	 *
	 * @param Project $project The project to be saved
	 * @throws PDOException if a database error occurs
	 * @return int The new project id
	 */
	public function save(Project $project)
	{
		$stmt = $this->db->prepare("INSERT INTO projects(project_name) values (?)");
		$stmt->execute(array($project->getName()));
		$toRet = $this->db->lastInsertId();

		$stmt = $this->db->prepare("INSERT INTO users_on_projects(user_mail,project_id) values (?,?)");

		foreach ($project->getUsers() as $user) {
			$stmt->execute(array($user->getUserMail(), $project->getId()));
		}

		return $toRet;
	}

	/**
	 * Updates a Project in the database
	 *
	 * @param Project $project The Project to be updated
	 * @throws PDOException if a database error occurs
	 * @return void
	 */
	public function update(Project $project)
	{
		$stmt = $this->db->prepare("UPDATE projects set project_name=? where project_id=?");
		$stmt->execute(array($project->getName(), $project->getId()));



		$stmt = $this->db->prepare("SELECT user_mail FROM users_on_projects WHERE project_id=?");
		$stmt->execute(array($project->getId()));
		$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$stmt = $this->db->prepare("INSERT INTO users_on_projects(user_mail,project_id) values (?,?)");

		foreach ($project->getUsers() as $user) {
			if (!in_array($user->getUserMail(), $users)) {
				$stmt->execute(array($user->getUserMail(), $project->getId()));
			}
		}
	}

	/**
	 * Deletes a Project from the database
	 *
	 * @param Project $project The project to be deleted
	 * @throws PDOException if a database error occurs
	 * @return void
	 */
	public function delete(Project $project)
	{
		$stmt = $this->db->prepare("DELETE from projects WHERE project_id=?");
		$stmt->execute(array($project->getId()));
	}

}
?>