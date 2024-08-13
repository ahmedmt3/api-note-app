<?php

namespace App\Controllers;

use App\Services\NoteServices;

class NoteController
{
    public function __construct(private NoteServices $gateway) {}

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
        $note = $this->gateway->get($id);
        //If Note doesn't exist
        if (!$note) {
            http_response_code(404);
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
                $errors = $this->getValidationErrors($data, false);
                if (!empty($errors)) {
                    http_response_code(422); //Unprocessable Entity
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                $rows = $this->gateway->update($note, $data);

                echo json_encode([
                    "rows" => $rows,
                    "message" => "Note $id Updated"
                ]);
                break;

            case 'DELETE':
                $rows = $this->gateway->delete($id);
                echo json_encode([
                    "rows" => $rows,
                    "message" => "Note $id Deleted"
                ]);
                break;

            default:
                http_response_code(405); //Method Not Allowed
                header("Allow: GET, PATCH, DELETE");
                break;
        }
    }

    // Collection data
    private function processCollectionRequest(string $method)
    {
        switch ($method) {
            case 'GET':
                echo json_encode($this->gateway->getAll());
                break;

            case 'POST':
                $json = file_get_contents("php://input");
                $data = (array) json_decode($json, true);
                //Validate data
                $errors = $this->getValidationErrors($data);
                if (!empty($errors)) {
                    http_response_code(422); //Unprocessable Entity
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                $id = $this->gateway->create($data);
                http_response_code(201);
                echo json_encode([
                    "id" => $id,
                    "message" => "Note Created"
                ]);
                break;

            default:
                http_response_code(405); //Method Not Allowed
                header("Allow: GET, POST");
                break;
        }
    }

    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];

        if ($is_new && empty($data['content'])) {
            $errors[] = "Content is required";
        }
        if (key_exists('color', $data)) {
            if (!preg_match('/^[a-fA-F0-9]{6}$/', $data['color'])) {
                $errors[] = "Invalid hex-color format";
            }
        }
        return $errors;
    }
}
