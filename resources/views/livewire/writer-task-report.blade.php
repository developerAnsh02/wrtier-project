<div class="container-fluid">
    <!-- Page Header -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Task Report</h4>
        </div>
        <div class="col-md-7 align-self-center text-end">
            <div class="align-items-center" style="display: flex !important; justify-content: flex-end !important;">
                <input type="date" wire:model.live="task_date" class="form-control w-auto me-3">
                <ol class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Task Report</li>
                </ol>
            </div>
        </div>
    </div>
   <!-- Error Display Section (place this near the top of your form) -->
    @if($errors->any())        
        @foreach($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center justify-content-between p-2" role="alert">
                <div>
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ $error }}
                </div>
                <button type="button" class="btn-close h-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endforeach        
    @endif

    <!-- Search Section -->
    @if($reports['is_draft'] ?? false)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-search me-2"></i> Search Order Code
                    </h5>
                </div>
                <div class="card-body">                    
                    <div class="row">
                        <div class="col-md-8 position-relative">
                            <div class="input-group">
                                <input wire:model.live.debounce.300ms="searchOrderCode"
                                    wire:keydown.escape="resetSearch"  
                                    type="text"
                                    class="form-control"
                                    placeholder="Enter order code..."
                                    autocomplete="off">
                                <button class="btn btn-primary" type="button"
                                        wire:click="searchOrder">
                                    <i class="fas fa-search me-1"></i> Search
                                </button>
                            </div>
                            
                            @if($showOrderCodeDropdown)
                                <div class="mt-2 border rounded shadow-sm position-absolute w-100 bg-white z-index-1" style="max-height: 200px; overflow-y: auto;">
                                    <!-- Close Button -->
                                    <div class="position-absolute end-0 top-0 p-1">
                                        <button wire:click="resetSearch" 
                                                class="btn btn-sm btn-link text-muted"
                                                title="Close">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Results List -->
                                    <div class="pt-3">  <!-- Added padding to accommodate close button -->
                                        @forelse($filteredOrderCodes as $code)
                                            <div class="p-2 border-bottom hover-bg-gray" 
                                                wire:click="selectOrderCode('{{ $code }}')"
                                                style="cursor: pointer;">
                                                <i class="fas fa-file-alt me-2 text-primary"></i>
                                                {{ $code }}
                                            </div>
                                        @empty
                                            <div class="p-2 text-center text-muted">
                                                <i class="fas fa-exclamation-circle me-2"></i>
                                                No orders found for "{{ $searchOrderCode }}"
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Task Form -->
    @if(!empty($newReport['order_code']))    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas {{ $editingIndex !== null ? 'fa-edit' : 'fa-plus-circle' }} me-2"></i>
                        {{ $editingIndex !== null ? 'Edit' : 'Add' }} Task Details
                    </h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="addReport">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Order Code</label>
                                <input type="text" class="form-control" 
                                       value="{{ $newReport['order_code'] }}" readonly>
                                <small class="text-muted">
                                    <a href="#" wire:click.prevent="resetForm">Change order</a>
                                </small>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Nature of Task</label>
                                <select wire:model="newReport.nature" class="form-select">
                                    <option value="New">New</option>
                                    <option value="Feedback">Feedback</option>
                                    <option value="Additional Words">Additional Words</option>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label">Word Count</label>
                                <input wire:model="newReport.word_count" 
                                       type="number" 
                                       class="form-control" 
                                       min="0"
                                       required>
                                @error('newReport.word_count')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Task Date</label>
                                <input type="text" class="form-control" 
                                       value="{{ \Carbon\Carbon::parse($task_date)->format('d M Y') }}" readonly>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Comments</label>
                            <textarea wire:model="newReport.comments" 
                                      class="form-control" 
                                      rows="2"
                                      placeholder="Enter task comments"
                                      required></textarea>
                            @error('newReport.comments')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            @if($editingIndex !== null)
                                <button type="button" class="btn btn-warning me-2" 
                                        wire:click="resetForm">
                                    Cancel Edit
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary me-2" 
                                        wire:click="resetForm">
                                    Cancel
                                </button>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                {{ $editingIndex !== null ? 'Update' : 'Add' }} Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Task Report Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order Code</th>
                                    <th>Nature</th>
                                    <th>Word Count</th>
                                    <th>Comments</th>
                                    <th>Time</th>
                                    @if($reports['is_draft'] ?? true)
                                        <th>Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports['tasks'] ?? [] as $index => $task)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $task['order_code'] }}</td>
                                        <td>{{ $task['nature'] }}</td>
                                        <td>{{ number_format($task['word_count']) }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($task['comments'], 50) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($task['timestamp'])->format('h:i A') }}</td>
                                        @if($reports['is_draft'] ?? false)
                                            <td>
                                                <button wire:click="editTask({{ $index }})" 
                                                        class="btn btn-sm btn-warning me-1"
                                                        title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button wire:click="deleteTask({{ $index }})" 
                                                        class="btn btn-sm btn-danger"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>                                            
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            No tasks found for {{ \Carbon\Carbon::parse($task_date)->format('d M Y') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($reports['tasks'] ?? []) > 0)
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="3" class="text-end">Total:</td>
                                    <td>{{ number_format($reports['total_words'] ?? 0) }}</td>
                                    <td colspan="6" class="text-end">                                        
                                        @if(!($reports['is_draft'] ?? true))
                                            @if(($reports['edit_request_status'] ?? null) === 'pending')
                                                <span class="text-info me-2">
                                                    <i class="fas fa-clock"></i> Edit Request Pending
                                                    <small class="d-block text-muted">
                                                        Reason: {{ $reports['edit_reason'] }}
                                                    </small>
                                                </span>
                                            @elseif(($reports['edit_request_status'] ?? null) === 'approved')
                                                <span class="text-success me-2">
                                                    <i class="fas fa-check-circle"></i> Edit Approved
                                                </span>
                                            @else
                                                <button wire:click="requestEdit" 
                                                        class="btn btn-sm btn-info me-2"
                                                        title="Request Edit from TL">
                                                    <i class="fas fa-user-edit"></i> Request Edit
                                                </button>
                                            @endif
                                            <span class="text-success">
                                                <i class="fas fa-check-circle"></i> 
                                                Submitted: {{ $reports['submitted_at'] ? \Carbon\Carbon::parse($reports['submitted_at'])->format('M d, h:i A') : 'Just now' }}
                                            </span>
                                        @else
                                            <button wire:click="finalSubmission" 
                                                class="btn btn-primary"
                                                @if(!($reports['is_draft'] ?? true)) disabled @endif>
                                                Submit
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($confirmingDelete !== null)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" wire:click="$set('confirmingDelete', null)"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this task?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('confirmingDelete', null)">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="confirmDelete">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

     <!-- Edit Request Modal -->
     @if($showRequestModal)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Request Edit Permission</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="cancelRequest"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        You're requesting to edit a submitted report for {{ \Carbon\Carbon::parse($task_date)->format('M d, Y') }}
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Reason for Edit Request*</label>
                        <textarea wire:model="editRequestReason" 
                                class="form-control" 
                                rows="4"
                                placeholder="Please explain why you need to edit this report (10-55 characters)"
                                required></textarea>
                        @error('editRequestReason')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="text-end small mt-1">
                            {{ strlen($editRequestReason) }}/55 characters
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cancelRequest">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="submitEditRequest">
                        <i class="fas fa-paper-plane me-1"></i> Submit Request
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        .hover-bg-gray:hover {
            background-color: #f8f9fa;
        }
        .z-index-1 {
            z-index: 1;
        }
        .modal {
            backdrop-filter: blur(2px);
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</div>