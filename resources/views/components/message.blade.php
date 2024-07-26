@if(Session::has('success'))
            <div class="bg-green-200 rounded-sm border-green-600 p-4 mt-3 mb-3 shadow-sm">
                {{(Session::get('success'))}}
            </div>
            @endif
            @if(Session::has('error'))
            <div class="bg-red-200 rounded-sm border-red-600 p-4 mt-3 shadow-sm">
                {{(Session::get('error'))}}
            </div>
            @endif