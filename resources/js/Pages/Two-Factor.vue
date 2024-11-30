<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
   code: '',

});

const submit = () => {
   form.post(route('two-factor.verify'), {
      onError: () => form.reset("code")
   });
};
</script>

<template>
   <Head title="Login" />
   <section class="bg-gradient-to-b from-gray-400 to-black min-h-screen flex items-center justify-center">

      <div class="bg-white rounded shadow-lg max-w-3x1">

         <!-- login form-->
         <div class="w-[25rem] p-10">
            <div class="p-2 flex justify-center ">
               <h2 class="font-bold text-2xl">Please Enter Your Two Factor Authentication</h2>
            </div>

            <div>
               <form @submit.prevent="submit" class="flex flex-col">

                  <label for="username">Please enter the code set to your gmail here.</label>
                  <input 
                     v-model="form.code"
                     type="text" 
                     name="username" 
                     placeholder="Enter your Code" 
                     class="border rounded w-full p-2 text-sm placeholder:text-xs placeholder:text-gray-400"
                  >
                  <div class="text-red-500 text-sm mb-1">{{ form.errors.code }}</div>

                  <button class="px-3 py-2 bg-green-700 my-3 text-white rounded">Verify</button>

               </form>
            </div>
         </div>
         
      </div>

   </section>
</template>
