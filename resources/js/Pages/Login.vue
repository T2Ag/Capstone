<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
   username: '',
   password: '',
});

const submit = () => {
   form.post(route('login.post'), {
      onError: () => form.reset("password")
   });
};
</script>

<template>
   <Head title="Login" />
   <section class="bg-gradient-to-b from-gray-400 to-black min-h-screen flex items-center justify-center">

      <div class="bg-white flex rounded shadow-lg max-w-3x1">

         <!-- image -->
         <div class="w-[15rem] h-[30rem]">
            <img src="https://t4.ftcdn.net/jpg/00/99/82/15/360_F_99821575_nVEHTBXzUnTcLIKN6yOymAWAnFwEybGb.jpg" alt="img" class="w-full h-full object-cover rounded">
         </div>

         <!-- login form-->
         <div class="w-[25rem] p-10">
            <div class="p-10 flex justify-center ">
               <h2 class="font-bold text-2xl">Welcome!</h2>
            </div>

            <div>
               <form @submit.prevent="submit" class="flex flex-col">

                  <label for="username">Username</label>
                  <input 
                     v-model="form.username"
                     type="text" 
                     name="username" 
                     placeholder="Enter your username" 
                     class="border rounded w-full p-1 text-sm placeholder:text-xs placeholder:text-gray-400"
                  >
                  <div class="text-red-500 text-sm mb-1">{{ form.errors.username }}</div>

                  <label for="password">Password</label>
                  <input 
                     v-model="form.password"
                     type="password" 
                     name="password" 
                     placeholder="Enter your password" 
                     class="border rounded w-full p-1 text-sm placeholder:text-xs placeholder:text-gray-400"
                  >
                  <div class="text-red-500 text-sm mb-1">{{ form.errors.password }}</div>

                  <button class="px-3 py-2 bg-red-700 my-3 text-white rounded">Login</button>
               </form>
            </div>
         </div>
         
      </div>

   </section>
</template>
