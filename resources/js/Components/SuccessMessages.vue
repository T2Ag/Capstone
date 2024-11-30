<template>

   <div v-if="success" class="font-medium text-md text-green-600">
      {{ success }}
   </div>
   
   </template>
   
<script setup>

defineProps({
   success:String,
})

</script>