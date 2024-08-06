<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Department') }}
            </h2>

            <a href="{{ route('department.create') }}" class="bg-slate-700 rounded py-2 my-2 px-3 text-white">Create</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>

            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="border-b">
                        <th class="px-6 py-3 text-left">ID</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Head</th>
                        <th class="px-6 py-3 text-left">Parent Department</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Created</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @if($departments->isNotEmpty())
                        @foreach($departments as $department)
                            <tr class="border-b">
                                <td class="px-6 py-3 text-left">
                                    {{ $department->id }}
                                </td>
                                <td class="px-6 py-3 text-left">
                                    {{ $department->name }}
                                </td>
                                <td class="px-6 py-3 text-left">
                                    {{ $department->departmentHead ? $department->departmentHead->name : 'N/A' }}
                                </td>
                                <td class="px-6 py-3 text-left">
                                    {{ $department->parentDepartment ? $department->parentDepartment->name : 'N/A' }}
                                </td>
                                <td class="px-6 py-3 text-left">
                                <button 
                                    onclick="toggleStatus('{{ $department->id }}');" 
                                    class="toggle-status-btn rounded-full py-2 px-4 text-white {{ $department->status === 'active' ? 'bg-green-500 hover:bg-green-400' : 'bg-red-500 hover:bg-red-400' }}"
                                    data-status="{{ $department->status }}">
                                    {{ ucfirst($department->status) }}
                                </button>
                                <form id="toggle-status-form-{{ $department->id }}" action="{{ route('department.toggleStatus', $department->id) }}" method="post" class="hidden">
                                    @csrf
                                    @method('put')
                                </form>
                            </td>
                                <td class="px-6 py-3 text-left">
                                    {{ \Carbon\Carbon::parse($department->created_at)->format('d, M, Y') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('department.edit', $department->id) }}" class="bg-slate-700 rounded py-2 my-2 px-3 text-white hover:bg-slate-600">Edit</a>
                                    <a href="#" onclick="deleteProduct('{{ $department->id }}');" class="bg-red-700 rounded py-2 my-2 px-3 text-white hover:bg-red-600">Delete</a>
                                    <form id="delete-product-form-{{ $department->id }}" action="{{ route('department.destroy', $department->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <div class="my-3">
                {{ $departments->links() }}
            </div>
        </div>
    </div>

    <script>
         function toggleStatus(id) {
            if (confirm("Are you sure you want to change the status of this department?")) {
                document.getElementById("toggle-status-form-" + id).submit();
            }
        }
        function deleteProduct(id) {
            if (confirm("Are you sure you want to delete this department?")) {
                document.getElementById("delete-product-form-" + id).submit();
            }
        }
    </script>
</x-app-layout>
