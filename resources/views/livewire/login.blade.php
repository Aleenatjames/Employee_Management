<div>
<div class="w-full mt-4">
    <form class="form-horizontal w-3/4 mx-auto" wire:submit.prevent="login">
        <div class="flex flex-col mt-4">
            <input
                id="email"
                type="text"
                class="flex-grow h-8 px-2 border rounded border-grey-400"
                name="email"
                wire:model="email"
                placeholder="Email"
            >
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex flex-col mt-4">
            <input
                id="password"
                type="password"
                class="flex-grow h-8 px-2 rounded border border-grey-400"
                name="password"
                wire:model="password"
                placeholder="Password"
            >
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex items-center mt-4">
            <input
                type="checkbox"
                name="remember"
                id="remember"
                wire:model="remember"
                class="mr-2"
            >
            <label for="remember" class="text-sm text-grey-dark">Remember Me</label>
        </div>
        <div class="flex flex-col mt-8">
            <button
                type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded"
            >
                Login
            </button>
        </div>
    </form>
    <div class="text-center mt-4">
        <a class="no-underline hover:underline text-blue-dark text-xs" href="#">
            Forgot Your Password?
        </a>
    </div>
</div>
</div>
