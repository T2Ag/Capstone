<template>

<UserLayout>
<div class="p-4">
   <div class="pb-8">
      <h1 class="text-3xl font-bold text-gray-800">
         To Do List
      </h1>
   </div>
   <div class="px-4">
      
      <ToDoListTable 
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
import ToDoListTable from '../../Components/ToDoListTable.vue';

const props = defineProps({
   user: Object,
   todos: Array

})

</script>