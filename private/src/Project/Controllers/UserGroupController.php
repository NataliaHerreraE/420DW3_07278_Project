<?php
declare(strict_types=1);
/**
 * 420DW3_07278_Project ${FILE_NAME}
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-03-29
 * (c) Copyright 2024 Natalia Herrera.
 */

namespace Project\Controllers;

use Couchbase\Group;
use Project\DTOs\UserGroup;
use Project\Services\LoginService;
use Project\Services\UserGroupService;
use Teacher\GivenCode\Abstracts\AbstractController;
use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 *
 */
class UserGroupController extends AbstractController {
    private UserGroupService $userGroupService;
    
    public function __construct() {
        parent::__construct();
        $this->userGroupService = new UserGroupService();
    }
    
    /**
     * TODO: Function documentation get
     *
     * @return void
     *
     * @throws RequestException
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-03-30
     */
    public function get() : void {
        $this->requireLogin();
        if (isset($_REQUEST["group_id"]) && is_numeric($_REQUEST["group_id"])) {
            $group_id = (int) $_REQUEST["group_id"];
            try {
                $group = $this->userGroupService->getUserGroupById($group_id);
                echo json_encode(['success' => true, 'data' => $group->toArray()]);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Group ID not provided']);
        }
    }
    
    /**
     * TODO: Function documentation post
     *
     * @return void
     *
     * @throws RequestException
     *
     * @author Natalia Herrera.
     * @since  2024-03-30
     */
    public function post() : void {
        $this->requireLogin();
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['groupname']) || !isset($data['description'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            return;
        }
        
        try {
            $newGroupId = $this->userGroupService->createUserGroup($data['groupname'], $data['description']);
            echo json_encode(['success' => true, 'message' => 'Group created successfully', 'group_id' => $newGroupId]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    
    /**
     * TODO: Function documentation put
     *
     * @return void
     *
     * @throws RequestException
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-03-30
     */
    public function put() : void {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['group_id']) || !is_numeric($data['group_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Group ID not provided']);
            return;
        }
        
        try {
            if (isset($data['is_deleted']) && $data['is_deleted'] == 1) {
                // Perform soft delete
                $this->userGroupService->deleteUserGroup((int) $data['group_id']);
                echo json_encode(['success' => true, 'message' => 'Group soft-deleted successfully']);
            } else {
                // Normal update logic here
                $this->userGroupService->updateUserGroup($data['group_id'], $data['groupname'], $data['description']);
                echo json_encode(['success' => true, 'message' => 'Group updated successfully']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    
    /**
     * TODO: Function documentation delete
     *
     * @return void
     *
     * @throws RequestException
     *
     * @author Natalia Herrera.
     * @since  2024-03-30
     */
    public function delete() : void {
        $this->requireLogin();
        $data = $this->getJsonData();
        
        if (!isset($data['group_id']) || !is_numeric($data['group_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Group ID not provided']);
            return;
        }
        
        try {
            $this->userGroupService->deleteUserGroup((int) $data['group_id']);
            echo json_encode(['success' => true, 'message' => 'Group soft deleted successfully']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    
    private function validateUserGroupData($data) : array {
        if (empty($data['groupName'])) {
            return ['success' => false, 'message' => 'Group name is required.'];
        }
        if ((strlen($data['groupName']) < 3) || (strlen($data['groupName']) > 20)) {
            return ['success' => false, 'message' => 'Group name must be between 3 and 20 characters.'];
        }
        if (isset($data['description']) && (strlen($data['description']) > 70)) {
            return ['success' => false, 'message' => 'Description must not exceed 100 characters.'];
        }
        // If all checks pass
        return ['success' => true, 'message' => 'Validation successful.'];
    }
    
    private function requireLogin() : void {
        if (!LoginService::isLoggedIn()) {
            throw new RequestException("Not authorized.", 401);
        }
    }
    
    private function getJsonData() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            throw new RequestException("Invalid JSON data received.", 400);
        }
        return $data;
    }
    
    private function jsonResponse($data, $statusCode = 200) : void {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
    }
    
    /**
     * TODO: Function documentation addUsersToGroup
     *
     * @return void
     *
     * @throws RequestException
     *
     * @author Natalia Herrera.
     * @since  2024-05-04
     */
    public function addUsersToGroup() : void {
        $this->requireLogin();
        $data = $this->getJsonData();
        
        if (!isset($data['group_id']) || !is_numeric($data['group_id'])) {
            throw new RequestException("Bad request: Usergroup ID is missing or invalid.", 400);
        }
        
        if (!isset($data['user_ids']) || !is_array($data['user_ids'])) {
            throw new RequestException("Bad request: User IDs are missing or invalid.", 400);
        }
        
        $this->userGroupService->addUsersToGroup((int) $data['group_id'], $data['user_ids']);
        echo $this->jsonResponse(['message' => 'Users added to group successfully'], 204);
    }
    
    
    /**
     * TODO: Function documentation removeUserFromGroup
     *
     * @return void
     *
     * @throws RequestException
     *
     * @author Natalia Herrera.
     * @since  2024-05-04
     */
    public function removeUserFromGroup() : void {
        $this->requireLogin();
        $data = $this->getJsonData();
        
        if (!isset($data['group_id']) || !is_numeric($data['group_id'])) {
            throw new RequestException("Bad request: Usergroup ID is missing or invalid.", 400);
        }
        
        if (!isset($data['user_id']) || !is_numeric($data['user_id'])) {
            throw new RequestException("Bad request: User ID is missing or invalid.", 400);
        }
        
        $this->userGroupService->removeUserFromGroup((int) $data['group_id'], (int) $data['user_id']);
        echo $this->jsonResponse(['message' => 'User removed from group successfully'], 204);
        
    }
    
    public static function getAllGroups() : void {
        header('Content-Type: application/json');
        try {
            $groupService = new UserGroupService();
            $groups = $groupService->getAllUserGroups();
            $groupArray = [];
            foreach ($groups as $group) {
                if ($group instanceof usergroup) {
                    $groupArray[] = $group->toArray();
                }
            }
            echo json_encode(['success' => true, 'data' => $groupArray]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * TODO: Function documentation getDeletedGroup
     *
     * @return void
     *
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-05-05
     */
    public static function getDeletedGroup() : void {
        header('Content-Type: application/json');
        try {
            $groupService = new UserGroupService();
            $groups = $groupService->getDeleteGroups();
            $groupArray = [];
            foreach ($groups as $group) {
                if ($group instanceof UserGroup){
                    $groupArray[] = $group->toArray();
                }
                
            }
            echo json_encode(['success' => true, 'data' => $groups]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}