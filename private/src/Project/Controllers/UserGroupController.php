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
        ob_start();
        $groupId = $_REQUEST['groupId'] ?? null;
        
        if (is_null($groupId) || !is_numeric($groupId)) {
            throw new RequestException("Bad request: Group ID is missing or invalid.", 400);
        }
        
        $group = $this->userGroupService->getUserGroupById((int) $groupId);
        if (!$group) {
            throw new RequestException("Group not found.", 404);
        }
        
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($group);
        ob_end_flush();
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
        ob_start();
        
        // Meant to retrieve the data from the form
        $data = $_REQUEST;
        
        // Validate the input data
        if (!$this->validateUserGroupData($data)) {
            throw new RequestException("Bad request: Missing or invalid fields.", 400);
        }
        
        // Call UserGroupService to create the user group
        try {
            $created_group = $this->userGroupService->createUserGroup($data['groupName'], $data['description']);
        } catch (ValidationException $exception) {
            throw new RequestException("Validation error: " . $exception->getMessage(), 422);
        } catch (RuntimeException $exception) {
            throw new RequestException("Server error: " . $exception->getMessage(), 500);
        }
        
        // Respond with the user group
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode([
                             'message' => 'User group created successfully',
                             'groupId' => $created_group->getId()
                         ]);
        
        ob_end_flush();
    }
    
    /**
     * TODO: Function documentation put
     *
     * @return void
     *
     * @throws RequestException
     *
     * @author Natalia Herrera.
     * @since  2024-03-30
     */
    public function put() : void {
        ob_start();
        
        // Retrieve data from the form submission
        $data = $_REQUEST;
        
        if (empty($data['userGroupId'])) {
            throw new RequestException("User group ID is required for updating.", 400);
        }
        
        $userGroupId = (int) $data['userGroupId'];
        
        $validationResult = $this->validateUserGroupData($data);
        if (!$validationResult['success']) {
            throw new RequestException($validationResult['message'], 400);
        }
        
        // Call UserGroupService to update the user group
        try {
            $updatedGroup =
                $this->userGroupService->updateUserGroup($userGroupId, $data['groupName'], $data['description'] ?? '');
        } catch (ValidationException $exception) {
            throw new RequestException("Validation error: " . $exception->getMessage(), 422);
        } catch (RuntimeException $exception) {
            throw new RequestException("Server error: " . $exception->getMessage(), 500);
        }
        
        // Respond with the updated user group data
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode([
                             'message' => 'User group updated successfully',
                             'userGroupId' => $updatedGroup->getId()
                         ]);
        
        ob_end_flush();
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
        ob_start();
        
        // Retrieve the user group ID from the request
        $userGroupId = $_REQUEST['userGroupId'] ?? null;
        
        if (is_null($userGroupId) || !is_numeric($userGroupId)) {
            throw new RequestException("Bad request: User group ID is missing or invalid.", 400);
        }
        
        // Convert the user group ID to an integer
        $userGroupId = (int) $userGroupId;
        
        try {
            // Check if the user group exists
            $userGroup = $this->userGroupService->getUserGroupById($userGroupId);
            if (!$userGroup) {
                throw new RequestException("User group not found.", 404);
            }
            
            // Determine whether it's a hard or soft delete
            $hardDelete = false; //
            // Perform the deletion
            $this->userGroupService->deleteUserGroup($userGroupId, $hardDelete);
            
            // Respond with a success message
            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode(['message' => 'User group deleted successfully']);
        } catch (RequestException $exception) {
            http_response_code($exception->getHttpResponseCode());
            echo json_encode(['error' => $exception->getMessage()]);
        } catch (RuntimeException $exception) {
            throw new RequestException("Server error: " . $exception->getMessage(), 500);
        }
        
        ob_end_flush();
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
}