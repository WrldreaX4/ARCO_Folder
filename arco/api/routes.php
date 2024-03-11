<?php

// Set CORS headers to allow requests from any origin
header("Access-Control-Allow-Origin: *");

// Include necessary files
require_once("config/initialize.php");

// Initialize database connection
$con = new Connection();
$pdo = $con->connect();

// Initialize Get and Post objects
$get = new Get($pdo);
$post = new Post($pdo);
$put = new Put($pdo);
$delete = new Delete($pdo);

// Check if 'request' parameter is set in the request
if(isset($_REQUEST['request'])){
    // Split the request into an array based on '/'
    $request = explode('/', $_REQUEST['request']);
}
else{
    // If 'request' parameter is not set, return a 404 response
    http_response_code(404);
    echo json_encode(["error" => "Not Found"]);
    exit;
}

// Handle requests based on HTTP method
switch($_SERVER['REQUEST_METHOD']){
    // Handle GET requests
    case 'GET':
        switch($request[0]){
            case 'get_signup':
                if(count($request)>1){
                    echo json_encode($get->get_signup($request[1]));
                }
                else{
                    echo json_encode($get->get_signup());
                }
                break;

                
            case 'flipbook':
                if(count($request)>1){
                    echo json_encode($get->get_flipbook($request[1]));
                }
                else{
                    echo json_encode($get->get_flipbook($data));
                }
                break;
            
            case 'flipbook_all':
                 if(count($request)>1){
                echo json_encode($get->get_flipbookall($request[1]));
                    }
                 else{
                     echo json_encode($get->get_flipbookall());
                 }
                 break;
            
            case 'collage':
                if(count($request)>1){
                    echo json_encode($get->get_collage($request[1]));
                }
                else{
                    echo json_encode($get->get_collage($data));
                }
                break;
                
            case 'reports':
                if(count($request)>1){
                    echo json_encode($get->get_reports($request[1]));
                }else{
                    echo json_encode($get->get_reports($data));
                }
                break;

            case 'reports_all':
                 if(count($request)>1){
                    echo json_encode($get->get_reportsall($request[1]));
                 }else{
                     echo json_encode($get->get_reportsall());
                    }
                    break;
                    
            default:
                // Return a 403 response for unsupported requests
                http_response_code(403);
                echo json_encode(["error" => "Forbidden"]);
                break;
        }
        break;
    // Handle POST requests
    case 'POST':
        // Decode JSON data from request body
        $data = json_decode(file_get_contents("php://input"));
        switch($request[0]){

            case 'signup':
                echo json_encode($post->signup($data));
                break;

            case 'login':
                echo json_encode($post->login($data));
                break;

            case 'report':
                echo json_encode($post->insertReport($data, $request[1]));
                break;
            case 'flipbook':
                echo json_encode($post->flipbook($data, $request[1]));
                 break;

            default:
                http_response_code(403);
                echo json_encode(["error" => "Forbidden"]);
                break;
        }
        break;
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        switch($request[0]){
            case 'edit_report' :
                echo json_encode($put->edit_reports($data, $request[1]));
                break;

                default:
                http_response_code(403);
                echo json_encode(["error" => "Forbidden"]);
                break;
        } 
        break;
    case 'DELETE':
        switch($request[0]){
            case 'delete_report' :
                echo json_encode($delete->delete_reports($request[1]));
                break;

            default:
            http_response_code(403);
            echo json_encode(["error" => "Forbidden"]);

        } break;
            // Return a 405 response for unsupported HTTP methods
            http_response_code(405);
            echo json_encode(["error" => "Method Not Allowed"]);
            break;
}

?>
