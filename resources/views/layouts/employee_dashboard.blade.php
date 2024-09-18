<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> @yield('title')</title>
  @vite('resources/css/app.css')

  @vite('resources/js/app.js')

</head>

<body>
  @livewireStyles
  @livewireScripts

  @section('sidebar')

  <!-- Sidebar -->
  <div class="fixed flex flex-col top-14 left-0 w-14 hover:w-64 md:w-64 h-full text-white transition-all duration-300 border-none z-10 sidebar bg-blue-900 dark:bg-gray-800">
    <div class="overflow-y-auto overflow-x-hidden flex flex-col justify-between flex-grow">
      <ul class="flex flex-col py-4 space-y-1">
        <li class="px-5 hidden md:block">
          <div class="flex flex-row items-center h-8">
            <div class="text-sm font-light tracking-wide text-gray-400 uppercase">Main</div>
          </div>
        </li>
        <li>
          <a href="{{ route('employee.dashboard') }}" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
            <span class="inline-flex justify-center items-center ml-4">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
              </svg>
            </span>
            <span class="ml-2 text-sm tracking-wide truncate">Dashboard</span>
          </a>
        </li>
      
       
         
        <li>
        <li class="opcion-con-desplegable">
          <div class="flex items-center justify-between p-2 hover:bg-gray-700 cursor-pointer">
            <div class="flex items-center">
              <i class="fas fa-calendar-alt mr-2"></i>
              <span>Timesheet</span>
            </div>
            <i class="fas fa-chevron-down text-xs"></i>
          </div>
          <ul class="desplegable ml-4 hidden">
            <li>
              <a href="{{route('employee.time-entries')}}" class="block p-2 hover:bg-gray-700 flex items-center">
                <i class="fas fa-chevron-right mr-2 text-xs"></i>
               Timesheet Entry
              </a>
            </li>
            <li>
              <a href="{{ route('employee.timesheet') }}" class="block p-2 hover:bg-gray-700 flex items-center">
                <i class="fas fa-chevron-right mr-2 text-xs"></i>
                Timesheet List
              </a>
            </li>
            <li>
              <a href="{{ route('employee-report.index') }}" class="block p-2 hover:bg-gray-700 flex items-center">
                <i class="fas fa-chevron-right mr-2 text-xs"></i>
               Employee Report
              </a>
            </li>
          </ul>

        </li>
        <li class="opcion-con-desplegable">
          <div class="flex items-center justify-between p-2 hover:bg-gray-700 cursor-pointer">
            <div class="flex items-center">
              <i class="fas fa-calendar-alt mr-2"></i>
              <span>Project</span>
            </div>
            <i class="fas fa-chevron-down text-xs"></i>
          </div>
          <ul class="desplegable ml-4 hidden">
            <li>
              <a href="{{route('employee.projects.index')}}" class="block p-2 hover:bg-gray-700 flex items-center">
                <i class="fas fa-chevron-right mr-2 text-xs"></i>
               Project List
              </a>
            </li>
            <li>
              <a href="{{ route('employee.project-allocations.index') }}" class="block p-2 hover:bg-gray-700 flex items-center">
                <i class="fas fa-chevron-right mr-2 text-xs"></i>
                Project Allocations
              </a>
            </li>
            <li>
              <a href="{{ route('employee.project-groups.index') }}" class="block p-2 hover:bg-gray-700 flex items-center">
                <i class="fas fa-chevron-right mr-2 text-xs"></i>
                Project Groups
              </a>
            </li>
          </ul>

        </li>
        <li class="opcion-con-desplegable">
          <div class="flex items-center justify-between p-2 hover:bg-gray-700 cursor-pointer">
            <div class="flex items-center">
              <i class="fas fa-calendar-alt mr-2"></i>
              <span>Attendance</span>
            </div>
            <i class="fas fa-chevron-down text-xs"></i>
          </div>
          <ul class="desplegable ml-4 hidden">
            <li>
              <a href="{{route('employee.attendance.line')}}" class="block p-2 hover:bg-gray-700 flex items-center">
                <i class="fas fa-chevron-right mr-2 text-xs"></i>
               List View
              </a>
            </li>
            <li>
              <a href="{{ route('employee.attendance') }}" class="block p-2 hover:bg-gray-700 flex items-center">
                <i class="fas fa-chevron-right mr-2 text-xs"></i>
                Tabular View
              </a>
            </li>
            <li>
              <a href="{{ route('employee.attendance.calendar') }}" class="block p-2 hover:bg-gray-700 flex items-center">
                <i class="fas fa-chevron-right mr-2 text-xs"></i>
                Calendar View
              </a>
            </li>
          </ul>

        </li>
        <li class="opcion-con-desplegable">
          <div class="flex items-center justify-between p-2 hover:bg-gray-700 cursor-pointer">
            <div class="flex items-center">
              <i class="fas fa-calendar-alt mr-2"></i>
              <span>Leave Tracker</span>
            </div>
            <i class="fas fa-chevron-down text-xs"></i>
          </div>
          <ul class="desplegable ml-4 hidden">
            <li>
              <a href="{{route('employee.attendance.line')}}" class="block p-2 hover:bg-gray-700 flex items-center">
                <i class="fas fa-chevron-right mr-2 text-xs"></i>
               List View
              </a>
            </li>
            <li>
              <a href="{{ route('employee.attendance') }}" class="block p-2 hover:bg-gray-700 flex items-center">
                <i class="fas fa-chevron-right mr-2 text-xs"></i>
                Tabular View
              </a>
            </li>
            <li>
              <a href="{{ route('employee.attendance.calendar') }}" class="block p-2 hover:bg-gray-700 flex items-center">
                <i class="fas fa-chevron-right mr-2 text-xs"></i>
                Calendar View
              </a>
            </li>
          </ul>

        </li>
        <a href="{{route('holiday.index')}}" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
            <span class="inline-flex justify-center items-center ml-4">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
              </svg>
            </span>
            <span class="ml-2 text-sm tracking-wide truncate">Holidays</span>
          </a>
        </li>
      
        <li>
          <a href="#" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
            <span class="inline-flex justify-center items-center ml-4">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
              </svg>
            </span>
            <span class="ml-2 text-sm tracking-wide truncate">Notifications</span>
            <span class="hidden md:block px-2 py-0.5 ml-auto text-xs font-medium tracking-wide text-red-500 bg-red-50 rounded-full">1.2k</span>
          </a>
        </li>
        <li class="px-5 hidden md:block">
          <div class="flex flex-row items-center mt-5 h-8">
            <div class="text-sm font-light tracking-wide text-gray-400 uppercase">Settings</div>
          </div>
        </li>
        <li>
          <a href="#" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
            <span class="inline-flex justify-center items-center ml-4">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </span>
            <span class="ml-2 text-sm tracking-wide truncate">Profile</span>
          </a>
        </li>
        <li>
          <a href="#" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
            <span class="inline-flex justify-center items-center ml-4">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
            </span>
            <span class="ml-2 text-sm tracking-wide truncate">Settings</span>
          </a>
        </li>
      </ul>
      <p class="mb-14 px-5 py-3 hidden md:block text-center text-xs">Copyright @2021</p>
    </div>

  </div>
  <!-- ./Sidebar -->
  @show
  <div>
    <div x-data="setup()" :class="{ 'dark': isDark }">
      <div class="min-h-screen flex flex-col flex-auto flex-shrink-0 antialiased bg-white dark:bg-gray-700 text-black dark:text-white">

        <!-- Header -->
        <div class="fixed w-full flex items-center justify-between h-14 text-white z-10">
          <div class="flex items-center justify-start md:justify-center pl-3 w-14 md:w-64 h-14 bg-blue-800 dark:bg-gray-800 border-none">
            <img class="w-7 h-7 md:w-10 md:h-10 mr-2 rounded-md overflow-hidden" src="https://therminic2018.eu/wp-content/uploads/2018/07/dummy-avatar.jpg" />
            <span class="hidden md:block">{{ Auth::guard('employee')->user()->name }}</span>
          </div>
          <div class="flex justify-between items-center h-14 bg-blue-800 dark:bg-gray-800 header-right">
            <div class="bg-white rounded flex items-center w-full max-w-xl mr-4 p- shadow-sm border border-gray-200">
              <button class="outline-none focus:outline-none">

              </button>
              <input type="search" name="" id="" placeholder="Search" class="w-full pl-3 text-sm text-black outline-none focus:outline-none bg-transparent" />
            </div>
            <ul class="flex items-center">
              <li>
                <button
                  aria-hidden="true"
                  @click="toggleTheme"
                  class="group p-2 transition-colors duration-200 rounded-full shadow-md bg-blue-200 hover:bg-blue-200 dark:bg-gray-50 dark:hover:bg-gray-200 text-gray-900 focus:outline-none">
                  <svg
                    x-show="isDark"
                    width="24"
                    height="24"
                    class="fill-current text-gray-700 group-hover:text-gray-500 group-focus:text-gray-700 dark:text-gray-700 dark:group-hover:text-gray-500 dark:group-focus:text-gray-700"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                  </svg>
                  <svg
                    x-show="!isDark"
                    width="24"
                    height="24"
                    class="fill-current text-gray-700 group-hover:text-gray-500 group-focus:text-gray-700 dark:text-gray-700 dark:group-hover:text-gray-500 dark:group-focus:text-gray-700"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                  </svg>
                </button>
              </li>
              <li>
                <div class="block w-px h-6 mx-3 bg-gray-400 dark:bg-gray-700"></div>
              </li>
              <li>
                @livewire('logout')
              </li>
            </ul>
          </div>
        </div>
        <div class="col  ">
          @yield('content')
        </div>
      </div>
      <!-- ./Header -->
     <!-- Dark mode setup script -->
  <script>
    const setup = () => {
      const getTheme = () => {
        if (window.localStorage.getItem('dark')) {
          return JSON.parse(window.localStorage.getItem('dark'));
        }
        return !!window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
      }

      const setTheme = (value) => {
        window.localStorage.setItem('dark', value);
      }

      return {
        loading: true,
        isDark: getTheme(),
        toggleTheme() {
          this.isDark = !this.isDark;
          setTheme(this.isDark);
          document.documentElement.classList.toggle('dark', this.isDark);
        },
      }
    }
  </script>
      <script>
        document.addEventListener("DOMContentLoaded", function() {
          // Obtener todas las opciones principales con desplegables
          const opcionesConDesplegable = document.querySelectorAll(".opcion-con-desplegable");

          // Agregar evento de clic a cada opción principal
          opcionesConDesplegable.forEach(function(opcion) {
            opcion.addEventListener("click", function() {
              // Obtener el desplegable asociado a la opción
              const desplegable = opcion.querySelector(".desplegable");

              // Alternar la clase "hidden" para mostrar u ocultar el desplegable
              desplegable.classList.toggle("hidden");
            });
          });
        });
      </script>
</body>

</html>