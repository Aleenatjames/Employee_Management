<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
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
</x-app-layout>
