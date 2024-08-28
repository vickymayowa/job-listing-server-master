<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class JobController extends Controller {
    /**
    * Display a listing of all jobs.
    */

    public function index() {
        // Retrieve all jobs
        $jobs = DB::select( 'SELECT * FROM jobs ORDER BY job_id DESC' );

        // Return jobs as a JSON response
        return Response::json( $jobs, 200 );
    }

    /**
    * Show the form for creating a new job.
    */

    public function create() {
        // Typically, this would return a view for creating a job
        // Since we're dealing with an API, you might not need this method
    }

    /**
     * Store a newly created job in storage.
     */
    public function store(Request $request)
    {
        // Insert data into the database
        $insert = DB::insert(
            'INSERT INTO jobs ( title, description, location, salary, requirements, company, email, jobType, user_id ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ? )', 
            [
                $request->title,
                $request->description,
                $request->location,
                $request->salary,
                $request->requirements,
                $request->company,
                $request->email,
                $request->jobType,
                $request->user_id
            ]
        );

        // Check if the insertion was successful
        if ($insert) {
            return Response::json(['status' => true, 'message' => 'Job created successfully'], 201);
        } else {
            return Response::json(['status' => false, 'message' => 'Failed to create job'], 500);
        }
    }

    /**
     * Display the specified job.
     */
    public function show(string $id)
    {
        // Retrieve the job by ID
        $job = DB::select('SELECT * FROM jobs WHERE user_id = ? ORDER BY job_id DESC', [$id]);

        // Check if job was found
        if ($job) {
            return Response::json($job, 200);
        } else {
            return Response::json(['status' => false, 'message' => 'Job not found'], 404);
        }
    }

    /**
     * Show the form for editing the specified job.
     */
    public function edit(string $id)
    {
        // Typically, this would return a view for editing a job
        // Since we're dealing with an API, you might not need this method
    }

    /**
    * Update the specified job in storage.
    */


    public function update(Request $request, string $id) {
        try {
            $update = DB::update(
                'UPDATE jobs SET title = ?, description = ?, location = ?, salary = ?, requirements = ?, company = ?, email = ?, jobType = ? WHERE job_id = ?',
                [
                    $request->title,
                    $request->description,
                    $request->location,
                    $request->salary,
                    $request->requirements,
                    $request->company,
                    $request->email,
                    $request->jobType,
                    $id
                ]
            );
    
            if ($update) {
                return Response::json(['status' => true, 'message' => 'Job updated successfully'], 200);
            } else {
                return Response::json(['status' => false, 'message' => 'Failed to update job']);
            }
        } catch (\Exception $e) {
            Log::error('Error updating job: ' . $e->getMessage());
            return Response::json(['status' => false, 'message' => 'Failed to update job']);
        }
    }
    
    /**
    * Remove the specified job from storage.
    */

    public function destroy( string $id ) {
        // Delete the job from the database
        $delete = DB::delete( 'DELETE FROM jobs WHERE job_id = ?', [ $id ] );

        // Check if the deletion was successful
        if ( $delete ) {
            return Response::json( [ 'status' => true, 'message' => 'Job deleted successfully' ], 200 );
        } else {
            return Response::json( [ 'status' => false, 'message' => 'Failed to delete job' ], 500 );
        }
    }
}
