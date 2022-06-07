<?php
/*
* Designed and programmed by
* @Author: Francis A. Anlimah
*/

class usersHandler
{
	private $gh;

	public function __construct()
	{
		require_once('general_handler.php');
		$this->gh = new GeneralHandler();
	}

	public function getUserDetails($user)
	{
		$sql = "SELECT `fname`, `lname`, `email`, `photo` FROM `tbl_users` WHERE id = :u";
		$params = array(':u' => htmlentities(htmlspecialchars($user)));
		return $this->gh->getData($sql, $params);
	}

	public function checkUserPass($user, $password)
	{
		$sql = "SELECT `id` FROM `tbl_users` WHERE `id`=:i AND `password`=:p";
		$params = array(':i' => $user, ':p' => sha1($password));
		return $this->gh->getID($sql, $params);
	}

	public function getHazobs($status = "all")
	{
		$sql = "";
		if ($status == "open") {
			$sql = "SELECT h.*, u.`fullname` 
				FROM tbl_hazobs AS h, `tbl_users` AS u 
				WHERE h.`status` = 'open' AND h.`deleted` = 0 AND u.`id` = h.`uid` 
				ORDER BY h.`updated_date` AND h.`updated_time` ASC LIMIT 5";
		} elseif ($status == "all") {
			$sql = "SELECT h.*, u.`fullname` 
				FROM tbl_hazobs AS h, `tbl_users` AS u 
				WHERE h.`deleted` = 0 AND u.`id` = h.`uid` 
				ORDER BY h.`updated_date` AND h.`updated_time` ASC";
		}

		$rslt = $this->gh->getData($sql);

		foreach ($rslt as $hazob) {
			$type = $hazob['type'] == 1 ? "Observation" : "Incident";
			$color = $hazob['status'] == 'open' ? "red" : "green";
			$reportedBy = $hazob['uid'] == $_SESSION["user"] ? "<b>You</b>" : $hazob["fullname"];

			$priority = '';
			if ($hazob["priority"] == "Low") {
				$priority = '<i class="bi-chevron-down text-primary" style="font-size: 14px; margin-left: 10px"></i><span> Low</span>';
			} elseif ($hazob["priority"] == "Medium") {
				$priority = '<i class="bi-chevron-up text-warning" style="font-size: 14px; margin-left: 10px"></i><span> Medium</span>';
			} elseif ($hazob["priority"] == "High") {
				$priority = '<i class="bi-chevron-double-up text-danger" style="font-size: 14px; margin-left: 10px"></i><span> High</span>';
			}

			$due_date = '';
			if ($hazob["due_date"]) {
				$due_date = '<i class="bi-clock text-primary" style="font-size: 12px; margin-left: 10px"></i><span> Due on ' . $hazob["due_date"] . '</span>';
			}

			$date = $hazob['updated_date'] == "" ? 'Created on ' . $hazob['created_date'] . ', ' . $hazob['created_time'] : 'Updated on ' . $hazob['updated_date'] . ', ' . $hazob['updated_time'];

			$assignees = $this->getHazobAssignees($hazob["hid"], $hazob["type"]);

			$assignTo = "";
			if ($assignees) {
				if (count($assignees) > 1) {
					foreach ($assignees as $key => $assignee) {
						if ($assignee["id"] == $_SESSION["user"]) {
							$assignTo = '<td colspan="2" style="font-size: 11px;padding: 3px 10px;">
										<i class="bi-people text-dark" style="font-size: 12px;"></i> 
										&nbsp; Assigned to <span id=""><b>You</b> and others</span>
									</td>';
						} else {
							$assignTo = '<td colspan="2" style="font-size: 11px;padding: 3px 10px;">
										<i class="bi-people text-dark" style="font-size: 12px;"></i> 
										&nbsp; Assigned to <span id="">' . $assignee["fullname"] . ' and others</span>
									</td>';
						}
						break;
					}
				} else {
					if ($assignees[0]["id"] == $_SESSION["user"]) {
						$assignTo = '<td colspan="2" style="font-size: 11px;padding: 3px 10px;">
									<i class="bi-people text-dark" style="font-size: 12px;"></i> 
									&nbsp; Assigned to <span id=""><b>You</b></span>
								</td>';
					} else {
						$assignTo = '<td colspan="2" style="font-size: 11px;padding: 3px 10px;">
									<i class="bi-people text-dark" style="font-size: 12px;"></i> 
									&nbsp; Assigned to <span id="">' . $assignees[0]["fullname"] . '</span>
								</td>';
					}
				}
			} else {
				$assignTo = "";
			}

			echo '<a href="report-comments.php?h=' . $hazob['hid'] . '&t=' . $hazob['type'] . '" style="text-decoration: none; color: #606060;">
					<table class="report" id="report-id" style="width: 100%;margin: 10px 0px; border: 1px solid #ccc; border-radius: 5px;">
						<tr id="title-area">
							<td colspan="2" style="padding: 2px 10px; color: #000;font-size: 14px;">' . $hazob['title'] . '</td>
						</tr>
						<tr id="type-area">
							<td colspan="2" style="padding: 5px 10px; font-size: 11px;">
								<span style="padding: 3px 5px; background-color: #f1f1f1; border-radius: 3px; border: 1px solid #909090">' . $type . '</span>
								' . $priority . '' . $due_date . '
							</td>
						</tr>
						<tr id="assignees-area">' . $assignTo . '</tr>
						<tr id="person-area">
							<td colspan="2" style="font-size: 11px;padding: 3px 10px;"><span id="">' . $reportedBy . ' reported this issue</span></td>
						</tr>
						<tr id="date-time-area" style="font-size: 11px;">
							<td style="padding: 5px 10px;">' . $date . '</td>
							<td style="text-align: right;padding: 2px 10px;;color: ' . $color . '; font-weight: 700;">' . ucfirst($hazob['status']) . '</td>
						</tr>
					</table>
				</a>';
		}
	}

	public function getReportInfo($t, $h)
	{
		$sql = "SELECT  h.*, u.`fullname` 
				FROM tbl_hazobs AS h, `tbl_users` AS u 
				WHERE h.`hid` = :h AND h.`type` = :t AND h.`uid` = u.`id`";
		return $this->gh->getData($sql, array(":h" => $h, ":t" => $t));
	}

	public function getReportDetails($t, $h)
	{
		$sql = "SELECT * FROM tbl_hazobs AS h, `tbl_users` AS u WHERE h.`hid` = :h AND h.`type` = :t";
		$rslt = $this->gh->getData($sql);
	}

	public function getTotalHazobs()
	{
		$sql = "SELECT * FROM `tbl_hazobs` WHERE `status` = 'open'";
		return $this->gh->getTotalData($sql);
	}

	public function getTotalUsers()
	{
		$sql = "SELECT * FROM `tbl_users` WHERE `deleted` = 0";
		return $this->gh->getTotalData($sql);
	}

	public function getHazobComments($user, $hazobID, $hazobType)
	{
		$sql = "SELECT a.`hid`, a.`uid`, a.`type`, a.`question`, a.`comment`, 
				a.`has_media`, a.`created_date`, a.`created_time`, u.`fullname` 
				FROM tbl_activities AS a, `tbl_users` AS u 
				WHERE u.`id` = a.`uid` AND a.`hid` = :h AND a.`type` = :t 
				ORDER BY a.created_at ASC";
		$params = array(":h" => $hazobID, ":t" => $hazobType);

		$rslt = $this->gh->getData($sql, $params);

		if ($rslt) {

			foreach ($rslt as $key => $hazob) {

				$media = "";
				if ($hazob["has_media"])
					$media = '<img class="media" src="images/comments/' . $hazob["has_media"] . '" alt="' . $hazob["has_media"] . '">';

				if ($hazob["uid"] == $user) {

					echo '<div class="message sent">
							' . $media . '
							' . $hazob["comment"] . '
							<span class="metadata">
								<span class="time">' . $hazob["created_date"] . ', ' . $hazob["created_time"] . '</span>
							</span>
						</div>';
				} else {

					echo '<div class="message received">
							<span class="senderdata">
								<span class="time">' . $hazob["fullname"] . '</span>
							</span>
							' . $media . '
							' . $hazob["comment"] . '
							<span class="metadata">
								<span class="time">' . $hazob["created_date"] . ', ' . $hazob["created_time"] . '</span>
							</span>
						</div>';
				}
			}
		}
	}

	public function getHazobUsers()
	{
		return $this->gh->getData("SELECT `id`, `fullname` FROM `tbl_users`");
	}

	public function getHazobAssignees($hazobID, $hazobType)
	{
		$sql = "SELECT u.`id`, u.`fullname` FROM `tbl_hazobs` AS h, `tbl_assignees` AS a, `tbl_users` AS u 
				WHERE h.`hid` = :h AND h.`type` = :t AND h.`hid` = a.`hid` 
				AND h.`type` = a.`type` AND a.`uid` = u.`id`";

		return $this->gh->getData($sql, array(":h" => $hazobID, ":t" => $hazobType));
	}

	public function getOBS($id)
	{
		$sql = "SELECT o.*, h.`description` 
				FROM `tbl_observations` AS o, `tbl_hazobs` AS h 
				WHERE o.`id` = :i AND h.`hid` = o.`id` AND h.`type` = 1";
		$params = array(":i" => htmlentities(htmlspecialchars($id)));
		return $this->gh->getData($sql, $params);
	}

	public function getINC($id)
	{
		$sql = "SELECT i.*, h.`description` FROM `tbl_incident` AS i, `tbl_hazobs` AS h 
				WHERE i.`id` = :i AND h.`hid` = i.`id` AND h.`type` = 2";
		$params = array(":i" => htmlentities(htmlspecialchars($id)));
		return $this->gh->getData($sql, $params);
	}

	public function getHazobImgs($hazobID, $hazobType)
	{
		$sql = "SELECT `id`, `title` FROM `tbl_media` WHERE `arid` = :h AND `type` = :t";
		$params = array(
			":h" => $this->gh->validateIDInput($hazobID),
			":t" => $this->gh->validateIDInput($hazobType)
		);

		return $this->gh->getData($sql, $params);
	}

	//FOR REPORT DETAILS PAGE

	public function updateTitle($hazobID, $hazobType, $title)
	{
		$sql = "UPDATE `tbl_hazobs` SET `title` = :d WHERE `hid` = :h AND `type` = :t";
		$params = array(
			":h" => $this->gh->validateIDInput($hazobID),
			":t" => $this->gh->validateIDInput($hazobType),
			":d" => htmlentities(htmlspecialchars($title))
		);
		return $this->gh->inputData($sql, $params);
	}

	public function updateDescription($hazobID, $hazobType, $descript)
	{
		$sql = "UPDATE `tbl_hazobs` SET `description` = :d WHERE `hid` = :h AND `type` = :t";
		$params = array(
			":h" => $this->gh->validateIDInput($hazobID),
			":t" => $this->gh->validateIDInput($hazobType),
			":d" => htmlentities(htmlspecialchars($descript))
		);
		return $this->gh->inputData($sql, $params);
	}

	public function updateStatus($hazobID, $hazobType, $data)
	{
		$sql = "UPDATE `tbl_hazobs` SET `status` = :d WHERE `hid` = :h AND `type` = :t";
		$params = array(
			":h" => $this->gh->validateIDInput($hazobID),
			":t" => $this->gh->validateIDInput($hazobType),
			":d" => htmlentities(htmlspecialchars($data))
		);
		return $this->gh->inputData($sql, $params);
	}

	public function updatePriority($hazobID, $hazobType, $data)
	{
		$sql = "UPDATE `tbl_hazobs` SET `priority` = :d WHERE `hid` = :h AND `type` = :t";
		$params = array(
			":h" => $this->gh->validateIDInput($hazobID),
			":t" => $this->gh->validateIDInput($hazobType),
			":d" => htmlentities(htmlspecialchars($data))
		);
		return $this->gh->inputData($sql, $params);
	}

	public function updateDueDate($hazobID, $hazobType, $ddate, $dtime)
	{
		$sql = "UPDATE `tbl_hazobs` SET `due_date` = :dd,  `due_time` = :dt 
				WHERE `hid` = :h AND `type` = :t";
		$params = array(
			":h" => $this->gh->validateIDInput($hazobID),
			":t" => $this->gh->validateIDInput($hazobType),
			":dd" => htmlentities(htmlspecialchars($ddate)),
			":dt" => htmlentities(htmlspecialchars($dtime))
		);
		return $this->gh->inputData($sql, $params);
	}

	public function updateAssignees($hazobID, $hazobType, $user, $action)
	{
		$h = $this->gh->validateIDInput($hazobID);
		$t = $this->gh->validateIDInput($hazobType);
		$u = $this->gh->validateIDInput($user);

		if ($action == 1) {
			$sql = "INSERT INTO `tbl_assignees` (`hid`, `type`, `uid`) VALUES(:h, :t, :u)";
			$params = array(":h" => $h, ":t" => $t, ":u" => $user);
			return $this->gh->inputData($sql, $params);
		} elseif ($action == 2) {
			$sql = "DELETE FROM `tbl_assignees` WHERE `hid` = :h AND `type` = :t AND `uid` = :u";
			$params = array(":h" => $h, ":t" => $t, ":u" => $user);
			return $this->gh->inputData($sql, $params);
		}
	}

	/*
	*	Deletes a report from the database
	*/

	private function deleteTBLHazobsRef($h, $t)
	{
		$sql = "DELETE FROM `tbl_hazobs` WHERE `hid` = :h AND `type` = :t";
		$params = array(":h" => $h, ":t" => $t);
		return $this->gh->inputData($sql, $params);
	}

	private function deleteTBLCommentsRef($h, $t)
	{
		$sql = "DELETE FROM `tbl_activities` WHERE `hid` = :h AND `type` = :t";
		$params = array(":h" => $h, ":t" => $t);
		return $this->gh->inputData($sql, $params);
	}

	private function deleteTBLMediaRef($h, $t)
	{
		$sql = "DELETE FROM `tbl_media` WHERE `arid` = :h AND `type` = :t";
		$params = array(":h" => $h, ":t" => $t);
		return $this->gh->inputData($sql, $params);
	}

	private function deleteTBLAssigneesRef($h, $t)
	{
		$sql = "DELETE FROM `tbl_assignees` WHERE `hid` = :h AND `type` = :t";
		$params = array(":h" => $h, ":t" => $t);
		return $this->gh->inputData($sql, $params);
	}

	private function deleteReport($h, $t)
	{
		$sql = "";
		if ($t == 1) {
			$sql = "DELETE FROM `tbl_observations` WHERE `id` = :h";
		} elseif ($t == 2) {
			$sql = "DELETE FROM `tbl_incidents` WHERE `id` = :h";
		}

		$params = array(":h" => $h);
		return $this->gh->inputData($sql, $params);
	}

	// Deletes an issues from the database
	public function deleteReportedIssue($hazobID, $hazobType)
	{
		$h = $this->gh->validateIDInput($hazobID);
		$t = $this->gh->validateIDInput($hazobType);

		if ($this->deleteTBLHazobsRef($h, $t)) {
			if ($this->deleteTBLCommentsRef($h, $t)) {
				if ($this->deleteTBLMediaRef($h, $t)) {
					if ($this->deleteTBLAssigneesRef($h, $t)) {
						if ($t == 1) {
							$this->deleteReport($h, $t);
							return '[{"success":"Issue deleted!"}]';
						}
					}
				}
			}
		}
	}
}
