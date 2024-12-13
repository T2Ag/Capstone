<template>

<UserLayout>
<div class="p-4">
   <div class="pb-8">
      <h1 class="text-3xl font-bold text-gray-800">
         Training
      </h1>
   </div>

   <div class="px-4">
      <div v-if="training" class="bg-white shadow-md rounded p-4 mb-6">
         <h2 class="text-2xl font-semibold text-gray-700 mb-2">Current Training</h2>
         <p class="text-gray-600">
         <strong>Training Name:</strong> {{ training.name }}
         </p>
         <p class="text-gray-600">
         <strong>Coach:</strong> {{ training.coach.first_name }} {{ training.coach.last_name }}
         </p>
      </div>
      <div v-else class="text-gray-500 text-center py-4">
         No training assigned to this user.
      </div>
   </div>



   <div class="px-4">

      <div v-if="success" class="px-2 py-1 bg-green-200 rounded border-1 border-green-500 ">
         <SuccessMessages :success="success" class="p-3"/>
      </div>
      
      <UserPageToDoListTable 
         v-if="user.client" 
         :todos="todos" 
         :client="user.client"
      />
      
      <div v-else class="text-gray-500 text-center py-4">
         This user has no registered client
      </div>

   </div>
</div>


</UserLayout>
   
</template>
<script setup>
import UserLayout from '../../Layouts/UserLayout.vue';
import UserPageToDoListTable from '../../Components/UserPage/UserPageToDoListTable.vue';
import SuccessMessages from '../../Components/SuccessMessages.vue'

const props = defineProps({
   user: Object,
   todos: Array,
   training: Object,
   success: String
})

</script>