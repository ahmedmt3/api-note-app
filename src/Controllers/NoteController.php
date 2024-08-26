<?php

namespace App\Controllers;

use App\Services\NoteServices;
use App\Services\UserServices;
use App\Utils\Helpers;
use App\Utils\ResponseCodes;

class NoteController
{
    public function __construct(private NoteServices $noteServices, private UserServices $userServices) {}

    public function processRequest(string $method, ?string $id): void
    {
        if ($id) {
            $this->processResourceRequest($method, $id);
        } else {
            $this->processCollectionRequest($method);
        }
    }
    // Singel Resource
    private function processResourceRequest(string $method, string $id)
    {
        $note = $this->noteServices->get($id);
        //If Note doesn't exist
        if (!$note) {
            http_response_code(ResponseCodes::NOT_FOUND);
            echo json_encode(["message" => "Note not found"]);
            return;
        }
        switch ($method) {
            case 'GET':
                echo json_encode($note);
                break;

            case 'PATCH':
                $json = file_get_contents("php://input");
                $data = (array) json_decode($json, true);
                //Validate data
                $errors = Helpers::NoteValidationErrors($data, false);
                if (!empty($errors)) {
                    http_response_code(ResponseCodes::UNPROCESSABLE_ENTITY);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                $rows = $this->noteServices->update($note, $data);

                echo json_encode([
                    "rows" => $rows,
                    "message" => "Note $id Updated"
                ]);
                break;

            case 'DELETE':
                $rows = $this->noteServices->delete($id);
                echo json_encode([
                    "rows" => $rows,
                    "message" => "Note $id Deleted"
                ]);
                break;

            default:
                http_response_code(ResponseCodes::METHOD_NOT_ALLOWED);
                header("Allow: GET, PATCH, DELETE");
                break;
        }
    }

    // Collection data
    private function processCollectionRequest(string $method)
    {
        switch ($method) {
            case 'GET':
                // Validating user
                $id = $_GET['user'] ?? null;
                if (empty($id) || !is_numeric($id)) {
                    http_response_code(ResponseCodes::UNAUTHORIZED);
                    echo json_encode(["message" => "Unauthorized"]);
                    break;
                }
                $user = $this->userServices->get($id);
                if (!$user) {
                    http_response_code(ResponseCodes::NOT_FOUND);
                    echo json_encode(["message" => "User not found"]);
                    break;
                }
                echo json_encode($this->noteServices->getAll($id));
                break;

            case 'POST':
                $json = file_get_contents("php://input");
                $data = (array) json_decode($json, true);
                //Validate data
                $errors = Helpers::noteValidationErrors($data);
                if (!empty($errors)) {
                    http_response_code(ResponseCodes::UNPROCESSABLE_ENTITY);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                $id = $this->noteServices->create($data);
                http_response_code(ResponseCodes::CREATED);
                echo json_encode([
                    "id" => (int) $id,
                    "message" => "Note Created"
                ]);
                break;

            default:
                http_response_code(ResponseCodes::METHOD_NOT_ALLOWED);
                header("Allow: GET, POST");
                break;
        }
    }
}
