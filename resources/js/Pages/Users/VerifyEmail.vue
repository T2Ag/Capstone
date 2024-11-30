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
  <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg p-6 max-w-lg text-center">
      <h1 class="text-2xl font-bold text-gray-800 mb-4">Thanks for Signing Up!</h1>
      <p class="text-gray-600 mb-6">
        We're excited to have you on board. Please check your email for a verification link to activate your account.
      </p>

      <SessionMessages  :status="status"/>

      <p class="text-gray-600 mb-6">
        Didn't receive the email? Be sure to check your spam folder or
        <form @submit.prevent="submit">
            <button class="text-blue-500 underline hover:text-blue-700">resend verification email</button>.
        </form>
       
      </p>

      <button
        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition duration-200"
      >
        Go to Homepage
      </button>
    </div>
  </div>
</template>
