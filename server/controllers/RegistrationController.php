<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../services/RegistrationService.php';

class RegistrationController extends Controller {
    private $registrationService;

    public function __construct() {
        $this->registrationService = new RegistrationService();
    }

    public function registerUserToEvent($eventId) {
        try {
            $data = $this->getRequestData();
            if (!isset($data['userId'])) {
                return $this->errorResponse('User ID is required', 400);
            }
            $userId = $data['userId'];

            $registrationId = $this->registrationService->registerUserToEvent($userId, $eventId);
            return $this->successResponse(
                ['registrationId' => $registrationId],
                'User registered to event successfully'
            );
        } catch (InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to register user to event', 500);
        }
    }

    public function unregisterUserFromEvent($eventId) {
        try {
            $data = $this->getRequestData();
            if (!isset($data['userId'])) {
                return $this->errorResponse('User ID is required', 400);
            }
            $userId = $data['userId'];

            $this->registrationService->unregisterUserFromEvent($userId, $eventId);
            return $this->successResponse(
                null,
                'User unregistered from event successfully'
            );
        } catch (InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to unregister user from event', 500);
        }
    }
}