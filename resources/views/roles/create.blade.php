<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between" >
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Role / Create') }}
        </h2>
        <a href="{{route('roles.index')}}"  class="bg-slate-700 rounded py-2 my-2 px-3 text-white">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{route('roles.store')}}" method="post">
                        @csrf
                        <div>
                            <div>
                                <label class="text-lg font-medium">Name</label>
                                <div class="mt-2">
                                    <input value="{{old('name')}}" type="text" class="w-1/2 border-gray-300 shadow-sm rounded-lg " name="name" placeholder="Enter Name">
                                    @error('name')
                                    <p class="invalid-feedback text-red-400">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="grid grid-cols-4">
                                    @if($permissions->isNotEmpty())
                                    @foreach($permissions as $permission)
                                    <div class="mt-3">
                                        <input type="checkbox" class="rounded" name="permission[]" id="permission-{{$permission->id}}" value="{{$permission->name}}">
                                        <label for="permission-{{$permission->id}}">{{$permission->name}}</label>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                                <button class="bg-slate-700 rounded py-3 my-2 px-5 text-white">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>