<?php

namespace App\Http\Controllers\API\V1\Admin;
use App\User;
use App\Http\Controllers\API\V1\BaseController;
use Illuminate\Http\Request;

class ExampleController extends BaseController
{
    public function index() {
		//retrieve code here
        return $this->createResponse($data);
	}



	public function create() {
		//create code here
        
		return $this->createResponse($data, 201);
	}



	public function testError() {
		try {
			$i = 2/0;
		}
		catch (\Exception $e) {
			return $this->createErrorResponse($e->getMessage(), 500);
		}
	}



	public function find($id) {
		$user = User::find($id);
		if (!$user) {
            return $this->createErrorResponse("User not found", 404);
        }
			
		return $this->createResponse($user);
	}



	public function delete($id) {
		// --delete code here
		// 
		// 

		return $this->createResponse(null, 204);
	}
}
