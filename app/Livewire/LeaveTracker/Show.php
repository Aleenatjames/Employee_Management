<?php

namespace App\Livewire\LeaveTracker;

use App\Models\LeaveApplication;
use App\Models\LeaveApproval;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Show extends Component
{
    public $applicationId;
    public $leaveApplication;
    public $managerComment = '';
    public $managerManagerComment = '';
    public $isApproved = false;


    public function mount($applicationId)
    {
        // Fetch the leave application by the application ID
        $this->applicationId = $applicationId;
        $this->leaveApplication = LeaveApplication::with(['employee.manager.manager'])->findOrFail($applicationId);
    }
    public function approveLeave($approverLevel)
    {

        $this->saveLeaveApproval($approverLevel, 'approved');
    }

    public function rejectLeave($approverLevel)
    {
        Log::info("Reject Leave called for level: $approverLevel"); // Add logging
        $this->saveLeaveApproval($approverLevel, 'rejected');
    }

    private function saveLeaveApproval($approverLevel, $status)
    {
        $approver = null;
        $comment = null;
        $level = 0;

        // Determine the approver and level based on the approverLevel
        if ($approverLevel == 'manager') {
            $approver = $this->leaveApplication->employee->manager;
            $comment = $this->managerComment;
            $level = 1;
        } elseif ($approverLevel == 'manager_manager') {
            $approver = $this->leaveApplication->employee->manager->manager;
            $comment = $this->managerManagerComment;
            $level = 2;
        }

        // Ensure approver exists
        if ($approver) {
            // Check if approval/rejection record already exists for this approver and application
            $existingApproval = LeaveApproval::where('application_id', $this->leaveApplication->id)
                ->where('approver_id', $approver->id)
                ->first();

            if ($existingApproval) {
                session()->flash('message', 'This leave application has already been ' . $existingApproval->status . ' by this approver.');
                return; // Exit the method to avoid duplicate approvals
            }

            // Save the leave approval record
            LeaveApproval::create([
                'application_id' => $this->leaveApplication->id,
                'date' => Carbon::now(),
                'approver_id' => $approver->id,
                'status' => $status,
                'comment' => $comment,
                'level' => $level,
            ]);

            // Update leave application status based on approval level
            if ($status === 'approved') {
                if ($level == 1 && !$this->leaveApplication->employee->manager->manager) {
                    // Fully approved by manager
                    $this->leaveApplication->status = 'fully approved';
                } elseif ($level == 1 && $this->leaveApplication->employee->manager->manager) {
                    // Partially approved by manager
                    $this->leaveApplication->status = 'partial approved';
                } elseif ($level == 2) {
                    // Fully approved by manager's manager
                    $this->leaveApplication->status = 'fully approved';
                }
            } elseif ($status === 'rejected') {
                // If rejected by anyone, it's rejected
                $this->leaveApplication->status = 'rejected';
            }

            $this->leaveApplication->lastupdated_by = Auth::guard('employee')->user()->id;

            // Save the updated status to the leave application
            $this->leaveApplication->save();

            // Feedback message
            session()->flash('message', 'Leave application has been ' . $this->leaveApplication->status);
        }
    }

    public function render()
    {
        $leaveApprovals = LeaveApproval::where('application_id', $this->leaveApplication->id)->get();
        return view('livewire.leave-tracker.show', [
            'leaveApplication' => $this->leaveApplication,
            'leaveApprovals' => $leaveApprovals,
        ]);
    }
}
