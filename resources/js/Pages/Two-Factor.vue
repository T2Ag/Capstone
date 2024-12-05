<template>
   <section class="bg-gradient-to-b from-gray-400 to-black min-h-screen flex items-center justify-center">
      <div class="bg-white rounded shadow-lg max-w-3x1">
         <div class="w-[25rem] p-10">
            <div class="py-3 flex justify-center text-center">
               <h2 class="font-bold text-2xl">Please Enter Your Two Factor Authentication</h2>
            </div>

            <SessionMessages  :status="status"/>

            <div>
               <form @submit.prevent="submit" class="flex flex-col">
                  <label for="username" class="py-2">Please enter the code sent to your gmail here.</label>
                  <input 
                     v-model="form.code"
                     type="text" 
                     name="username" 
                     placeholder="Enter your Code" 
                     class="border rounded w-full p-2 text-sm placeholder:text-xs placeholder:text-gray-400"
                  >
                  <div class="text-red-500 text-sm mb-1">{{ form.errors.code }}</div>

                  <button class="px-3 py-2 bg-red-700 my-3 text-white rounded">Verify</button>
               </form>

               <!-- Resend Code Button -->
               <div class="flex flex-col justify-centertext-center">
                  <button 
                     @click="resendCode" 
                     class="text-blue-500 hover:underline mb-4"
                  >
                     Didn't receive code? Resend
                  </button>

                  <Link  method="post" as="button" :href="route('logout')"  class=" mx-auto hover:underline text-red-700" >
                     Logout
                  </Link>
               </div>
            </div>
         </div>
      </div>
   </section>
</template>

<script setup>
import { router, useForm } from "@inertiajs/vue3";
import SessionMessages from '../Components/SessionMessages.vue'

const props = defineProps({
   status: String
 })

const form = useForm({
   code: '',
});

const submit = () => {
   form.post(route('two-factor.verify'), {
      onError: () => form.reset("code")
   });
};

const resendCode = () => {
   router.visit(route('two-factor.resend'), {
      method: 'post'
   });
};
</script>