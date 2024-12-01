<script setup>
import { useForm } from '@inertiajs/vue3';
import SessionMessages from '../../Components/SessionMessages.vue';

defineProps({
   status: String
})

const form = useForm({
   
})

const submit = () => {
   form.post(route('verification.send'))
}
</script>

<template>
  <div class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-b from-gray-400 to-black ">
    <div class="bg-white shadow-lg rounded-lg p-[5rem] max-w-lg text-center">
      <h1 class="text-2xl font-bold text-gray-800 mb-4">Thanks for Signing Up on <span class="text-red-700">FLEX!</span></h1>
      <p class="text-gray-600 mb-6">
        We're excited to have you on board. Please check your email for a verification link to activate your account.
      </p>

      <SessionMessages  :status="status"/>

      <p class="text-gray-600 mb-6">
        Didn't receive the email? Be sure to check your spam folder or
        <form @submit.prevent="submit">
            <button
              class="bg-red-700 hover:bg-red-800 text-white font-semibold py-2 px-4 mt-2 rounded transition duration-200"
            >
              Resend Verification Email
            </button>
        </form>
       
      </p>


    </div>
  </div>
</template>
