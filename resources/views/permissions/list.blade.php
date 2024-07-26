<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between" >
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Permission') }}
        </h2>
        @can('create permissions')
        <a href="{{route('permissions.create')}}" class="bg-slate-700 rounded py-2 my-2 px-3 text-white">Create</a>
        @endcan
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
                        <th class="px-6 py-3 text-left" >Created</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @if($permissions->isNotEmpty())
                    @foreach($permissions as $permission)
                    <tr class="border-b">
                        <td class="px-6 py-3 text-left">
                            {{$permission->id}}
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{$permission->name}}
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{\Carbon\Carbon::parse($permission->created_at)->format('d,M,Y')}}
                        </td>
                        <td class="px-6 py-3 text-center">
                            @can('edit permissions')
                        <a href="{{route('permissions.edit',$permission->id)}}" class="bg-slate-700 rounded py-2 my-2 px-3 text-white hover:bg-slate-600">Edit</a>
                        @endcan
                        @can('delete permissions')
                        <a href="#"  onclick="deleteProduct('{{ $permission->id }}');" class="bg-red-700 rounded py-2 my-2 px-3 text-white hover:bg-red-600">Delete</a>
                        @endcan
                        <form id="delete-product-form-{{ $permission->id }}" action="{{ route('permissions.destroy', $permission->id) }}" method="post">
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
          {{$permissions->links()}}
          </div>
            
        </div>
    </div>
    <script>
    function deleteProduct(id){
        
        if(confirm("Are you sure you want to delete the product?")){
            document.getElementById("delete-product-form-"+id).submit();
        }
    }
</script>
</x-app-layout>