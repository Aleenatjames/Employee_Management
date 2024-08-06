<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Employees') }}
            </h2>
            <a href="{{ route('employees.create') }}" class="bg-slate-700 rounded py-2 my-2 px-3 text-white">Create</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-">
            <x-message></x-message>

            <table class="w-full border-collapse">
                <thead class="bg-gray-50">
                    <tr class="border-b">
                        <th class="px-6 py-3 text-left">ID</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Designation</th>
                        <th class="px-6 py-3 text-left">Departments</th>
                        <th class="px-6 py-3 text-left">Role</th> <!-- Add Role column -->
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Created</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($employees as $employee)
                        <tr class="border-b">
                            <td class="px-6 py-3 text-left">{{ $employee->id }}</td>
                            <td class="px-6 py-3 text-left">{{ $employee->name }}</td>
                            <td class="px-6 py-3 text-left">{{ $employee->email }}</td>
                            <td class="px-6 py-3 text-left">{{ $employee->designation }}</td>
                            <td class="px-6 py-3 text-left">
                                @foreach($employee->departments as $department)
                                    {{ $department->name }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </td>
                            <td class="px-6 py-3 text-left"> <!-- Role column -->
                                @foreach($employee->roles as $role)
                                    {{ ucfirst($role->name) }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </td>
                            <td class="px-6 py-3 text-left">
                                <button 
                                    onclick="toggleStatus('{{ $employee->id }}');" 
                                    class="toggle-status-btn rounded-full py-2 px-4 text-white {{ $employee->status === 'active' ? 'bg-green-500 hover:bg-green-400' : 'bg-red-500 hover:bg-red-400' }}"
                                    data-status="{{ $employee->status }}">
                                    {{ ucfirst($employee->status) }}
                                </button>
                                <form id="toggle-status-form-{{ $employee->id }}" action="{{ route('employees.toggleStatus', $employee->id) }}" method="post" class="hidden">
                                    @csrf
                                    @method('put')
                                </form>
                            </td>
                            <td class="px-6 py-3 text-left">{{ \Carbon\Carbon::parse($employee->created_at)->format('d, M, Y') }}</td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('employees.edit', $employee->id) }}" class="bg-slate-700 rounded py-2 my-2 px-3 text-white hover:bg-slate-600">Edit</a>
                                <a href="#" onclick="deleteEmployee('{{ $employee->id }}');" class="bg-red-700 rounded py-2 my-2 px-3 text-white hover:bg-red-600">Delete</a>
                                <form id="delete-employee-form-{{ $employee->id }}" action="{{ route('employees.destroy', $employee->id) }}" method="post" class="hidden">
                                    @csrf
                                    @method('delete')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-3 text-center text-gray-500">No employees found.</td> <!-- Update colspan to 9 -->
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="my-3">
                {{ $employees->links() }}
            </div>
        </div>
    </div>

    <script>
        function toggleStatus(id) {
            if (confirm("Are you sure you want to change the status of this employee?")) {
                document.getElementById("toggle-status-form-" + id).submit();
            }
        }

        function deleteEmployee(id) {
            if (confirm("Are you sure you want to delete this employee?")) {
                document.getElementById("delete-employee-form-" + id).submit();
            }
        }
    </script>
</x-app-layout>
