<template>

<UserLayout>
<div class="p-4">
   <div class="pb-8">
      <h1 class="text-3xl font-bold text-gray-800">
         To Do List
      </h1>
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
   success: String
})

</script>