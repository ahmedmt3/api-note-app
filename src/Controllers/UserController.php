<?php

namespace App\Controllers;

use App\Services\UserServices;
use App\Utils\Helpers;
use App\Utils\ResponseCodes;

class UserController
{
    public function __construct(private UserServices $userServices) {}

    public function processRequest(string $method, ?string $id): void
    {
        if ($id) {
            $this->processResourceRequest($method, $id);
        } else {
            $this->processCollectionRequest($method);
        }
    }

    // Resource Request
    private function processResourceRequest(string $method, string $id): void
    {
        $user = $this->userServices->get($id);
        //If User doesn't exist
        if (!$user) {
            http_response_code(ResponseCodes::NOT_FOUND);
            echo json_encode(["message" => "User not found"]);
            return;
        }
        switch ($method) {
            case 'GET':
                echo json_encode($user);
                break;

            case 'DELETE':
                $rows = $this->userServices->delete($id);
                echo json_encode([
                    "rows" => $rows,
                    "message" => "User $id Deleted"
                ]);
                break;

            default:
                http_response_code(ResponseCodes::METHOD_NOT_ALLOWED);
                header("Allow: GET, DELETE");
                break;
        }
    }

    // Collection Request
    private function processCollectionRequest(string $method): void
    {
        switch ($method) {

            case 'GET':
                $data = $this->userServices->getAll();
                echo json_encode($data);
                break;

            case 'POST':
                $json = file_get_contents("php://input");
                $data =  (array) json_decode($json, true);
                // Determine if Login or Signup request
                if (isset($data['action']) && $data['action'] === 'login') {
                    //========[ Login Case ]========
                    //Validating user data
                    $errors = Helpers::userValidationErrors($data);
                    if (!empty($errors)) {
                        http_response_code(ResponseCodes::UNPROCESSABLE_ENTITY);
                        echo json_encode(["errors" => $errors]);
                        break;
                    }
                    $user = $this->userServices->login($data);
                    if ($user) {
                        echo json_encode([
                            "message" => "Login successful",
                            "user" => $user
                        ]);
                    } else {
                        http_response_code(ResponseCodes::UNAUTHORIZED);
                        echo json_encode(["message" => "Invalid credentials"]);
                    }
                } else {
                    //=======[ Signup Case ]=========
                    //Validating user data
                    $errors = Helpers::userValidationErrors($data, true);
                    if (!empty($errors)) {
                        http_response_code(ResponseCodes::UNPROCESSABLE_ENTITY);
                        echo json_encode(["errors" => $errors]);
                        break;
                    }
                    //Check if user already exist
                    $userExist = $this->userServices->checkUser($data['username']);
                    if ($userExist) {
                        http_response_code(ResponseCodes::CONFLICT);
                        echo json_encode(["message" => "User already exist"]);
                        break;
                    }
                    $id = $this->userServices->create($data);
                    http_response_code(ResponseCodes::CREATED);
                    echo json_encode([
                        "id" => (int) $id,
                        "message" => "User Created"
                    ]);
                }
                break;


            default:
                http_response_code(ResponseCodes::METHOD_NOT_ALLOWED);
                header("Allow: GET, POST");
                break;
        }
    }
}
