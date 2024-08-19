<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use App\Models\ProjectTimesheet;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    public function create(){
        return view('employee.project-timesheet.create');
    }
    public function list(){
        return view('employee.project-timesheet.index');
    }
    public function edit($timesheet){
        return view('employee.project-timesheet.edit',compact('timesheet'));
    }
    public function exportTimesheets(Request $request)
    {
        // Define the filename for the CSV
        $filename = "timesheets.csv";

        // Query the ProjectTimesheet model based on the request parameters
        $timesheets = ProjectTimesheet::query()
            ->where('employee_id', $request->selectedEmployee)
            ->when($request->startDate, function ($query) use ($request) {
                $query->where('date', '>=', $request->startDate);
            })
            ->when($request->endDate, function ($query) use ($request) {
                $query->where('date', '<=', $request->endDate);
            })
            ->when($request->selectedProject, function ($query) use ($request) {
                $query->where('project_id', $request->selectedProject);
            })
            ->with(['project'])
            ->get();

        // Open a file in write mode
        $handle = fopen($filename, 'w+');

        // Add the headers to the CSV file
        fputcsv($handle, [
            'ID',
            'Employee ID',
            'Project ID',
            'Date',
            'Task ID',
            'Time',
            'Comment',
            'Project Name'
        ]);

        // Add the data rows to the CSV file
        foreach ($timesheets as $timesheet) {
            fputcsv($handle, [
                $timesheet->id,
                $timesheet->employee_id,
                $timesheet->project_id,
                $timesheet->date,
                $timesheet->taskid,
                $timesheet->time,
                $timesheet->comment,
                $timesheet->project->name ?? 'N/A',
            ]);
        }

        // Close the file
        fclose($handle);

        // Return the CSV file as a download and delete it after sending
        return response()->download($filename)->deleteFileAfterSend(true);
    }
}

